<?php

namespace App\Http\Controllers\Student\Prompts;

use App\Ai\Agents\QuestionGenerationAgent;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetQuestionsController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $question = $request->user()->promptQuestions()->latest()->first();

        if ($question) {
            $response = QuestionGenerationAgent::make()->prompt($request->question);

            $question->promptAnswer()
                ->updateOrCreate(
                    ['prompt_question_id' => $question->id],
                    ['questions' => $response['questions'] ?? null]
                );

            $questions = collect($response['questions'] ?? [])
                ->map(function ($q) {
                    if (is_array($q)) {
                        return ['question' => $q['question'] ?? (string) ($q[0] ?? ''), 'selected' => false];
                    }

                    $decoded = json_decode($q, true);
                    if (is_array($decoded) && isset($decoded['question'])) {
                        return ['question' => $decoded['question'], 'selected' => false];
                    }

                    return ['question' => (string) $q, 'selected' => false];
                })
                ->filter(fn ($q) => $q['question'] !== '')
                ->values()
                ->all();

            return response()->json([
                'questions' => $questions,
            ]);
        }

        return response()->json(['questions' => []]);
    }
}
