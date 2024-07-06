<?php

namespace Media101\Bird\Exceptions;

use Exception;
use Media101\Bird\Supports\Notifications\SMS\SMSMessage;

class NotAnSmsMessageException extends Exception
{
    protected $message = 'The provided sms message is not a valid sms message. Expected `' . SMSMessage::class . '`';
}
