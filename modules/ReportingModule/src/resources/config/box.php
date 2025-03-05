<?php

use Kolette\Auth\Models\User;
use App\Models\Refund;

return [
    'modules' => [

    ],
    'routes' => [
        'files' => ['api'],
        'prefix' => 'api/v1',
        'middleware' => ['api'],
    ],
    'views' => [
        'namespace' => 'reporting',
    ],
    'reportables' => [
        Refund::class,
        User::class,
    ],
];
