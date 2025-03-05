<?php

namespace App\Observers;

use App\Actions\Checkout\PointsTransaction;
use App\Actions\Checkout\RefundOrder;
use App\Enums\OrderDetailStatus;
use App\Enums\OrderStatus;
use App\Models\OrderDetail;
use Kolette\Marketplace\Models\Order;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        if ($order->status == OrderStatus::CANCELLED) {
            Log::error('error', ['ordeder' => $order]);
            $order->orderDetails()->where('status', OrderDetailStatus::ACTIVE)
                ->get()->each(
                    fn(OrderDetail $detail) => (new RefundOrder)($detail)
                );
        } else if ($order->status === OrderStatus::CONFIRMED_PICKUP) {
            (new PointsTransaction)($order);
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
