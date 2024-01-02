<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use Billable, HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'timezone',
        'total_questions_asked',
        'viewed_starter_guide',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'total_questions_asked' => 'integer',
        'viewed_starter_guide' => 'boolean',
    ];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function promptQuestions(): HasManyThrough
    {
        return $this->hasManyThrough(PromptQuestion::class, Student::class);
    }

    protected function timezone(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ?? config('app.timezone')
        );
    }

    public function subscriptionQuantity(): int
    {
        $subscriptionItem = $this->subscription('default')->items->first();

        return $subscriptionItem->quantity;
    }

    public function showInitialPaymentPage(): bool
    {
        return ! $this->subscribed() && $this->students->count() >= 1;
    }

    public function showExceededQuantityPage(): bool
    {
        return $this->subscribed() && $this->students->count() >= $this->subscriptionQuantity();
    }
}
