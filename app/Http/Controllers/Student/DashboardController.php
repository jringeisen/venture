<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use OpenAI\Laravel\Facades\OpenAI;

class DashboardController extends Controller
{
    public function index(Request $request, StudentService $studentService)
    {
        return Inertia::render('Student/Dashboard', [
            'totalQuestions' => $studentService->student($request->user())->totalQuestionsAsked(),
            'dailyQuestions' => $studentService->student($request->user())->totalQuestionsAskedToday(),
            'categoriesWithCounts' => $studentService->student($request->user())->categoriesWithCounts(),
            'pieChartData' => $studentService->student($request->user())->pieChartData(),
            'motivationalMessage' => $this->generateMotivationalMessage($request),
        ]);
    }

    protected function generateMotivationalMessage(Request $request)
    {
        $user = $request->user();

        if ($user->motivational_message && (is_null($user->motivational_message) || $user->motivational_message->isYesterday())) {
            return $this->callOpenAI($user);
        }

        return null;
    }

    protected function callOpenAI(Student $user)
    {
        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo-1106',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "Using the tone of {$this->getRandomPerson()}, generate a motivational quote for a child between the ages of 11 and 15.",
                ],
            ],
            'user' => 'user-'.$user->id,
        ]);

        return $response->choices[0]->message->content;
    }

    protected function getRandomPerson(): string
    {
        return collect([
            'Neil Degrasse Tyson',
            'Joe Rogan',
            'Elon Musk',
            'Andy Frisella',
            'David Goggins',
            'Jocko Willink',
        ])->random();
    }
}
