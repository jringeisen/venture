<?php

use App\Ai\Agents\QuestionGenerationAgent;
use App\Models\Prompt;
use App\Models\PromptQuestion;
use App\Models\User;

it('returns questions as an array of strings from structured output', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create([
        'parent_id' => $parent->id,
        'username' => fake()->userName(),
        'grade' => 5,
        'age' => 10,
    ]);

    Prompt::create(['category' => 'questions', 'prompt' => 'Generate related questions.']);

    $promptQuestion = PromptQuestion::withoutEvents(function () use ($student) {
        return PromptQuestion::factory()->create([
            'user_id' => $student->id,
        ]);
    });

    QuestionGenerationAgent::fake([
        [
            'questions' => [
                'What causes rain to fall from clouds?',
                'How do rivers form and where do they go?',
                'Why is the ocean salty?',
            ],
        ],
    ]);

    $response = $this->actingAs($student)
        ->postJson('/student/prompts/questions', [
            'question' => $promptQuestion->question,
        ]);

    $response->assertSuccessful()
        ->assertJsonCount(3, 'questions')
        ->assertJsonStructure([
            'questions' => [
                '*' => ['question', 'selected'],
            ],
        ]);

    $questions = $response->json('questions');

    foreach ($questions as $q) {
        expect($q['question'])->toBeString()->not->toBeEmpty();
        expect($q['selected'])->toBeFalse();
    }

    QuestionGenerationAgent::assertPrompted($promptQuestion->question);
});

it('normalizes malformed AI responses returning JSON objects instead of strings', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create([
        'parent_id' => $parent->id,
        'username' => fake()->userName(),
        'grade' => 5,
        'age' => 10,
    ]);

    Prompt::create(['category' => 'questions', 'prompt' => 'Generate related questions.']);

    $promptQuestion = PromptQuestion::withoutEvents(function () use ($student) {
        return PromptQuestion::factory()->create([
            'user_id' => $student->id,
        ]);
    });

    QuestionGenerationAgent::fake([
        [
            'questions' => [
                ['question' => 'What causes rain to fall from clouds?', 'selected' => false],
                ['question' => 'How do rivers form and where do they go?', 'selected' => false],
                '{"question": "Why is the ocean salty?", "selected": false}',
                'What is evaporation?',
            ],
        ],
    ]);

    $response = $this->actingAs($student)
        ->postJson('/student/prompts/questions', [
            'question' => $promptQuestion->question,
        ]);

    $response->assertSuccessful()
        ->assertJsonCount(4, 'questions')
        ->assertJsonStructure([
            'questions' => [
                '*' => ['question', 'selected'],
            ],
        ]);

    $questions = $response->json('questions');

    expect($questions[0]['question'])->toBe('What causes rain to fall from clouds?');
    expect($questions[1]['question'])->toBe('How do rivers form and where do they go?');
    expect($questions[2]['question'])->toBe('Why is the ocean salty?');
    expect($questions[3]['question'])->toBe('What is evaporation?');

    foreach ($questions as $q) {
        expect($q['selected'])->toBeFalse();
    }
});

it('returns empty questions when no prompt question exists', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create([
        'parent_id' => $parent->id,
        'username' => fake()->userName(),
        'grade' => 5,
        'age' => 10,
    ]);

    $response = $this->actingAs($student)
        ->postJson('/student/prompts/questions', [
            'question' => 'What is the water cycle?',
        ]);

    $response->assertSuccessful()
        ->assertJsonCount(0, 'questions');
});
