<?php

namespace App\Services\Interfaces;

use App\Models\PromptQuestion;
use App\Models\Student;
use App\Models\User;
use App\Services\Dto\AIContentDto;

/**
 * @property Student|User $user;
 * @property PromptQuestion $question;
 */
interface AIServiceInterface
{
    public function createChat(bool $isStringResponse = false): AIContentDto;

    public function createStream(): AIContentDto;

    public function moderate(mixed $input): AIContentDto;
}
