<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI;

use InvalidArgumentException;
use JsonSerializable;

final class RealtimeResponseBody implements JsonSerializable
{
    /**
     * A retention message that's text-based and can include an optional image.
     */
    private ?Message $message;

    /**
     * A retention message with a switch-plan option.
     */
    private ?AlternateProduct $alternateProduct;

    /**
     * A retention message that includes a promotional offer.
     */
    private ?PromotionalOffer $promotionalOffer;

    /**
     * A response object you provide to present an offer or switch-plan recommendation message.
     */
    private ?AdvancedCommerceInfo $advancedCommerceInfo;

    public function __construct(
        ?Message $message = null,
        ?AlternateProduct $alternateProduct = null,
        ?PromotionalOffer $promotionalOffer = null,
        ?AdvancedCommerceInfo $advancedCommerceInfo = null
    ) {
        $optionsCount = (int) ($message !== null)
            + (int) ($alternateProduct !== null)
            + (int) ($promotionalOffer !== null)
            + (int) ($advancedCommerceInfo !== null);

        if ($optionsCount !== 1) {
            throw new InvalidArgumentException(
                'RealtimeResponseBody requires exactly one of: message, alternateProduct, promotionalOffer, advancedCommerceInfo.'
            );
        }

        $this->message = $message;
        $this->alternateProduct = $alternateProduct;
        $this->promotionalOffer = $promotionalOffer;
        $this->advancedCommerceInfo = $advancedCommerceInfo;
    }

    public static function fromMessage(Message $message): self
    {
        return new self($message);
    }

    public static function fromAlternateProduct(AlternateProduct $alternateProduct): self
    {
        return new self(null, $alternateProduct);
    }

    public static function fromPromotionalOffer(PromotionalOffer $promotionalOffer): self
    {
        return new self(null, null, $promotionalOffer);
    }

    public static function fromAdvancedCommerceInfo(AdvancedCommerceInfo $advancedCommerceInfo): self
    {
        return new self(null, null, null, $advancedCommerceInfo);
    }

    /**
     * Returns a text-based retention message.
     */
    public function getMessage(): ?Message
    {
        return $this->message;
    }

    /**
     * Returns a retention message with a switch-plan option.
     */
    public function getAlternateProduct(): ?AlternateProduct
    {
        return $this->alternateProduct;
    }

    /**
     * Returns a retention message that includes a promotional offer.
     */
    public function getPromotionalOffer(): ?PromotionalOffer
    {
        return $this->promotionalOffer;
    }

    /**
     * Returns the response object you provide to present an offer or switch-plan recommendation message.
     */
    public function getAdvancedCommerceInfo(): ?AdvancedCommerceInfo
    {
        return $this->advancedCommerceInfo;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return array_filter(get_object_vars($this), static fn ($value) => $value !== null);
    }
}
