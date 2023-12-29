<?php

namespace App\Services;

use App\Models\Student;
use OpenAI\Laravel\Facades\OpenAI;

class MotivationalMessageService
{
    protected Student $student;

    protected string $timezone;

    public function __construct(Student $student)
    {
        $this->student = $student;
        $this->timezone = $student->timezone;
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
        $dayMotivationalMessageWasLastSaved = $this->student->motivational_message?->timezone($this->timezone)->startOfDay()->utc();
        $todaysDate = today()->timezone($this->timezone)->startOfDay()->utc();

        return $this->student->motivational_message
            && $dayMotivationalMessageWasLastSaved->lessThan($todaysDate);
    }

    protected function callOpenAI(): string
    {
        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo-1106',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "Using the tone of {$this->getRandomPerson()}, generate a motivational quote for a child between the ages of 11 and 15.",
                ],
            ],
            'user' => 'user-'.$this->student->id,
        ]);

        return $response->choices[0]->message->content;
    }

    protected function getRandomPerson(): string
    {
        return collect([
            'Neil Degrasse Tyson',
            'Joe Rogan',
            'Elon Musk',
            'Andy Frisella',
            'David Goggins',
            'Jocko Willink',
        ])->random();
    }
}
