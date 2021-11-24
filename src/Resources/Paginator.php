<?php

namespace DeliciousBrains\SpinupWp\Resources;

use Countable;
use DeliciousBrains\SpinupWp\Endpoints\Endpoint;
use DeliciousBrains\SpinupWp\SpinupWp;

class Paginator implements Countable
{
    protected Endpoint $endpoint;
    protected ?string $previousPage;
    protected ?string $nextPage;
    protected int $count;
    protected array $parameters;

    public function __construct(Endpoint $endpoint, array $paginationData, array $parameters = [])
    {
        $this->endpoint   = $endpoint;
        $this->parameters = $parameters;

        $this->fill($paginationData);
    }

    protected function fill(array $paginationData): void
    {
        $this->previousPage = $paginationData['previous'] ?? null;
        $this->nextPage     = $paginationData['next'] ?? null;
        $this->count        = $paginationData['count'] ?? 0;
    }

    public function count(): int
    {
        return $this->count;
    }

    public function hasNext(): bool
    {
        return !empty($this->nextPage);
    }

    public function nextPage(): array
    {
        parse_str(parse_url($this->nextPage, PHP_URL_QUERY), $queryParameters);
        $url = strtok($this->nextPage, '?'); // Remove query string
        $uri = str_replace(SpinupWp::API_URL, '', $url);

        $response = $this->endpoint->getRequest($uri, array_merge($queryParameters, $this->parameters));

        $this->fill($response['pagination']);

        return $response['data'];
    }
}