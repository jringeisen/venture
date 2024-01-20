<?php

namespace App\Services\Adapters\AI\OpenAI\Objects;

class OpenAIMessageObject
{
    public readonly string $role;
    public readonly string $content;

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
