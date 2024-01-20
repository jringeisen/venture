<?php

namespace App\Services\Dto;

use App\Services\Objects\ModerationObject;

class AIContentDto
{
    public readonly string $subject;
    public readonly string $subCategory;
    public readonly array $questions;
    public readonly ModerationObject $moderation;

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function setSubCategory(string $subCategory): self
    {
        $this->subCategory = $subCategory;

        return $this;
    }

    /** @var array<string> $questions */
    public function setQuestions(array $questions): self
    {
        $this->questions = $questions;

        return $this;
    }

    public function setModeration(ModerationObject $moderationObject): self
    {
        $this->moderation = $moderationObject;

        return $this;
    }
}
