<?php

namespace Media101\Bird\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Media101\Bird\Services\Notifications\SMS\SMSMessage;

class NotificationNotSent extends Exception
{
    public static function notificationType($notification, $status, $errorMessage): self
    {
        Log::error($errorMessage);

        return new self("Could not send `$notification` notification. Returned with status `$status`.");
    }
}
