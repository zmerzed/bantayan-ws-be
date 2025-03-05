<?php

namespace Kolette\Auth\Services;

use Exception;
use Kolette\Auth\Services\Stripe;

/**
 * @property Customer|null $stripeCustomer
 */
trait IsStripeCustomer
{  
    /**
     * Define a polymorphic one-to-one relationship.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */ 
    public function stripeCustomer()
    {
        return $this->morphOne(Customer::class, 'model')->latestOfMany();
    }  
    
    /**
     * Get the email address used to create the customer in Stripe.
     *
     * @return string|null
     */
    public function stripeEmail()
    {
        return $this->email;
    }

    /**
     * Get the stripe id of the model.
     */
    public function stripeId() : ?string
    {
        return optional($this->stripeCustomer)->id;
    }

    /**
     * Checki if model has stripe id
     * 
     * @return bool
     */
    public function hasStripeId()
    {
        return $this->stripeCustomer != null;
    }
    
    /**
     * Create a Stripe customer for the given model.
     *
     * @param  array  $options
     * @return \Stripe\Customer
     *
     * @throws \Laravel\Cashier\Exceptions\CustomerAlreadyCreated
     */
    public function createAsStripeCustomer(array $options = [])
    {
        if ($this->hasStripeId()) {
            throw new Exception('Customer already created.');
        }

        if (! array_key_exists('email', $options) && $email = $this->stripeEmail()) {
            $options['email'] = $email;
        }
  
        $customer = $this->stripeClient()->customers->create($options); 

        $this->stripeCustomer()->create([
            'id' => $customer->id,
            'data' => $customer->toArray()
        ]);

        return $customer;
    }

    /**
     * Get the Stripe customer for the model.
     *
     * @return \Stripe\Customer
     */
    public function asStripeCustomer()
    {
        $this->assertCustomerExists();
         
        $customer = $this->stripeClient()->customers->retrieve($this->stripeId());  

        $this->stripeCustomer()->update([ 
            'data' => $customer->toArray()
        ]);

        return $customer;
    }
 
    /**
     * Get the Stripe customer instance for the current user or create one.
     *
     * @param  array  $options
     * @return \Stripe\Customer
     */
    public function createOrGetStripeCustomer(array $options = [])
    {
        if ($this->hasStripeId()) {
            return $this->asStripeCustomer();
        }

        return $this->createAsStripeCustomer($options);
    }

    /**
     * Determine if the customer has a Stripe customer ID and throw an exception if not.
     *
     * @return void 
     */
    protected function assertCustomerExists()
    {
        if (! $this->hasStripeId()) {
            throw new Exception('Customer does not exist.');
        }
    }

    /**
     * Get the Stripe supported currency used by the customer.
     *
     * @return string
     */
    public function preferredCurrency()
    {
        return config('services.stripe.currency');
    }

    /**
     * Get a new instance of stripe client.
     *
     * @return StripeClient
     */
    public function stripeClient(array $options = [])
    {
        return Stripe::client($options);
    } 

    /**
     * Creates EphemeralKey
     *
     * @return void
     */
    public function createEphemeralKey()
    { 
        $stripeId = $this->createOrGetStripeCustomer()->id; 
 
        return \Stripe\EphemeralKey::create(['customer' => $stripeId], Stripe::defaultOptions()); 
    } 
}