<?php

namespace DeliciousBrains\SpinupWp;

use GuzzleHttp\Client as HttpClient;

class SpinupWp
{
    protected string $apiKey;

    protected HttpClient $client;

    public function __construct(string $apiKey, HttpClient $client = null)
    {
        $this->apiKey = $apiKey;

        $this->client = $client ?: $this->setClient();
    }

    public function setClient(): HttpClient
    {
        return new HttpClient([
            'base_uri'    => 'https://api.spinupwp.app/v1/',
            'http_errors' => false,
            'headers'     => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            ],
        ]);
    }
}