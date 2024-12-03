<?php

namespace Media101\Bird\Models\Messages;

use Media101\Bird\Contracts\HasBirdMessage;
use Media101\Bird\Enums\ChannelType;
use Media101\Bird\Enums\IdentifierKey;
use Media101\Bird\Enums\MessageType;
use Media101\Bird\Models\Contact;

abstract class Message
{
    use HasBirdMessage;

    public ChannelType $viaChannel;
    public MessageType $messageType;
    public array $contacts = [];
    public array $actions = [];
    public string $text;

    public function text(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function addContact(Contact $contact, IdentifierKey $identifierKey): static
    {
        $this->contacts[] = [
            'displayName'     => $contact->getDisplayName(),
            'identifierKey'   => $identifierKey->value,
            'identifierValue' => $identifierKey === IdentifierKey::PHONE_NUMBER
                ? $contact->getPhoneNumber()
                : $contact->getEmailAddress(),
        ];

        return $this;
    }

    public function addAction(string $action, array $parameters = []): static
    {
        $this->actions[] = [
            'action' => $action,
            'parameters' => $parameters,
        ];

        return $this;
    }

    public function toArray(): array
    {
        return [
            'body' => [
                'type' => $this->messageType->value,
                $this->messageType->value => [
                    'text' => $this->text,
                ],
            ],
            'receiver' => [
                'contacts' => $this->contacts
            ]
        ];
    }
}
