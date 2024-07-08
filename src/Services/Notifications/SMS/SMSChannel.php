<?php

namespace Media101\Bird\Services\Notifications\SMS;


use Illuminate\Http\Client\ConnectionException;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Media101\Bird\Abstracts\Bird;
use Media101\Bird\Exceptions\InvalidParameterException;
use Media101\Bird\Exceptions\NotAnSmsMessageException;
use Media101\Bird\Exceptions\NotificationNotSent;

class SMSChannel extends Bird
{
    /**
     * Send the given SMS notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return void
     * @throws NotAnSmsMessageException
     * @throws NotificationNotSent
     * @throws InvalidParameterException|ConnectionException
     */
    public function send(mixed $notifiable, Notification $notification): void
    {
        /** @var SMSMessage $message */
        $message = $notification->toSMS($notifiable);

        if (! $this->isSmsMessage($message)) {
            throw new NotAnSmsMessageException();
        }

        $http = Http::withHeaders(self::headers())->post($this->smsEndpoint(), $message->toArray());

        if (! $http->accepted()) {
            throw NotificationNotSent::notificationType(__CLASS__, $http->status(), $http->json());
        }
    }


    /**
     * Get the SMS endpoint URL.
     *
     * @return string
     * @throws InvalidParameterException
     */
    private function smsEndpoint(): string
    {
        $channelID = config('bird.channels.sms');

        if (! $channelID) {
            throw InvalidParameterException::configValueIsNotSet('bird.channels.sms');
        }

        return self::endpoint("channels/$channelID/messages");
    }


    /**
     * Check if the message is an instance of SMSMessage.
     *
     * @param mixed $message
     * @return bool
     */
    private function isSmsMessage(mixed $message): bool
    {
        return $message instanceof SMSMessage;
    }
}
