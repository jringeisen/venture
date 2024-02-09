<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function getTotalMinutesAttribute(): int
    {
        return floor($this->total_seconds / 60);
    }
}
