<?php

namespace App\Services\Objects;

class ModerationObject
{
    public readonly bool $flagged;

    public readonly ?string $message;

    public function setFlagged(bool $flagged): self
    {
        $this->flagged = $flagged;

        return $this;
    }

    public function setMessage(?string $message = null): self
    {
        $this->message = $message;

        return $this;
    }
}
