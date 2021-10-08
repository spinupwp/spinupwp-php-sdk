<?php

namespace DeliciousBrains\SpinupWp\Resources;

use Countable;
use DeliciousBrains\SpinupWp\Endpoints\Endpoint;
use DeliciousBrains\SpinupWp\SpinupWp;
use Generator;
use IteratorAggregate;

class ResourceCollection implements Countable, IteratorAggregate
{
    protected array $payload;

    protected string $class;

    protected Endpoint $endpoint;

    public SpinupWp $spinupwp;

    protected int $page;

    protected array $data;

    public function __construct(array $payload, string $class, Endpoint $endpoint, SpinupWp $spinupwp, int $page = 1)
    {
        $this->payload  = $payload;
        $this->class    = $class;
        $this->endpoint = $endpoint;
        $this->spinupwp = $spinupwp;
        $this->page     = $page;

        $this->mapResourceClass();
    }

    protected function mapResourceClass(): void
    {
        $this->data = array_map(function ($data) {
            return new $this->class($data, $this->endpoint, $this->spinupwp);
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
        foreach ($this->data as $resource) {
            yield $resource;
        }

        while ($this->hasNext()) {
            if (!method_exists($this->endpoint, 'list')) {
                return;
            }

            $nextPage = $this->endpoint->list(++$this->page);

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
