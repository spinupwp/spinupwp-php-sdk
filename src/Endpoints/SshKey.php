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

    public function create(array $data): SshKeyResource
    {
        $sshKey = $this->postRequest('ssh-keys', $data);

        return new SshKeyResource($sshKey, $this->spinupwp);
    }

    /**
     * @return int[] The IDs of the events removing the key from the site users and sudo users that had it.
     */
    public function delete(int $id): array
    {
        $request = $this->deleteRequest("ssh-keys/{$id}");

        return $request['event_ids'];
    }
}
