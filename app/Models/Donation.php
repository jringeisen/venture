<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

class Donation extends Model
{
    use HasFactory;

    public static $TOTAL_GOAL = 5500;

    protected $fillable = [
        'email',
        'name',
        'amount',
        'payment_link',
    ];

    protected $casts = [
        'email' => 'string',
        'name' => 'string',
        'amount' => 'integer',
        'payment_link' => 'string',
    ];

    public static function totalGoalProgress(): array
    {
        $sum = (int) Donation::sum('amount');

        return [
            'amount' => Number::currency($sum / 100),
            'progress' => Number::percentage((($sum / 100) / self::$TOTAL_GOAL) * 100),
        ];
    }
}
