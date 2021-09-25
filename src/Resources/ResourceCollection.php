<?php

namespace DeliciousBrains\SpinupWp\Resources;

use Countable;
use DeliciousBrains\SpinupWp\Endpoints\Endpoint;
use Generator;
use IteratorAggregate;

class ResourceCollection implements Countable, IteratorAggregate
{
    protected array $payload;

    protected string $class;

    protected Endpoint $endpoint;

    protected array $data;

    public function __construct(array $payload, string $class, Endpoint $endpoint)
    {
        $this->payload  = $payload;
        $this->class    = $class;
        $this->endpoint = $endpoint;

        $this->mapResourceClass();
    }

    protected function mapResourceClass(): void
    {
        $this->data = array_map(function ($data) {
            return new $this->class($data, $this->endpoint);
        }, $this->payload['data']);
    }

    protected function hasNext(): bool
    {
        return !empty($this->payload['pagination']['next']);
    }

    protected function hasPrevious(): bool
    {
        return !empty($this->payload['pagination']['previous']);
    }

    public function payload(): array
    {
        return $this->payload;
    }

    public function count(): int
    {
        return $this->payload['pagination']['count'];
    }

    public function getIterator(): Generator
    {
        $currentPage = 1;

        foreach ($this->data as $resource) {
            yield $resource;
        }

        while ($this->hasNext()) {
            $nextPage = $this->endpoint->list(++$currentPage);

            $this->payload = $nextPage->payload();
            $this->mapResourceClass();

            foreach ($this->data as $resource) {
                yield $resource;
            }
        }
    }

    public function toArray(): array
    {
        return iterator_to_array($this);
    }
}