<?php

use Kolette\Auth\Models\User;
use App\Models\Refund;

return [
    'resource_map' => [
        //
    ],
    'morph_map' => [
        User::class => 'users',
        Refund::class => 'refunds'
    ],
];
