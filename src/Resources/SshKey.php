<?php

namespace SpinupWp\Resources;

class SshKey extends Resource
{
    /**
     * @return int[] The IDs of the events removing the key from the site users and sudo users that had it.
     */
    public function delete(): array
    {
        return $this->spinupwp->sshKeys->delete($this->id);
    }
}
