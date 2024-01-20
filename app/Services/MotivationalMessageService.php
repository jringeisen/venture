<?php

namespace App\Services;

use App\Models\User;
use OpenAI\Laravel\Facades\OpenAI;

class MotivationalMessageService
{
    protected User $user;

    protected string $timezone;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->timezone = $user->timezone;
    }

    public function generate(): ?string
    {
        if ($this->shouldCreateMessage()) {
            return $this->callOpenAI();
        }

        return null;
    }

    protected function shouldCreateMessage(): bool
    {
        $dayMotivationalMessageWasLastSaved = $this->user->motivational_message;
        $todaysDate = now()->timezone($this->timezone)->startOfDay();

        return is_null($dayMotivationalMessageWasLastSaved)
            || ($this->user->motivational_message && $dayMotivationalMessageWasLastSaved->lessThan($todaysDate));
    }

    protected function callOpenAI(): string
    {
        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo-1106',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "Using the tone of {$this->getRandomPerson()}, generate a motivational quote for a child that is {$this->user->age} years old.",
                ],
            ],
            'user' => 'user-'.$this->user->id,
        ]);

        return $response->choices[0]->message->content;
    }

    protected function getRandomPerson(): string
    {
        return collect([
            'Neil Degrasse Tyson',
        ])->random();
    }
}
