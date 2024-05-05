<?php

use Illuminate\Support\Number;

return [
    'cause_one' => [
        'title' => 'Support Backend Course Curriculum Development',
        'description' => "Your contribution helps us build the backbone of future courses. With your support, we'll develop the backend infrastructure necessary to deliver engaging and effective online learning experiences. Join us in shaping the educational landscape for tomorrow's learners.",
        'image' => 'https://images.pexels.com/photos/546819/pexels-photo-546819.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
        'goal' => Number::currency(45000),
        'goalValue' => 45000,
        'paymentLink' => 'https://buy.stripe.com/test_aEU6sh65M2R924w005',
        'plink' => 'plink_1PCrK8Aolv7LEvcOHKRTpWbF',
    ],
    'cause_two' => [
        'title' => 'A $50 Donation Supports a Year of Learning for a Student',
        'description' => "A $50 donation funds a student's learning journey for an entire year. Your generosity unlocks boundless opportunities for them on Venture, providing unlimited access to learning resources and educational support. Every donation makes a lasting impact on their educational success.",
        'image' => 'https://images.pexels.com/photos/9302788/pexels-photo-9302788.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
        'goal' => Number::currency(500000),
        'goalValue' => 500000,
        'paymentLink' => 'https://buy.stripe.com/test_00g6sh51IfDVcJaeV0',
        'plink' => 'plink_1PCrMeAolv7LEvcOvWehivgb',
    ],
    'cause_three' => [
        'title' => 'Breaking Language Barriers: Enable Global Access',
        'description' => "Your contribution drives our efforts to expand language support on our platform. With your support, we'll enhance the codebase to ensure personalized learning experiences in every language. Let's make education accessible to all, regardless of language barriers.",
        'image' => 'https://images.pexels.com/photos/87651/earth-blue-planet-globe-planet-87651.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
        'goal' => Number::currency(30000),
        'goalValue' => 30000,
        'paymentLink' => 'https://buy.stripe.com/test_00g6sh79QezR4cE28f',
        'plink' => 'plink_1PCrOSAolv7LEvcO8zEFZdLd',
    ],
];
