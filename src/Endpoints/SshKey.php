<?php

namespace SpinupWp\Endpoints;

class SshKey extends Endpoint
{
    public function get(): string
    {
        $response = $this->getRequest('ssh-key');

        return $response['key'];
    }
}
