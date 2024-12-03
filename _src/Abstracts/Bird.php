<?php

namespace Media101\_src\Abstracts;


use Media101\_src\Exceptions\InvalidParameterException;

abstract class Bird
{
    protected static string $apiEndpoint = 'https://api.bird.com';

    /**
     * Generates the headers required for API requests to the Bird platform.
     *
     * @throws InvalidParameterException
     *
     * @return array The headers for Bird API
     */
    protected static function headers(): array
    {
        $accessKey = config('bird.access_key');

        if (! $accessKey) {
            throw InvalidParameterException::configValueIsNotSet('bird.access_key');
        }

        return [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $accessKey",
        ];
    }


    /**
     * This method generates the API endpoint URL for the Bird platform. \
     * It retrieves the workspace ID from the application's configuration and throws an exception if it's not set.
     *
     * @throws InvalidParameterException
     * @param string|null $path
     * @return string
     */
    protected static function endpoint(string $path = null): string
    {
        $apiEndpoint = self::$apiEndpoint;
        $workspaceID = config('bird.workspace_id');

        if (! $workspaceID) {
            throw InvalidParameterException::configValueIsNotSet('bird.workspace_id');
        }

        $endpoint = "$apiEndpoint/workspaces/$workspaceID";

        return $path
            ? "$endpoint/$path"
            : $endpoint;
    }
}
