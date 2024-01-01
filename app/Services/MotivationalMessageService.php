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
        $dayMotivationalMessageWasLastSaved = $this->student->motivational_message;
        $todaysDate = now()->timezone($this->timezone)->startOfDay();

        return is_null($dayMotivationalMessageWasLastSaved)
            || ($this->student->motivational_message && $dayMotivationalMessageWasLastSaved->lessThan($todaysDate));
    }

    protected function callOpenAI(): string
    {
        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo-1106',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "Using the tone of {$this->getRandomPerson()}, generate a motivational quote for a child that is {$this->student->age} years old.",
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
        ])->random();
    }
}
