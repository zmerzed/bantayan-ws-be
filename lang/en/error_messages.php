<?php

return [
    'order' => [
        'owner' => 'You don\'t have the right to view/update this resource.',
        'processing' => 'Unable to complete action. Order is now getting processed.',
        'cancelled' => 'Unable to complete action. Order is already been cancelled.',
        'completed' => 'Unable to complete action. Order is already completed.',
        'not_completed' => 'Unable to complete action. Order is not yet completed.',
        'refund' => [
            'not_completed' => 'Unable to complete action. Order not yet picked-up',
            'exists' => 'Unable to complete action. Refund requested already'
        ],
        'detail' => [
            'owner' => 'Unable to complete action. You\'re not the owner of the item'
        ]
    ],
    'checkout' => [
        'successful' => 'Checkout successful',
        'failed' => [
            'payment' => 'Unable to checkout. One of your orders got declined or has failed payment.'
        ]
    ]
];
