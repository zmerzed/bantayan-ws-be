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

class OrderProcessing extends Notification implements ShouldQueue
{
    use Queueable;
    use InteractsWithFCM;

    /**
 * Create a new notification instance.
 */
    public function __construct(public Order $order)
    {
        $this->title = OrderStatusTitle::processing;
        $this->description = OrderStatusMessage::processing;
        $this->type = OrderStatusNotifications::START_PROCESSING_TYPE;
        $this->orderId = $this->order->id;
        $this->actor = $this->order->seller;

        $this->data = [
            'order_id' => $this->order->id
        ];
    }
}
