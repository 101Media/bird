<?php

namespace Media101\Bird\Notifications\Channels;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Media101\Bird\Concerns\IsNotificationChannel;
use Media101\Bird\Contracts\BirdConnection;
use Media101\Bird\Exceptions\InvalidParameterException;
use Media101\Bird\Exceptions\NotificationNotSent;
use Media101\Bird\Models\Messages\SMSMessage;

class SMSChannel implements IsNotificationChannel
{
    use BirdConnection;

    /**
     * @throws InvalidParameterException
     */
    public function channelEndpoint(): string
    {
        $channelID = config('bird.channels.sms');

        if (! $channelID) {
            throw InvalidParameterException::configValueIsNotSet('bird.channels.sms');
        }

        return $this->endpoint("channels/$channelID/messages");
    }

    public function getMessage(Notification $notification)
    {
        if (! method_exists($notification, 'toSMS')) {
            throw new \InvalidArgumentException('Notification does not implement toSMS method');
        }

        return $notification->toSMS($notification);
    }

    /**
     * Send the notification
     *
     * @throws ConnectionException
     * @throws NotificationNotSent
     * @throws InvalidParameterException
     */
    public function send(mixed $notifiable, Notification $notification): void
    {
        /** @var SMSMessage $message */
        $message = $this->getMessage($notification);

        $response = $this->birdRequest($this->channelEndpoint(), $message->toArray());

        if (! $response->accepted()) {
            throw NotificationNotSent::notificationType(
                notification: get_class($notification),
                status: $response->status(),
                errorMessage: $response->json()
            );
        }
    }
}
