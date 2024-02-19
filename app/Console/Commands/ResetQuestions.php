<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetQuestions extends Command
{
    protected $signature = 'venture:reset-questions';

    protected $description = 'This command resets the total questions asked column each month, giving the user 20 free questions to ask each month.';

    public function handle()
    {
        User::whereNull('parent_id')->chunk(100, function ($users) {
            foreach ($users as $user) {
                $startOfMonth = now($user->timezone)->startOfMonth();

                if ($startOfMonth->isToday()) {
                    $user->update([
                        'total_questions_asked' => 0,
                    ]);
                }
            }
        });
    }
}
