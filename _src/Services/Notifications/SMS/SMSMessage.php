<?php

namespace Media101\_src\Services\Notifications\SMS;


use Media101\_src\Services\Contacts\BirdContact;
use Media101\_src\Services\Notifications\SMS\Utils\SMSType;

class SMSMessage
{
    private SMSType $type = SMSType::TEXT;
    private array $contacts;
    private string $message;


    public function type(SMSType $type): static
    {
        $this->type = $type;

        return $this;
    }


    public function to(BirdContact $person, string $identifiedBy = 'phonenumber'): static
    {
       $this->contacts[] = [
           'identifierKey' => $identifiedBy,
           'identifierValue' => $person->getPhone(),
       ];

        return $this;
    }


    public function text($message): static
    {
        $this->message = $message;

        return $this;
    }


    public function toArray(): array
    {
        return [
            'body' => [
                'type' => $this->type->value,
                $this->type->value => [
                    'text' => $this->message,
                ],
            ],
            'receiver' => [
                'contacts' => $this->contacts
            ]
        ];
    }
}
