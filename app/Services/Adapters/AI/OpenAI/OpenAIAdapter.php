<?php

namespace App\Services\Adapters\AI\OpenAI;

use App\Models\PromptQuestion;
use App\Models\Student;
use App\Models\User;
use App\Services\Adapters\AI\OpenAI\Objects\OpenAIMessageObject;
use App\Services\Dto\AIContentDto;
use App\Services\Interfaces\AIAdapterInterface;
use App\Services\Objects\ModerationObject;
use Illuminate\Support\Collection;
use JsonException;
use OpenAI\Laravel\Facades\OpenAI;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OpenAIAdapter implements AIAdapterInterface
{
    protected const MODEL = 'gpt-4o-mini';

    protected readonly PromptQuestion $question;

    /** @var Collection<OpenAIMessageObject> */
    protected Collection $messages;

    protected readonly Student|User $user;

    public function __construct()
    {
        $this->messages = collect();
    }

    /**
     * @throws JsonException
     */
    public function request(bool $isString = false): AIContentDto
    {
        $chatCreationOptions = [
            'model' => self::MODEL,
            'messages' => $this->messages->toArray(),
            'user' => 'user-'.$this->user->id,
        ];

        if (! $isString) {
            $chatCreationOptions['response_format'] = ['type' => 'json_object'];
        }

        $response = OpenAI::chat()
            ->create($chatCreationOptions);

        if (isset($this->question, $response['usage']['total_tokens'])) {
            $this->question->update([
                'total_tokens' => $this->question->total_tokens + $response['usage']['total_tokens'],
            ]);
        }

        $decodedResponse = json_decode(
            $response->choices[0]->message->content,
            false,
            512,
            JSON_THROW_ON_ERROR
        );

        return $this->convertResponse($decodedResponse);
    }

    public function convertResponse(mixed $response): AIContentDto
    {
        $dto = new AIContentDto();

        if (property_exists($response, 'flagged')) {
            $moderationObject = new ModerationObject();

            $moderationObject->setFlagged($response->flagged);

            if ($response->flagged) {
                $moderationObject->setMessage($response->message);
            }

            $dto->setModeration($moderationObject);
        } elseif (property_exists($response, 'subject')) {
            $dto->setSubject($response->subject);
            $dto->setSubCategory($response->sub_category);
        } elseif (property_exists($response, 'questions')) {
            $dto->setQuestions($response->questions);
        } elseif (is_string($response)) {
            $dto->setMessage($response);
        }

        return $dto;
    }

    public function requestStream(): AIContentDto
    {
        $streamResponse = new StreamedResponse(
            function () {
                header('Content-Type: text/event-stream');
                header('Cache-Control: no-cache');
                header('X-Accel-Buffering: no');

                $stream = OpenAI::chat()
                    ->createStreamed([
                        'model' => self::MODEL,
                        'messages' => $this->messages->toArray(),
                        'user' => 'user-'.$this->user->id,
                    ]);

                $message = '';

                foreach ($stream as $response) {
                    $data = $response->choices[0]->toArray();

                    if ($data['finish_reason'] === null) {
                        $message .= $data['delta']['content'];
                    }

                    if ($data['finish_reason'] === 'stop') {
                        if (isset($data['usage']['total_tokens'])) {
                            $this->question->update([
                                'total_tokens' => $this->question->total_tokens + $response['usage']['total_tokens'],
                            ]);
                        }

                        $this->question
                            ->promptAnswer()
                            ->updateOrCreate(
                                ['prompt_question_id' => $this->question->id],
                                ['content' => $message, 'word_count' => str_word_count($message)]
                            );
                    }

                    echo 'data: '.json_encode($data, JSON_THROW_ON_ERROR)."\n\n";

                    ob_flush();
                    flush();

                    sleep(0.5);
                }
            });

        return (new AIContentDto())->setStream($streamResponse);
    }

    public function addMessage(OpenAIMessageObject $messageObject): self
    {
        $this->messages = $this->messages->add($messageObject);

        return $this;
    }

    public function setUser(Student|User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function setQuestion(PromptQuestion $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function moderate(mixed $input): AIContentDto
    {
        $dto = new AIContentDto();

        $moderation = OpenAI::moderations()
            ->create(
                [
                    'model' => 'text-moderation-latest',
                    'input' => $input,
                ]
            );

        $moderationObject = new ModerationObject();

        $moderationObject->setFlagged($moderation->results[0]->flagged);

        $moderationObject->setMessage();

        $dto->setModeration($moderationObject);

        return $dto;
    }
}
