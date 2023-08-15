<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

final class Key
{
    private string $keyId;
    private string $key;

    public function __construct(string $keyId, string $key)
    {
        $this->keyId = $keyId;
        $this->key = $key;
    }

    public function getKeyId(): string
    {
        return $this->keyId;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
