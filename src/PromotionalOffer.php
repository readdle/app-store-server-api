<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use InvalidArgumentException;
use JsonSerializable;

use function array_filter;

final class PromotionalOffer implements JsonSerializable
{
    /**
     * The identifier of the message to display to the customer, along with the promotional offer.
     */
    private string $messageIdentifier;

    /**
     * The promotional offer signature in V2 format.
     */
    private ?string $promotionalOfferSignatureV2;

    /**
     * The promotional offer signature in V1 format.
     */
    private ?PromotionalOfferSignatureV1 $promotionalOfferSignatureV1;

    public function __construct(
        string $messageIdentifier,
        ?string $promotionalOfferSignatureV2 = null,
        ?PromotionalOfferSignatureV1 $promotionalOfferSignatureV1 = null
    ) {
        $signaturesCount = (int) ($promotionalOfferSignatureV2 !== null) + (int) ($promotionalOfferSignatureV1 !== null);

        if ($signaturesCount !== 1) {
            throw new InvalidArgumentException(
                'PromotionalOffer requires exactly one signature: V2 string or V1 DTO.'
            );
        }

        $this->messageIdentifier = $messageIdentifier;
        $this->promotionalOfferSignatureV2 = $promotionalOfferSignatureV2;
        $this->promotionalOfferSignatureV1 = $promotionalOfferSignatureV1;
    }

    /**
     * Returns the identifier of the message to display to the customer, along with the promotional offer.
     */
    public function getMessageIdentifier(): string
    {
        return $this->messageIdentifier;
    }

    /**
     * Returns the promotional offer signature in V2 format.
     */
    public function getPromotionalOfferSignatureV2(): ?string
    {
        return $this->promotionalOfferSignatureV2;
    }

    /**
     * Returns the promotional offer signature in V1 format.
     */
    public function getPromotionalOfferSignatureV1(): ?PromotionalOfferSignatureV1
    {
        return $this->promotionalOfferSignatureV1;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return array_filter(get_object_vars($this), static fn ($value) => $value !== null);
    }
}
