<?php

namespace Media101\Bird\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Media101\Bird\Concerns\IsNotificationChannel;
use Media101\Bird\Contracts\BirdConnection;
use Media101\Bird\Exceptions\NotificationNotSent;

class EmailChannel implements IsNotificationChannel
{
    use BirdConnection;

    public function channelEndpoint(): string
    {
        return '';
    }

    public function getMessage(Notification $notification)
    {
        if (! method_exists($notification, 'toBirdEmail')) {
            throw new \InvalidArgumentException('Notification does not implement toBirdEmail method');
        }

        return $notification->toBirdEmail($notification);
    }

    public function send(mixed $notifiable, Notification $notification): void
    {
        $message = $this->getMessage($notification);

        $response = $this->birdPostRequest($this->channelEndpoint(), $message->toArray());

        if (! $response->accepted()) {
            throw NotificationNotSent::notificationType(
                notification: get_class($notification),
                status: $response->status(),
                errorMessage: $response->json()
            );
        }
    }
}
