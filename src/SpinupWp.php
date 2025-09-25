<?php

namespace SpinupWp;

use GuzzleHttp\Client as HttpClient;
use SpinupWp\Endpoints\Event;
use SpinupWp\Endpoints\Server;
use SpinupWp\Endpoints\Site;
use SpinupWp\Endpoints\SshKey;

/**
 * @property Event $events
 * @property Server $servers
 * @property Site $sites
 * @property SshKey $sshKeys
 */
class SpinupWp
{
    public const API_URL = 'https://api.spinupwp.test/v1/';

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

    public function hasApiKey(): bool
    {
        return !empty($this->apiKey);
    }

    public function setClient(HttpClient $client = null): self
    {
        $this->client = $client ?: new HttpClient([
            'base_uri'    => self::API_URL,
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

        return "\SpinupWp\Endpoints\\{$name}";
    }
}
