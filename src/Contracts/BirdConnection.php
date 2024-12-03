<?php

namespace Media101\Bird\Contracts;

use Media101\Bird\Exceptions\InvalidParameterException;

trait BirdConnection
{
    /**
     * Generates the headers required for API requests to the Bird platform.
     *
     * @throws InvalidParameterException
     *
     * @return array The headers for Bird API
     */
    public function headers(): array
    {
        $accessKey = config('bird.access_key');

        if (! $accessKey) {
            throw InvalidParameterException::configValueIsNotSet('bird.access_key');
        }

        return [
            'Content-Type'  => 'application/json',
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
    protected function endpoint(?string $path = null): string
    {
        $apiEndpoint =  'https://api.bird.com';
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
