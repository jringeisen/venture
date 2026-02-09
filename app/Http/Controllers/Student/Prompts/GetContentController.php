<?php

namespace App\Http\Controllers\Student\Prompts;

use App\Ai\Agents\ContentStreamingAgent;
use App\Http\Controllers\Controller;
use App\Models\Prompt;
use Illuminate\Broadcasting\Channel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Ai\Responses\StreamableAgentResponse;

class GetContentController extends Controller
{
    /**
     * @throws \RuntimeException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();
        $usersAge = $user->age;

        $prompt = Prompt::where('category', 'like', "%$usersAge%")->first()->prompt;

        $question = $user->promptQuestions()->latest()->first();

        if (! $question) {
            throw new \RuntimeException("No prompt question exists for the given user: {$user->id}");
        }

        (new ContentStreamingAgent($prompt, $question))
            ->broadcastOnQueue(
                $question->question,
                new Channel("private-prompts.{$user->id}"),
            )
            ->then(function (StreamableAgentResponse $response) use ($question) {
                $question->promptAnswer()
                    ->updateOrCreate(
                        ['prompt_question_id' => $question->id],
                        ['content' => $response->text, 'word_count' => str_word_count($response->text)]
                    );
            });

        return response()->json(['status' => 'generating']);
    }
}
