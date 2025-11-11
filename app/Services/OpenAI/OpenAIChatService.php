<?php

namespace App\Services\OpenAI;

use App\Models\PromptQuestion;
use App\Models\Student;
use App\Models\User;
use App\Services\OpenAI\Responses\ChatResponse;
use App\Services\OpenAI\Responses\QuestionsResponse;
use App\Services\OpenAI\Responses\StreamResponse;
use App\Services\OpenAI\Responses\SubjectResponse;
use Illuminate\Support\Collection;
use JsonException;
use OpenAI\Laravel\Facades\OpenAI;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OpenAIChatService
{
    protected const MODEL = 'gpt-4o-mini';

    /** @var Collection<array{role: string, content: string}> */
    protected Collection $messages;

    protected ?PromptQuestion $question = null;

    protected Student|User|null $user = null;

    public function __construct()
    {
        $this->messages = collect();
    }

    public function addMessage(string $role, string $content): self
    {
        $this->messages->push([
            'role' => $role,
            'content' => $content,
        ]);

        return $this;
    }

    public function setUser(Student|User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function updateQuestionTokens(PromptQuestion $question): self
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @throws JsonException
     */
    public function createChat(bool $expectJsonResponse = true): ChatResponse|SubjectResponse|QuestionsResponse
    {
        $chatCreationOptions = [
            'model' => self::MODEL,
            'messages' => $this->messages->toArray(),
        ];

        if ($this->user) {
            $chatCreationOptions['user'] = 'user-'.$this->user->id;
        }

        if ($expectJsonResponse) {
            $chatCreationOptions['response_format'] = ['type' => 'json_object'];
        }

        $response = OpenAI::chat()->create($chatCreationOptions);

        // Update token count if question is set
        if ($this->question && isset($response['usage']['total_tokens'])) {
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

        // Return specific response types based on the content
        if (property_exists($decodedResponse, 'subject')) {
            return SubjectResponse::fromJson($decodedResponse);
        }

        if (property_exists($decodedResponse, 'questions')) {
            return QuestionsResponse::fromJson($decodedResponse);
        }

        return ChatResponse::fromJson($decodedResponse);
    }

    public function createStream(): StreamResponse
    {
        $streamedResponse = new StreamedResponse(
            function () {
                header('Content-Type: text/event-stream');
                header('Cache-Control: no-cache');
                header('X-Accel-Buffering: no');

                $chatCreationOptions = [
                    'model' => self::MODEL,
                    'messages' => $this->messages->toArray(),
                ];

                if ($this->user) {
                    $chatCreationOptions['user'] = 'user-'.$this->user->id;
                }

                $stream = OpenAI::chat()->createStreamed($chatCreationOptions);

                $message = '';

                foreach ($stream as $response) {
                    $data = $response->choices[0]->toArray();

                    if ($data['finish_reason'] === null) {
                        $message .= $data['delta']['content'];
                    }

                    if ($data['finish_reason'] === 'stop') {
                        // Update token count if question is set
                        if ($this->question && isset($data['usage']['total_tokens'])) {
                            $this->question->update([
                                'total_tokens' => $this->question->total_tokens + $response['usage']['total_tokens'],
                            ]);
                        }

                        // Save the answer
                        if ($this->question) {
                            $this->question
                                ->promptAnswer()
                                ->updateOrCreate(
                                    ['prompt_question_id' => $this->question->id],
                                    ['content' => $message, 'word_count' => str_word_count($message)]
                                );
                        }
                    }

                    echo 'data: '.json_encode($data, JSON_THROW_ON_ERROR)."\n\n";

                    flush();
                }
            });

        return new StreamResponse($streamedResponse);
    }
}
