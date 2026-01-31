<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Subscription Plans
    |--------------------------------------------------------------------------
    |
    | Define each subscription tier with its Stripe price IDs and feature limits.
    | A limit of -1 means unlimited.
    |
    */

    'plans' => [
        'free' => [
            'stripe_monthly_price' => null,
            'stripe_yearly_price' => null,
            'limits' => [
                'ai_questions_per_day' => 5,
                'active_courses_per_student' => 1,
                'max_students' => 3,
                'compliance_reports_per_month' => 0,
                'pdf_download' => false,
                'certificates' => false,
            ],
        ],

        'explorer' => [
            'stripe_monthly_price' => env('STRIPE_EXPLORER_MONTHLY_PRICE'),
            'stripe_yearly_price' => env('STRIPE_EXPLORER_YEARLY_PRICE'),
            'limits' => [
                'ai_questions_per_day' => 30,
                'active_courses_per_student' => 5,
                'max_students' => 5,
                'compliance_reports_per_month' => 2,
                'pdf_download' => true,
                'certificates' => false,
            ],
        ],

        'family' => [
            'stripe_monthly_price' => env('STRIPE_FAMILY_MONTHLY_PRICE'),
            'stripe_yearly_price' => env('STRIPE_FAMILY_YEARLY_PRICE'),
            'limits' => [
                'ai_questions_per_day' => -1,
                'active_courses_per_student' => -1,
                'max_students' => 10,
                'compliance_reports_per_month' => -1,
                'pdf_download' => true,
                'certificates' => true,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Pricing Display
    |--------------------------------------------------------------------------
    */

    'pricing' => [
        'explorer' => [
            'monthly' => 9.99,
            'yearly' => 99.00,
        ],
        'family' => [
            'monthly' => 19.99,
            'yearly' => 199.00,
        ],
    ],

];
