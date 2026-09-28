<?php

namespace SpinupWp\Endpoints;

use SpinupWp\Resources\ResourceCollection;
use SpinupWp\Resources\SshKey as SshKeyResource;

class SshKey extends Endpoint
{
    public function get(): string
    {
        $response = $this->getRequest('ssh-key');

        return $response['key'];
    }

    public function list(int $page = 1, array $parameters = []): ResourceCollection
    {
        $sshKeys = $this->getRequest('ssh-keys', array_merge([
            'page' => $page,
        ], $parameters));

        return $this->transformCollection(
            $sshKeys['data'],
            SshKeyResource::class,
            $this->getPaginator($sshKeys['pagination'], $parameters),
        );
    }
}
