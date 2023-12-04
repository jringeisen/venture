<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PromptAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_category',
        'content',
        'questions',
        'outline',
    ];

    public function promptQuestion()
    {
        return $this->belongsTo(PromptQuestion::class);
    }

    public static function getPieChartData()
    {
        return self::select('subject_category', DB::raw('count(*) as total'))
            ->whereNotNull('subject_category')
            ->groupBy('subject_category')
            ->get()
            ->map(function ($item) {
                $item->subject_category = ucfirst($item->subject_category);

                return $item;
            })
            ->values();
    }
}
