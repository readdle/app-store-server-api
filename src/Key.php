<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

final class Key
{
    private string $privateKey;
    private string $keyId;

    public function __construct(string $privateKey, string $keyId)
    {
        $this->privateKey = $privateKey;
        $this->keyId = $keyId;
    }

    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }

    public function getKeyId(): string
    {
        return $this->keyId;
    }
}
