<?php

namespace Media101\Bird\Messages;

use Media101\Bird\Concerns\IsBirdMessage;
use Media101\Bird\Enums\ChannelType;
use Media101\Bird\Enums\IdentifierKey;
use Media101\Bird\Enums\MessageType;
use Media101\Bird\Models\Contact;

class SMSMessage extends Message implements IsBirdMessage
{
    public ChannelType $viaChannel = ChannelType::SMS;
    public MessageType $messageType = MessageType::TEXT;

    public function toContact(Contact $contact): static
    {
        $this->addContact($contact, IdentifierKey::PHONE_NUMBER);

        return $this;
    }
}
