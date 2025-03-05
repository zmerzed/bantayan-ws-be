<?php

namespace App\Observers;

use App\Enums\OrderStatus;
use App\Models\OrderStatusHistory;
use App\Notifications\Customer\OrderPickup;
use App\Notifications\Customer\OrderProcessing;
use App\Notifications\Customer\OrderReadyForPickup;
use App\Notifications\Merchant\NewOrderReceived;
use App\Notifications\Merchant\OrderReceived;

class OrderStatusHistoryObserver
{
    /**
     * Handle the OrderStatusHistory "created" event.
     */
    public function created(OrderStatusHistory $orderStatusHistory): void
    {
        /** \Kolette\Marketplace\Models\Order $order */
        $order = $orderStatusHistory->order;

        if ($orderStatusHistory->status === OrderStatus::PROCESSING) {
            $order->customer->notify(new OrderProcessing($order));
        } else if ($orderStatusHistory->status === OrderStatus::READY_FOR_PICKUP) {
            $order->customer->notify(new OrderReadyForPickup($order));
        } else if ($orderStatusHistory->status === OrderStatus::CONFIRMED_PICKUP) {
            $order->customer->notify(new OrderPickup($order));
        }
    }

    /**
     * Handle the OrderStatusHistory "updated" event.
     */
    public function updated(OrderStatusHistory $orderStatusHistory): void
    {
        //
    }

    /**
     * Handle the OrderStatusHistory "deleted" event.
     */
    public function deleted(OrderStatusHistory $orderStatusHistory): void
    {
        //
    }

    /**
     * Handle the OrderStatusHistory "restored" event.
     */
    public function restored(OrderStatusHistory $orderStatusHistory): void
    {
        //
    }

    /**
     * Handle the OrderStatusHistory "force deleted" event.
     */
    public function forceDeleted(OrderStatusHistory $orderStatusHistory): void
    {
        //
    }
}
