<?php

namespace App\Enums;

enum SubscriptionPlan: string
{
    case Free = 'free';
    case Explorer = 'explorer';
    case Family = 'family';

    public function label(): string
    {
        return match ($this) {
            self::Free => 'Starter',
            self::Explorer => 'Explorer',
            self::Family => 'Family',
        };
    }

    /**
     * @return int|bool The limit value for the given feature key.
     */
    public function limit(string $key): int|bool
    {
        return config("subscription.plans.{$this->value}.limits.{$key}");
    }

    /**
     * Check if a feature is unlimited (-1) for this plan.
     */
    public function isUnlimited(string $key): bool
    {
        return $this->limit($key) === -1;
    }

    /**
     * Resolve a plan from a Stripe price ID.
     */
    public static function fromStripePrice(?string $priceId): self
    {
        if (! $priceId) {
            return self::Free;
        }

        foreach (config('subscription.plans') as $slug => $plan) {
            if ($plan['stripe_monthly_price'] === $priceId || $plan['stripe_yearly_price'] === $priceId) {
                return self::from($slug);
            }
        }

        return self::Free;
    }
}
