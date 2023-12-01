<?php

namespace App\Http\Controllers\Student\Prompts;

use App\Http\Controllers\Controller;
use App\Models\Prompt;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GetContentController extends Controller
{
    public function __invoke(Request $request)
    {
        return new StreamedResponse(function () use ($request) {
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('X-Accel-Buffering: no');

            $content = str_replace('[AGE]', $request->user()->age, Prompt::where('category', 'tone')->first()->prompt);
            $content = str_replace('[GRADE]', $request->user()->grade, $content);

            $question = $request->user()->promptQuestions()->latest()->first();

            $stream = OpenAI::chat()->createStreamed([
                'model' => 'gpt-3.5-turbo-1106',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $content,
                    ],
                    [
                        'role' => 'user',
                        'content' => $question->question,
                    ],
                ],
                'user' => 'user-'.$request->user()->id,
            ]);

            $message = '';

            foreach ($stream as $response) {
                $data = $response->choices[0]->toArray();

                if ($data['finish_reason'] === null) {
                    $message .= $data['delta']['content'];
                }

                if ($data['finish_reason'] === 'stop') {
                    $question->promptAnswer()->updateOrCreate([
                        'prompt_question_id' => $question->id,
                    ], [
                        'content' => $message,
                    ]);
                }

                echo 'data: '.json_encode($data)."\n\n";
                ob_flush();
                flush();

                sleep(0.5);
            }
        });
    }
}
