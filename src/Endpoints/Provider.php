<?php

namespace SpinupWp\Endpoints;

class Provider extends Endpoint
{
    public function metadata(string $provider): array
    {
        return $this->getRequest("providers/{$provider}/metadata");
    }
}
