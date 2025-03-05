<?php

return [
    'modules' => [
        /**
         * Example:
         * VendorA\ModuleX\Providers\ModuleServiceProvider::class,
         * VendorB\ModuleY\Providers\ModuleServiceProvider::class
         *
         */
        Kolette\Auth\Providers\ModuleServiceProvider::class,
        Kolette\Media\Providers\ModuleServiceProvider::class,
        Kolette\Category\Providers\ModuleServiceProvider::class,
        Kolette\Reporting\Providers\ModuleServiceProvider::class,
    ],
    'register_route_models' => true
];
