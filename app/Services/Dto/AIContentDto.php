<?php

namespace App\Services\Dto;

use App\Services\Objects\ModerationObject;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AIContentDto
{
    public readonly string $subject;

    public readonly string $subCategory;

    public readonly array $questions;

    public readonly ModerationObject $moderation;

    public readonly string $message;

    public readonly StreamedResponse $stream;

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function setSubCategory(?string $subCategory): self
    {
        $this->subCategory = $subCategory;

        return $this;
    }

    /** @var array<string> */
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

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function setStream(StreamedResponse $stream): self
    {
        $this->stream = $stream;

        return $this;
    }
}
