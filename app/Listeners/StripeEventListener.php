<?php

namespace App\Listeners;

use App\Models\Donation;

class StripeEventListener
{
    public function handle(object $event): void
    {
        if ($event->payload['type'] === 'checkout.session.completed') {
            $object = $event->payload['data']['object'];

            \Illuminate\Support\Facades\Log::info($object);

            if (isset($object['payment_status']) && $object['payment_status'] === 'paid') {
                Donation::create([
                    'name' => $object['customer_details']['name'],
                    'email' => $object['customer_details']['email'],
                    'amount' => $object['amount_total'],
                    'payment_link' => $object['payment_link'],
                ]);
            }
        }
    }
}
