<?php

namespace App\Services;

use App\Models\User;
use App\Models\Student;
use OpenAI\Laravel\Facades\OpenAI;

class OpenAIService
{
    protected array $messages = [];
    protected Student|User $user;

    public function create(): array
    {
        $response = OpenAI::chat()->create([
            'model' => 'gpt-4-1106-preview',
            'response_format' => ['type' => 'json_object'],
            'messages' => $this->messages,
            'user' => 'user-' . $this->user->id,
        ]);

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
}
