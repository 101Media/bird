<?php

namespace Media101\Bird\Supports\Contacts;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Media101\Bird\Abstracts\Bird;
use Media101\Bird\Exceptions\InvalidParameterException;

/**
 * Class BirdContacts
 *
 * Handles operations related to Bird contacts.
 */
class ContactManager extends Bird
{
    /**
     * Retrieves contacts from the API.
     *
     * @param string|null $id The ID of the contact to retrieve. If null, retrieves a list of contacts.
     * @param int $limit The number of contacts to retrieve. Default is 10.
     * @param bool $reverse Whether to retrieve contacts in reverse order. Default is false.
     * @param string $nextPageToken The token for the next page of results. Default is an empty string.
     * @return PromiseInterface|Response|null The retrieved contacts, or null on failure.
     * @throws InvalidParameterException|ConnectionException
     */
    public static function get(
        ?string $id = null,
        int     $limit = 10,
        bool    $reverse = false,
        string  $nextPageToken = ''
    ):  PromiseInterface|Response|null
    {
        $query = [
            'limit' => $limit,
            'reverse' => $reverse,
            'nextPageToken' => $nextPageToken,
        ];

        $getEndpoint = $id
            ? self::contactsEndpoint()."/$id"
            : self::contactsEndpoint();

        return Http::withHeaders(static::headers())
            ->get($getEndpoint, $query);
    }


    /**
     * Creates or updates a contact.
     *
     * @param BirdContact $birdContact The contact data transfer object.
     * @param string $identifierKey The contact identifier. Available "emailadress, phonenumber(default)"
     * @return PromiseInterface|Response|null The response from the API, or null on failure.
     * @throws InvalidParameterException
     * @throws ConnectionException
     */
    public static function createOrUpdate(
        BirdContact $birdContact,
        string      $identifierKey = 'phonenumber'
    ): PromiseInterface|Response|null
    {
        if (! $birdContact->getPhone()) {
            Log::error('Could not create or update contact because phone number was not provided.');
            InvalidParameterException::invalidPhoneNumber('');
        }

        $identifierValue = self::getIdentifierKey($identifierKey, $birdContact);

        return Http::withHeaders(static::headers())
            ->patch(self::createUpdateEndpoint($identifierKey, $identifierValue), $birdContact->toArray());
    }


    /**
     * Deletes a contact.
     *
     * @param string $id The UUID of the contact in bird to delete.
     * @return array|bool The response from the API, or true if deletion was successful.
     * @throws InvalidParameterException
     */
    public static function delete(string $id): array|bool
    {
        $http = Http::withHeaders(static::headers())
            ->delete(self::contactsEndpoint()."/$id");

        if ($http->status() !== 204) {
            return $http->json();
        }

        return true;
    }


    /**
     * Gets the identifier key value from the contact DTO.
     *
     * @param  string  $identifierKey  The key used to identify the contact.
     * @param  BirdContact  $contactDTO  The contact data transfer object.
     * @return string The value of the identifier key.
     */
    private static function getIdentifierKey(string $identifierKey, BirdContact $contactDTO): string
    {
        return match ($identifierKey) {
            'phonenumber' => $contactDTO->getPhone(),
            'emailaddress' => $contactDTO->getEmail(),
            default => '',
        };
    }


    /**
     * Gets the endpoint for contacts.
     *
     * @return string The contacts endpoint URL.
     * @throws InvalidParameterException
     */
    private static function contactsEndpoint(): string
    {
        return self::endpoint('contacts');
    }


    /**
     * Gets the endpoint for creating or updating a contact.
     *
     * @param string $key The identifier key.
     * @param string $value The identifier value.
     * @return string The create/update endpoint URL.
     * @throws InvalidParameterException
     */
    private static function createUpdateEndpoint(string $key, string $value): string
    {
        return self::contactsEndpoint()."/identifiers/$key/$value";
    }
}
