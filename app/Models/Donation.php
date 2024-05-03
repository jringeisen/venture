<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

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
}
