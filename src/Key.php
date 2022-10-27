<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

final class Key
{
    private string $key;
    private string $keyId;

    public function __construct(string $key, string $keyId)
    {
        $this->key = $key;
        $this->keyId = $keyId;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getKeyId(): string
    {
        return $this->keyId;
    }
}
