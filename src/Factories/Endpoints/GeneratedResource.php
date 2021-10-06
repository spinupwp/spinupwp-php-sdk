<?php

namespace DeliciousBrains\SpinupWp\Factories\Endpoints;

use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Enumerable;

class GeneratedResource
{
    public Response $response;
    public array $sourceData;

    public function __construct(array $sourceData, Response $response)
    {
        $this->sourceData = $sourceData;
        $this->response = $response;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function getSourceData(): array
    {
        return $this->sourceData;
    }

    public function get($key, $default = null)
    {
        $array = $this->sourceData;

        if (strpos($key, '.') === false) {
            return $array[$key] ?? $default;
        }

        foreach (explode('.', $key) as $segment) {
            if ($this->accessible($array) && static::exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }

    public function accessible($value): bool
    {
        return is_array($value) || $value instanceof \ArrayAccess;
    }

    public function exists($array, $key): bool
    {
        if ($array instanceof \ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }
}