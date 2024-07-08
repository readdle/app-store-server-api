<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use function min;
use function time;

final class Payload
{
    const MAX_TTL = 3600;
    const DEFAULT_TTL = 300;

    /**
     * Issuer ID from the Keys page in App Store Connect (Ex: "57246542-96fe-1a63-e053-0824d011072a").
     */
    private string $issuerId;

    /**
     * Audience.
     * Constant value.
     * @noinspection SpellCheckingInspection
     */
    private string $audience = 'appstoreconnect-v1';

    /**
     * App's bundle ID (Ex: “com.example.testBundleId2021”).
     */
    private string $bundleId;

    private int $ttl;

    public function __construct(string $issuerId, string $bundleId, int $ttl = 0)
    {
        $this->issuerId = $issuerId;
        $this->bundleId = $bundleId;

        $this->ttl = $ttl === 0 ? self::DEFAULT_TTL : min($ttl, self::MAX_TTL);
    }

    /**
     * @return array<string, int|string>
     */
    public function toArray(): array
    {
        $time = time();

        return [
            'iss' => $this->issuerId,
            'iat' => $time,
            'exp' => $time + $this->ttl,
            'aud' => $this->audience,
            'bid' => $this->bundleId,
        ];
    }
}
