<?php

namespace Media101\Bird\Supports\Contacts;


use Media101\Bird\Exceptions\InvalidParameterException;

class BirdContact
{
    private string $displayName = '';

    private array $identifiers = [];

    private array $attributes = [];


    public function getPhone(): ?string
    {
        $identifier = collect($this->identifiers)->firstWhere('key', 'phonenumber');

        return $identifier['value'] ?? null;
    }


    /**
     * @throws InvalidParameterException
     */
    public function phone(string $phone): static
    {
        if (! $this->isValidPhoneNumber($phone)) {
            throw InvalidParameterException::invalidPhoneNumber($phone);
        }

        $this->identifiers[] = [
            'key' => 'phonenumber',
            'value' => $phone,
        ];

        return $this;
    }


    public function getName(): string
    {
        return $this->displayName;
    }


    public function name(string $name): static
    {
        $this->displayName = $name;

        return $this;
    }


    public function getEmail(): ?string
    {
        $identifier = collect($this->identifiers)->firstWhere('key', 'emailaddress');

        return $identifier['value'] ?? null;
    }


    public function email(string $email): static
    {
        $this->identifiers[] = [
            'key' => 'emailaddress',
            'value' => $email,
        ];

        return $this;
    }


    public function getIdentifiers(): array
    {
        return $this->identifiers;
    }


    public function getAttributes(): array
    {
        return $this->attributes;
    }


    public function attribute(string $attribute, string $value): static
    {
        $this->attributes = array_merge($this->attributes, [$attribute => $value]);

        return $this;
    }


    public function toArray(): array|object
    {
        return [
            'displayName' => $this->displayName,
            'identifiers' => $this->identifiers,
            ...$this->attributes,
        ];
    }


    /**
     *  Validates if the given phone number is capable of sending SMS
     */
    private function isValidPhoneNumber(string $phoneNumber): bool
    {
        $phoneRegex = config('bird.phone_number_regex');

        return !$phoneRegex || preg_match($phoneRegex, $phoneNumber) === 1;
    }
}
