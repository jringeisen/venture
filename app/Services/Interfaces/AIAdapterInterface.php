<?php

namespace App\Services\Interfaces;

use App\Models\PromptQuestion;
use App\Services\Dto\AIContentDto;

interface AIAdapterInterface
{
    public function request(): AIContentDto;

    public function convertResponse(mixed $response): AIContentDto;
    public function setQuestion(PromptQuestion $question): self;
    public function moderate(mixed $input): AIContentDto;
}
