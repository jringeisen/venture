<?php

namespace App\Services;

use App\Models\User;
use App\Services\Interfaces\AIServiceInterface;
use Illuminate\Contracts\Container\BindingResolutionException;

class MotivationalMessageService
{
    protected User $user;

    protected string $timezone;

    protected AIServiceInterface $aIService;

    /**
     * @throws BindingResolutionException
     */
    public function __construct(User $user)
    {
        $this->aIService = app()->make(AIServiceInterface::class, ['aiService' => 'OpenAI']);

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
        $this->aIService
            ->addMessage(
                'system',
                "Using the tone of {$this->getRandomPerson()}, generate a motivational quote for a child that is {$this->user->age} years old."
            );

        return $this->aIService->createChat(true)->message;
    }

    protected function getRandomPerson(): string
    {
        return collect([
            'Neil Degrasse Tyson',
        ])->random();
    }
}
