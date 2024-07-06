<?php

namespace Media101\Bird\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Media101\Bird\Supports\Notifications\SMS\SMSMessage;

class NotificationNotSent extends Exception
{
    protected $message = 'The provided sms message is not a valid sms message. Expected: ' . SMSMessage::class;

    public static function notificationType($notification, $status, $errorMessage): self
    {
        Log::error($errorMessage);

        return new self("Could not send `$notification` notification. Returned with status `$status`.");
    }
}
