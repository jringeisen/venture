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

class OpenAIAdapter implements AIAdapterInterface
{
    protected readonly PromptQuestion $question;

    /** @var Collection<OpenAIMessageObject> $messages */
    protected Collection $messages;

    protected readonly Student|User $user; // TODO: Will need to update this after Jonathon's code is merged

    public function __construct()
    {
        $this->messages = collect();
    }

    /**
     * @return AIContentDto
     * @throws JsonException
     */
    public function request(): AIContentDto
    {
        $response = OpenAI::chat()
            ->create([
                'model' => 'gpt-3.5-turbo-1106',
                'response_format' => ['type' => 'json_object'],
                'messages' => $this->messages->toArray(),
                'user' => 'user-' . $this->user->id,
            ]);

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

    /**
     * @param mixed $response
     * @return AIContentDto
     */
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
        }

        if (property_exists($response, 'subject')) {
            $dto->setSubject($response->subject);
            $dto->setSubCategory($response->sub_category);
        }

        if (property_exists($response, 'questions')) {
            $dto->setQuestions($response->questions);
        }

        return $dto;
    }

    public function addMessage(OpenAIMessageObject $messageObject): self
    {
        $this->messages = $this->messages->add($messageObject);

        return $this;
    }

    public function setUser(Student|User $user): self // TODO: Will need to update this after Jonathon's code is merged
    {
        $this->user = $user;

        return $this;
    }

    public function setQuestion(PromptQuestion $question): self
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @param mixed $input
     * @return AIContentDto
     */
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
