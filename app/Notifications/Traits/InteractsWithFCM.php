<?php

namespace App\Notifications\Traits;

use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\AndroidConfig;
use NotificationChannels\Fcm\Resources\AndroidFcmOptions;
use NotificationChannels\Fcm\Resources\AndroidNotification;
use NotificationChannels\Fcm\Resources\ApnsConfig;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;
use NotificationChannels\Fcm\Resources\ApnsFcmOptions;

trait InteractsWithFCM
{
    /** @var string $title */
    protected $title;
    /** @var string $type */
    protected $type;
    /** @var string $description */
    protected $description;
    /** @var string $image */
    protected $image;
    /** @var Kolette\Auth\Models\User */
    protected $actor;
    /** @var string $commentId */
    protected $commentId;
    /** @var string $orderId */
    protected $orderId;
    /** @var array */
    protected $data;

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [FcmChannel::class, 'database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return $this->data($notifiable);
    }

    private function data($notifiable)
    {
        /** @var \Carbon\Carbon */
        $timestamp = now();

        return [
            'id' => (string)$this->id,
            'type' => (string)$this->type,
            'message' => (string)$this->description,
            'actor_id' => (string)$this->actor->id,
            'actor_name' => (string)$this->actor->full_name,
            'actor_avatar' => (string)optional($this->actor->avatar)->getFullUrl(),
            'notifiable_id' => (string)$notifiable->id,
            'notifiable_name' => (string)$notifiable->full_name,
            'notifiable_avatar' => (string)optional($notifiable->avatar)->getFullUrl(),
            'comment_id' => (string)$this->commentId,
            'order_id' => (string)$this->orderId,
            'timestamp' => (string)$timestamp->toISOString()
        ];
    }

    public function toFcm($notifiable)
    {
        return FcmMessage::create()
            ->setData([
                'type' => $this->type,
                'payload' => json_encode($this->data($notifiable))
            ])
            ->setNotification(FcmNotification::create()
                ->setTitle($this->title)
                ->setBody($this->description))
            ->setAndroid(
                AndroidConfig::create()
                    ->setFcmOptions(AndroidFcmOptions::create()->setAnalyticsLabel('analytics'))
                    ->setNotification(AndroidNotification::create()->setColor('#0A0A0A'))
            )->setApns(
                ApnsConfig::create()
                    ->setFcmOptions(ApnsFcmOptions::create()->setAnalyticsLabel('analytics_ios'))
            );
    }
}
