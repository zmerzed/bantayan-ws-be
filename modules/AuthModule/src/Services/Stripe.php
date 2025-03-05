<?php

namespace Kolette\Auth\Services;

use Stripe\BaseStripeClient;
use Stripe\Stripe as BaseStripe;
use Stripe\StripeClient;

class Stripe extends BaseStripe
{
    /**
     * The Stripe API version.
     *
     * @var string
     */
    const STRIPE_VERSION = '2020-08-27';

    /**
     * The base URL for the Stripe API.
     *
     * @var string
     */
    const STRIPE_BASE_URL = BaseStripeClient::DEFAULT_API_BASE;

    /**
     * Get a new instance of stripe client.
     *
     * @return StripeClient
     */
    public static function client(array $options = []): StripeClient
    {
        return new StripeClient(array_merge(static::defaultOptions(), $options));
    }

    /**
     * Get the default options for the Stripe API.
     *
     * @return array
     */
    public static function defaultOptions(): array
    {
        return [
            'api_key'        => config('services.stripe.secret'),
            'stripe_version' => static::STRIPE_VERSION,
            'api_base'       => static::STRIPE_BASE_URL,
        ];
    }
}
