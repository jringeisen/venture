<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveTime extends Model
{
    use HasFactory;

    protected $table = 'active_time';

    protected $fillable = [
        'total_seconds',
        'user_id',
        'date'
    ];
}
