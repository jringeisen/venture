<?php

namespace App\Http\Controllers\Student\Prompts;

use App\Ai\Agents\ContentStreamingAgent;
use App\Http\Controllers\Controller;
use App\Models\Prompt;
use Illuminate\Http\Request;
use Laravel\Ai\Responses\StreamedAgentResponse;
use Throwable;

class GetContentController extends Controller
{
    /**
     * @throws Throwable
     */
    public function __invoke(Request $request)
    {
        $usersAge = $request->user()->age;

        $prompt = Prompt::where('category', 'like', "%$usersAge%")->first()->prompt;

        $question = $request->user()->promptQuestions()->latest()->first();

        throw_unless($question, "No prompt question exists for the given user: {$request->user()->id}");

        return (new ContentStreamingAgent($prompt, $question))
            ->stream($question->question)
            ->then(function (StreamedAgentResponse $response) use ($question) {
                $question->promptAnswer()
                    ->updateOrCreate(
                        ['prompt_question_id' => $question->id],
                        ['content' => $response->text, 'word_count' => str_word_count($response->text)]
                    );
            });
    }
}
