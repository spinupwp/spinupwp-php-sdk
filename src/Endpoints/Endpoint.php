<?php

namespace DeliciousBrains\SpinupWp\Endpoints;

use DeliciousBrains\SpinupWp\Exceptions\NotFoundException;
use DeliciousBrains\SpinupWp\Exceptions\RateLimitException;
use DeliciousBrains\SpinupWp\Exceptions\TimeoutException;
use DeliciousBrains\SpinupWp\Exceptions\UnauthorizedException;
use DeliciousBrains\SpinupWp\Exceptions\ValidationException;
use DeliciousBrains\SpinupWp\Resources\ResourceCollection;
use Exception;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

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

        $statusCode = $response->getStatusCode();

        if ($statusCode < 200 || $statusCode > 299) {
            return $this->handleRequestError($response);
        }

        $responseBody = (string) $response->getBody();

        return json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR);
    }

    protected function handleRequestError(ResponseInterface $response): void
    {
        if ($response->getStatusCode() === 401) {
            throw new UnauthorizedException();
        }

        if ($response->getStatusCode() === 404) {
            throw new NotFoundException();
        }

        if ($response->getStatusCode() === 422) {
            $responseBody = (string) $response->getBody();

            throw new ValidationException(json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR));
        }

        if ($response->getStatusCode() === 429) {
            throw new RateLimitException();
        }

        throw new Exception((string) $response->getBody());
    }

    protected function getRequest(string $uri)
    {
        return $this->request('GET', $uri);
    }

    protected function postRequest(string $uri, array $payload = [])
    {
        return $this->request('POST', $uri, $payload);
    }

    protected function deleteRequest(string $uri, array $payload = [])
    {
        return $this->request('DELETE', $uri, $payload);
    }

    protected function transformCollection(array $payload, string $class): ResourceCollection
    {
        return new ResourceCollection($payload, $class, $this);
    }

    protected function wait(callable $callback, int $timeout = 300, int $sleep = 10)
    {
        $start = time();

        beginning:

        $return = $callback();

        if ($return) {
            return $return;
        }

        if (time() - $start < $timeout) {
            sleep($sleep);

            goto beginning;
        }

        throw new TimeoutException($return);
    }
}