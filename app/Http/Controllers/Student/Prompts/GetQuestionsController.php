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
                ->map(fn (string $q) => ['question' => $q, 'selected' => false])
                ->values()
                ->all();

            return response()->json([
                'questions' => $questions,
            ]);
        }

        return response()->json(['questions' => []]);
    }
}
