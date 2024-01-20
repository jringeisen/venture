<?php

namespace App\Services\Interfaces;

use App\Models\PromptQuestion;
use App\Models\Student;
use App\Models\User;
use App\Services\Dto\AIContentDto;

/**
 * @property Student|User $user; TODO: Will need to update after Jonathon's code changes.
 * @property PromptQuestion $question;
 */
interface AIServiceInterface {
    public function createChat(): AIContentDto;
    public function moderate(mixed $input): AIContentDto;
}