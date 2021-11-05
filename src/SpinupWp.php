<?php

namespace DeliciousBrains\SpinupWp;

use DeliciousBrains\SpinupWp\Endpoints\Event;
use DeliciousBrains\SpinupWp\Endpoints\Server;
use DeliciousBrains\SpinupWp\Endpoints\Site;
use GuzzleHttp\Client as HttpClient;

/**
 * @property Event $events
 * @property Server $servers
 * @property Site $sites
 */
class SpinupWp
{
    protected string $apiKey;

    protected HttpClient $client;

    protected array $endpoints = [];

    public function __construct(string $apiKey = null, HttpClient $client = null)
    {
        $this->apiKey = $apiKey ?: '';

        $this->setClient($client);
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function setClient(HttpClient $client = null): self
    {
        $this->client = $client ?: new HttpClient([
            'base_uri'    => 'https://api.spinupwp.app/v1/',
            'http_errors' => false,
            'headers'     => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            ],
        ]);

        return $this;
    }

    public function getClient(): HttpClient
    {
        return $this->client;
    }

    /**
     * @return mixed|void
     */
    public function __get(string $name)
    {
        if (isset($this->endpoints[$name])) {
            return $this->endpoints[$name];
        }

        $class = $this->buildEndpointClass($name);

        if (class_exists($class)) {
            $this->endpoints[$name] = new $class($this);

            return $this->endpoints[$name];
        }
    }

    protected function buildEndpointClass(string $name): string
    {
        $name = ucfirst(substr($name, 0, -1));

        return "\DeliciousBrains\SpinupWp\Endpoints\\{$name}";
    }
}
