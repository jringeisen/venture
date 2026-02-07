<?php

namespace App\Http\Controllers\Student\Prompts;

use App\Ai\Agents\SubjectCategorizationAgent;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetSubjectController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $question = $request->user()->promptQuestions()->latest()->first();

        $response = SubjectCategorizationAgent::make()->prompt($request->question);

        $question->promptAnswer()
            ->updateOrCreate(
                ['prompt_question_id' => $question->id],
                ['subject_category' => strtolower($response['subject'])]
            );

        return response()->json([
            'subject' => $response['subject'],
            'subCategory' => $response['sub_category'] ?? null,
        ]);
    }
}
