<?php

namespace DeliciousBrains\SpinupWp\Endpoints;

use GuzzleHttp\Client;

abstract class Endpoint
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    protected function request(string $verb, string $uri, array $payload = [])
    {
        $response = $this->client->request($verb, $uri,
            empty($payload) ? [] : ['form_params' => $payload]
        );

        $responseBody = (string) $response->getBody();

        return json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR);
    }

    protected function getRequest(string $uri)
    {
        return $this->request('GET', $uri);
    }

    protected function deleteRequest(string $uri, array $payload = [])
    {
        return $this->request('DELETE', $uri, $payload);
    }

    protected function transformCollection(array $collection, string $class): array
    {
        return array_map(function ($data) use ($class) {
            return new $class($data, $this);
        }, $collection);
    }
}