<?php

namespace DeliciousBrains\SpinupWp\Resources;

use Countable;
use DeliciousBrains\SpinupWp\SpinupWp;
use Generator;
use IteratorAggregate;

class ResourceCollection implements Countable, IteratorAggregate
{
    protected string $class;

    public SpinupWp $spinupwp;

    protected ?Paginator $paginator;

    protected array $resources = [];

    public function __construct(array $resources, string $class, SpinupWp $spinupwp, ?Paginator $paginator = null)
    {
        $this->class     = $class;
        $this->spinupwp  = $spinupwp;
        $this->paginator = $paginator;

        $this->resources = $this->mapResourceClass($resources);
    }

    protected function mapResourceClass(array $resources): array
    {
        return array_map(function ($resource) {
            return new $this->class(['data' => $resource], $this->spinupwp);
        }, $resources);
    }

    public function count(): int
    {
        if ($this->paginator instanceof Paginator) {
            return $this->paginator->count();
        }

        return count($this->resources);
    }

    public function getIterator(): Generator
    {
        foreach ($this->resources as $resource) {
            yield $resource;
        }

        if (!$this->paginator) {
            return;
        }

        while ($this->paginator->hasNext()) {
            $nextResources = $this->mapResourceClass($this->paginator->nextPage());

            foreach ($nextResources as $resource) {
                $this->resources[] = $resource;

                yield $resource;
            }
        }
    }

    public function toArray(): array
    {
        return iterator_to_array($this);
    }
}
