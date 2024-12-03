<?php

namespace Media101\_src\Exceptions;

use Exception;
use Media101\_src\Services\Notifications\SMS\SMSMessage;

class NotAnSmsMessageException extends Exception
{
    protected $message = 'The provided sms message is not a valid sms message. Expected `' . SMSMessage::class . '`';
}
