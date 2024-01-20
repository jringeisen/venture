<?php

namespace App\Services\AI;

use App\Models\PromptQuestion;
use App\Models\Student;
use App\Models\User;
use App\Services\Adapters\AI\OpenAI\Objects\OpenAIMessageObject;
use App\Services\Adapters\AI\OpenAI\OpenAIAdapter;
use App\Services\Dto;
use App\Services\Dto\AIContentDto;
use App\Services\Interfaces\AIServiceInterface;
use Exception;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class OpenAIService implements AIServiceInterface
{
    protected array $messages = [];

    protected Student|User $user;

    protected PromptQuestion $question;

    public function __construct(private readonly OpenAIAdapter $adapter, Student|User $user)
    {
        $this->user = $user;

        $this->adapter->setUser($this->user);
    }

    public function createChat(): Dto\AIContentDto
    {
        try {
            return $this->adapter
                ->request();
        } catch (Exception $e) {
            Log::error('Error in OpenAIServiceService::create: ' . $e->getMessage());

            return new AIContentDto();
        }
    }

    public function messages(string $role, string $content): self
    {
        if (trim($content) === '') {
            throw new InvalidArgumentException('Content cannot be null or empty');
        }

        $this->adapter
            ->addMessage(
                (new OpenAIMessageObject())
                    ->setRole($role)
                    ->setContent($content)
            );

        return $this;
    }

    public function updateQuestionTokens(PromptQuestion $question): self
    {
        $this->adapter
            ->setQuestion($question);

        return $this;
    }

    /**
     * @param mixed $input
     * @return AIContentDto
     */
    public function moderate(mixed $input): AIContentDto
    {
        return $this->adapter->moderate($input);
    }
}
