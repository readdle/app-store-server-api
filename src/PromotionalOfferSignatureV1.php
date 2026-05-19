<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use JsonSerializable;

use function array_filter;

final class PromotionalOfferSignatureV1 implements JsonSerializable
{
    /**
     * The Base64-encoded cryptographic signature you generate using the offer parameters.
     */
    private string $encodedSignature;

    /**
     * The subscription's product identifier.
     */
    private string $productId;

    /**
     * A one-time-use UUID antireplay value you generate.
     */
    private string $nonce;

    /**
     * The UNIX time, in milliseconds, when you generate the signature.
     */
    private int $timestamp;

    /**
     * A string that identifies the private key you use to generate the signature.
     */
    private string $keyId;

    /**
     * The subscription offer identifier that you set up in App Store Connect.
     */
    private string $offerIdentifier;

    /**
     * A UUID that you provide to associate with the transaction if the customer accepts the promotional offer.
     */
    private ?string $appAccountToken;

    public function __construct(
        string $encodedSignature,
        string $productId,
        string $nonce,
        int $timestamp,
        string $keyId,
        string $offerIdentifier,
        ?string $appAccountToken = null
    ) {
        $this->encodedSignature = $encodedSignature;
        $this->productId = $productId;
        $this->nonce = $nonce;
        $this->timestamp = $timestamp;
        $this->keyId = $keyId;
        $this->offerIdentifier = $offerIdentifier;
        $this->appAccountToken = $appAccountToken;
    }

    /**
     * Returns the Base64-encoded cryptographic signature you generate using the offer parameters.
     */
    public function getEncodedSignature(): string
    {
        return $this->encodedSignature;
    }

    /**
     * Returns the subscription's product identifier.
     */
    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * Returns the one-time-use UUID antireplay value you generate.
     */
    public function getNonce(): string
    {
        return $this->nonce;
    }

    /**
     * Returns the UNIX time, in milliseconds, when you generate the signature.
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * Returns the identifier of the private key you use to generate the signature.
     */
    public function getKeyId(): string
    {
        return $this->keyId;
    }

    /**
     * Returns the subscription offer identifier that you set up in App Store Connect.
     */
    public function getOfferIdentifier(): string
    {
        return $this->offerIdentifier;
    }

    /**
     * Returns the app account token you provide to associate with the transaction if the customer accepts the offer.
     */
    public function getAppAccountToken(): ?string
    {
        return $this->appAccountToken;
    }

    /**
     * @return array<string, int|string>
     */
    public function jsonSerialize(): array
    {
        return array_filter(get_object_vars($this), static fn ($value) => $value !== null);
    }
}
