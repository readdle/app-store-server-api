<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use JsonSerializable;
use Readdle\AppStoreServerAPI\Exception\AppStoreServerNotificationException;
use Readdle\AppStoreServerAPI\Exception\MalformedJWTException;
use Readdle\AppStoreServerAPI\Util\ArrayTypeCaseGenerator;
use Readdle\AppStoreServerAPI\Util\JWT;

final class DecodedRealtimeRequestBody implements JsonSerializable
{
    /**
     * The original transaction identifier of the customer's subscription.
     */
    private string $originalTransactionId;

    /**
     * The unique identifier of the app in the App Store.
     */
    private int $appAppleId;

    /**
     * The unique identifier of the auto-renewable subscription.
     */
    private string $productId;

    /**
     * The user's locale.
     */
    private string $userLocale;

    /**
     * A UUID the App Store server creates to uniquely identify each request.
     */
    private string $requestIdentifier;

    /**
     * The UNIX time, in milliseconds, that the App Store signed the JSON Web Signature (JWS) data.
     */
    private int $signedDate;

    /**
     * The server environment, either sandbox or production.
     *
     * @return Environment::PRODUCTION|Environment::SANDBOX
     */
    private string $environment;

    public static function createFromEncodedRealtimeRequestBody(
        string $encodedRealtimeRequestBody,
        ?string $rootCertificate = null
    ): self {
        $requestBody = json_decode($encodedRealtimeRequestBody, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($requestBody)) {
            throw new AppStoreServerNotificationException('Request body is not a valid JSON array');
        }

        if (!array_key_exists('signedPayload', $requestBody)) {
            throw new AppStoreServerNotificationException('Request body does not contain "signedPayload" property');
        }

        try {
            $payload = JWT::parse($requestBody['signedPayload'], $rootCertificate);
        } catch (MalformedJWTException $e) {
            throw new AppStoreServerNotificationException('Malformed JWT: ' . $e->getMessage());
        }

        $decodedRealtimeRequestBody = new self();

        $typeCaster = (new ArrayTypeCaseGenerator())($payload, [
            'int' => ['signedDate', 'appAppleId'],
            'string' => ['originalTransactionId', 'productId', 'userLocale', 'requestIdentifier', 'environment'],
        ]);

        foreach ($typeCaster as $prop => $value) {
            $decodedRealtimeRequestBody->$prop = $value;
        }

        return $decodedRealtimeRequestBody;
    }

    public function getOriginalTransactionId(): string
    {
        return $this->originalTransactionId;
    }

    public function getAppAppleId(): int
    {
        return $this->appAppleId;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getUserLocale(): string
    {
        return $this->userLocale;
    }

    public function getRequestIdentifier(): string
    {
        return $this->requestIdentifier;
    }

    public function getSignedDate(): int
    {
        return $this->signedDate;
    }

    /**
     * Returns the number of milliseconds elapsed since the App Store signed this request.
     */
    public function getMillisecondsSinceSigned(): int
    {
        return (int) round(microtime(true) * 1000) - $this->signedDate;
    }

    /**
     * Returns the number of seconds elapsed since the App Store signed this request.
     */
    public function getSecondsSinceSigned(): int
    {
        return intdiv($this->getMillisecondsSinceSigned(), 1000);
    }

    /**
     * Returns whether this request is older than the given TTL, in seconds.
     */
    public function isExpired(int $ttlSeconds): bool
    {
        return $this->getSecondsSinceSigned() >= $ttlSeconds;
    }

    public function getEnvironment(): string
    {
        return $this->environment;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
