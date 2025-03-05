<?php

namespace Kolette\Auth\Http\Controllers\V1\Customer\Payment;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Kolette\Auth\Services\Stripe;
use Kolette\Auth\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class CardController extends Controller
{

    public function list(Request $request)
    {
        /** @var \Kolette\Auth\Models\User */
        $user = auth()->user();

        $collection = $user->listStripeCards();

        return JsonResource::collection($collection->data);
    }

    public function attach(Request $request)
    {
        $request->validate([
            'source_id' => 'required',
        ]);

        $card = auth()->user()->createStripeCardFromToken($request->source_id);

        return JsonResource::make($card);
    }

    public function detach(Request $request)
    {
        $request->validate([
            'source_id' => 'required',
        ]);

        $card = auth()->user()->deleteStripeCard($request->source_id);

        return JsonResource::make($card);
    }

    public function default(Request $request)
    {
        $request->validate([
            'source_id' => 'required',
        ]);

        auth()->user()->makeStripeCardDefault($request->source_id);

        return response()->noContent();
    }

}
