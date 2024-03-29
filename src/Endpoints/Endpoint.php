<?php

namespace SpinupWp\Endpoints;

use Exception;
use Psr\Http\Message\ResponseInterface;
use SpinupWp\Exceptions\AccessDeniedException;
use SpinupWp\Exceptions\NotFoundException;
use SpinupWp\Exceptions\RateLimitException;
use SpinupWp\Exceptions\TimeoutException;
use SpinupWp\Exceptions\UnauthorizedException;
use SpinupWp\Exceptions\ValidationException;
use SpinupWp\Resources\Paginator;
use SpinupWp\Resources\ResourceCollection;
use SpinupWp\SpinupWp;

abstract class Endpoint
{
    protected SpinupWp $spinupwp;

    public function __construct(SpinupWp $spinupwp)
    {
        $this->spinupwp = $spinupwp;
    }

    protected function request(string $verb, string $uri, array $payload = []): array
    {
        $response = $this->spinupwp->getClient()->request(
            $verb,
            $uri,
            empty($payload) ? [] : ['form_params' => $payload]
        );

        $statusCode = $response->getStatusCode();

        if ($statusCode < 200 || $statusCode > 299) {
            $this->handleRequestError($response);
        }

        $responseBody = (string) $response->getBody();

        return json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR);
    }

    protected function handleRequestError(ResponseInterface $response): void
    {
        if ($response->getStatusCode() === 401) {
            throw new UnauthorizedException();
        }

        if ($response->getStatusCode() === 403) {
            throw new AccessDeniedException();
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

    public function getRequest(string $uri, array $parameters = []): array
    {
        if ($parameters) {
            $uri .= '?' . http_build_query($parameters);
        }

        return $this->request('GET', $uri);
    }

    public function postRequest(string $uri, array $payload = []): array
    {
        return $this->request('POST', $uri, $payload);
    }

    public function deleteRequest(string $uri, array $payload = []): array
    {
        return $this->request('DELETE', $uri, $payload);
    }

    protected function transformCollection(array $data, string $class, Paginator $paginator = null): ResourceCollection
    {
        return new ResourceCollection($data, $class, $this->spinupwp, $paginator);
    }

    protected function getPaginator(array $pagination, array $parameters = []): Paginator
    {
        return new Paginator($this, $pagination, $parameters);
    }

    /**
     * @return mixed
     * @throws TimeoutException
     */
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

        if (!(is_array($return) || is_null($return))) {
            $return = [$return];
        }
        throw new TimeoutException($return);
    }
}
