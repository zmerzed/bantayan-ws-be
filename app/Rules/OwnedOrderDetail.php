<?php

namespace App\Rules;

use App\Enums\OrderDetailStatus;
use App\Enums\OrderStatus;
use App\Models\OrderDetail;
use Kolette\Auth\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class OwnedOrderDetail implements ValidationRule
{
    public function __construct(public User $user)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $orderDetails = OrderDetail::find($value);

        if ($orderDetails->order->status != OrderStatus::CONFIRMED_PICKUP) {
            $fail(__('error_messages.order.refund.not_completed'));
        }

        if ($orderDetails->buyer_id != $this->user->id) {
            $fail(__('error_messages.order.detail.owner'));
        }

        if ($orderDetails->status != OrderDetailStatus::ACTIVE || $orderDetails->refunds->count()) {
            $fail(__('error_messages.order.refund.exists'));
        }
    }
}
