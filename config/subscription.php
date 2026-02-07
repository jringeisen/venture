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
                'ai_questions_per_hour' => 10,
                'active_courses_per_student' => 1,
                'max_students' => 1,
                'compliance_reports_per_month' => 0,
                'pdf_download' => false,
                'certificates' => false,
            ],
        ],

        'family' => [
            'stripe_monthly_price' => env('STRIPE_FAMILY_MONTHLY_PRICE'),
            'stripe_yearly_price' => env('STRIPE_FAMILY_YEARLY_PRICE'),
            'limits' => [
                'ai_questions_per_hour' => -1,
                'active_courses_per_student' => -1,
                'max_students' => 5,
                'compliance_reports_per_month' => -1,
                'pdf_download' => true,
                'certificates' => true,
            ],
        ],

        'classroom' => [
            'stripe_monthly_price' => env('STRIPE_CLASSROOM_MONTHLY_PRICE'),
            'stripe_yearly_price' => env('STRIPE_CLASSROOM_YEARLY_PRICE'),
            'limits' => [
                'ai_questions_per_hour' => -1,
                'active_courses_per_student' => -1,
                'max_students' => 25,
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
        'family' => [
            'monthly' => 19.99,
            'yearly' => 199.00,
        ],
        'classroom' => [
            'monthly' => 59.00,
            'yearly' => 588.00,
        ],
    ],

];
