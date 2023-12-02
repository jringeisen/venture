<?php

namespace App\Services;

use App\Models\PromptQuestion;
use App\Models\Student;
use App\Models\User;
use OpenAI\Laravel\Facades\OpenAI;

class OpenAIService
{
    protected array $messages = [];

    protected Student|User $user;

    protected PromptQuestion $question;

    public function create(): array
    {
        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo-1106',
            'response_format' => ['type' => 'json_object'],
            'messages' => $this->messages,
            'user' => 'user-'.$this->user->id,
        ]);

        if (isset($response['usage']['total_tokens'])) {
            $this->question->update([
                'total_tokens' => $this->question->total_tokens + $response['usage']['total_tokens'],
            ]);
        }

        return json_decode($response->choices[0]->message->content, true);
    }

    public function messages(string $role, string $content): self
    {
        $this->messages[] = [
            'role' => $role,
            'content' => $content,
        ];

        return $this;
    }

    public function user(Student|User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function updateQuestionTokens(PromptQuestion $question): self
    {
        $this->question = $question;

        return $this;
    }
}
