<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveTime extends Model
{
    use HasFactory;

    protected $table = 'active_time';

    protected $appends = ['total_minutes'];

    protected $fillable = [
        'total_seconds',
        'user_id',
        'date',
        'created_at',
        'updated_at',
    ];

    protected function getTotalMinutesAttribute(): int
    {
        return floor($this->total_seconds / 60);
    }
}
