<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionCheckoutRequest;
use App\Http\Requests\SubscriptionSwapRequest;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionController extends Controller
{
    public function __construct(
        private readonly SubscriptionService $subscriptionService
    ) {}

    /**
     * Parent subscription management page.
     */
    public function index(Request $request): Response
    {
        $summary = $this->subscriptionService->getSubscriptionSummary($request->user());

        return Inertia::render('Teachers/Subscription/Index', [
            'subscription' => $summary,
            'pricing' => config('subscription.pricing'),
            'plans' => config('subscription.plans'),
        ]);
    }

    /**
     * Create a Stripe Checkout session for a new subscription.
     */
    public function checkout(SubscriptionCheckoutRequest $request)
    {
        $plan = $request->validated('plan');
        $billingCycle = $request->validated('billing_cycle');

        $priceKey = $billingCycle === 'yearly' ? 'stripe_yearly_price' : 'stripe_monthly_price';
        $priceId = config("subscription.plans.{$plan}.{$priceKey}");

        if (! $priceId) {
            return back()->withErrors(['plan' => 'Invalid plan or billing cycle.']);
        }

        return $request->user()
            ->newSubscription('default', $priceId)
            ->checkout([
                'success_url' => route('parent.subscription.success').'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('parent.subscription.index'),
            ]);
    }

    /**
     * Post-checkout confirmation page.
     */
    public function success(Request $request): Response
    {
        $summary = $this->subscriptionService->getSubscriptionSummary($request->user());

        return Inertia::render('Teachers/Subscription/Success', [
            'subscription' => $summary,
        ]);
    }

    /**
     * Redirect to Stripe Customer Portal for billing management.
     */
    public function billingPortal(Request $request): RedirectResponse
    {
        return $request->user()->redirectToBillingPortal(route('parent.subscription.index'));
    }

    /**
     * Swap between subscription plans or billing cycles.
     */
    public function swap(SubscriptionSwapRequest $request): RedirectResponse
    {
        $plan = $request->validated('plan');
        $billingCycle = $request->validated('billing_cycle');

        $priceKey = $billingCycle === 'yearly' ? 'stripe_yearly_price' : 'stripe_monthly_price';
        $priceId = config("subscription.plans.{$plan}.{$priceKey}");

        if (! $priceId) {
            return back()->withErrors(['plan' => 'Invalid plan or billing cycle.']);
        }

        $request->user()->subscription('default')->swap($priceId);

        return to_route('parent.subscription.index')
            ->with('status', 'success')
            ->with('title', 'Plan Updated')
            ->with('body', 'Your subscription has been updated successfully.');
    }
}
