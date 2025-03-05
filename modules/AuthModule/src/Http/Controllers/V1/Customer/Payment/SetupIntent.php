<?php

namespace Kolette\Auth\Http\Controllers\V1\Customer\Payment;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Kolette\Auth\Services\Stripe;
use Kolette\Auth\Http\Controllers\Controller;

class SetupIntent extends Controller
{

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $stripe = Stripe::client();

        $setupIntent = $stripe->setupIntents->create([
            'payment_method_types' => ['card'],
        ]);

        return response()->json([
            'data' => Arr::only($setupIntent->toArray(), ['id', 'client_secret'])
        ]);
    }
}
