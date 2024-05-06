<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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

    public static function totalSum(): int
    {
        return Donation::sum('amount');
    }

    public static function sumAmountByPaymentLink(): Collection
    {
        return Donation::selectRaw('SUM(amount) as amount, payment_link')->groupBy('payment_link')->get();
    }
}
