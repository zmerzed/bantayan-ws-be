<?php

namespace App\Notifications\Customer;

use App\Enums\OrderStatusMessage;
use App\Enums\OrderStatusNotifications;
use App\Enums\OrderStatusTitle;
use App\Notifications\Traits\InteractsWithFCM;
use Kolette\Marketplace\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OrderPickup extends Notification implements ShouldQueue
{
    use Queueable;
    use InteractsWithFCM;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Order $order)
    {
        $this->title = OrderStatusTitle::completed;
        $this->description = OrderStatusMessage::completed;
        $this->type = OrderStatusNotifications::CONFIRM_PICKUP_TYPE;
        $this->orderId = $this->order->id;
        $this->actor = $this->order->seller;

        $this->data = [
            'order_id' => $this->order->id
        ];
    }
}
