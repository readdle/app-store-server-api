<?php

declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Tests\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Readdle\AppStoreServerAPI\AdvancedCommerceInfo;
use Readdle\AppStoreServerAPI\AlternateProduct;
use Readdle\AppStoreServerAPI\Message;
use Readdle\AppStoreServerAPI\PromotionalOffer;
use Readdle\AppStoreServerAPI\PromotionalOfferSignatureV1;
use Readdle\AppStoreServerAPI\RealtimeResponseBody;

final class RealtimeResponseBodyTest extends TestCase
{
    public function testMessageResponseBodyCanBeCreatedAndSerialized(): void
    {
        $responseBody = RealtimeResponseBody::fromMessage(new Message('message_id'));

        $this->assertSame(
            '{"message":{"messageIdentifier":"message_id"}}',
            json_encode($responseBody)
        );
    }

    public function testAlternateProductCanBeCreatedAndSerialized(): void
    {
        $alternateProduct = new AlternateProduct(
            'message_id',
            'com.example.subscription.monthly',
            AlternateProduct::BILLING_PLAN_TYPE__MONTHLY
        );

        $this->assertSame('message_id', $alternateProduct->getMessageIdentifier());
        $this->assertSame('com.example.subscription.monthly', $alternateProduct->getProductId());
        $this->assertSame(AlternateProduct::BILLING_PLAN_TYPE__MONTHLY, $alternateProduct->getBillingPlanType());
        $this->assertSame(
            '{"messageIdentifier":"message_id","productId":"com.example.subscription.monthly","billingPlanType":"MONTHLY"}',
            json_encode($alternateProduct)
        );
    }

    public function testPromotionalOfferWithV2SignatureCanBeCreatedAndSerialized(): void
    {
        $promotionalOffer = new PromotionalOffer('message_id', 'signed_jws');

        $this->assertSame('message_id', $promotionalOffer->getMessageIdentifier());
        $this->assertSame('signed_jws', $promotionalOffer->getPromotionalOfferSignatureV2());
        $this->assertNull($promotionalOffer->getPromotionalOfferSignatureV1());
        $this->assertSame(
            '{"messageIdentifier":"message_id","promotionalOfferSignatureV2":"signed_jws"}',
            json_encode($promotionalOffer)
        );
    }

    public function testPromotionalOfferWithV1SignatureCanBeCreatedAndSerialized(): void
    {
        $signature = new PromotionalOfferSignatureV1(
            'encoded_signature',
            'com.example.subscription.monthly',
            'nonce-uuid',
            1710000000000,
            'KEY1234567',
            'offer_id',
            'app-account-token'
        );
        $promotionalOffer = new PromotionalOffer('message_id', null, $signature);

        $this->assertSame('encoded_signature', $signature->getEncodedSignature());
        $this->assertSame('com.example.subscription.monthly', $signature->getProductId());
        $this->assertSame('nonce-uuid', $signature->getNonce());
        $this->assertSame(1710000000000, $signature->getTimestamp());
        $this->assertSame('KEY1234567', $signature->getKeyId());
        $this->assertSame('offer_id', $signature->getOfferIdentifier());
        $this->assertSame('app-account-token', $signature->getAppAccountToken());
        $this->assertSame(
            '{"messageIdentifier":"message_id","promotionalOfferSignatureV1":{"encodedSignature":"encoded_signature","productId":"com.example.subscription.monthly","nonce":"nonce-uuid","timestamp":1710000000000,"keyId":"KEY1234567","offerIdentifier":"offer_id","appAccountToken":"app-account-token"}}',
            json_encode($promotionalOffer)
        );
    }

    public function testPromotionalOfferRequiresExactlyOneSignature(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('PromotionalOffer requires exactly one signature: V2 string or V1 DTO.');

        new PromotionalOffer('message_id');
    }

    public function testPromotionalOfferRejectsMultipleSignatures(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('PromotionalOffer requires exactly one signature: V2 string or V1 DTO.');

        new PromotionalOffer(
            'message_id',
            'signed_jws',
            new PromotionalOfferSignatureV1(
                'encoded_signature',
                'com.example.subscription.monthly',
                'nonce-uuid',
                1710000000000,
                'KEY1234567',
                'offer_id'
            )
        );
    }

    public function testAdvancedCommerceInfoCanBeCreatedAndSerialized(): void
    {
        $advancedCommerceInfo = new AdvancedCommerceInfo('message_id', 'base64_encoded_data');

        $this->assertSame('message_id', $advancedCommerceInfo->getMessageIdentifier());
        $this->assertSame('base64_encoded_data', $advancedCommerceInfo->getAdvancedCommerceData());
        $this->assertSame(
            '{"messageIdentifier":"message_id","advancedCommerceData":"base64_encoded_data"}',
            json_encode($advancedCommerceInfo)
        );
    }

    public function testRealtimeResponseBodyRequiresExactlyOnePayloadType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'RealtimeResponseBody requires exactly one of: message, alternateProduct, promotionalOffer, advancedCommerceInfo.'
        );

        new RealtimeResponseBody();
    }

    public function testRealtimeResponseBodyRejectsMultiplePayloadTypes(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'RealtimeResponseBody requires exactly one of: message, alternateProduct, promotionalOffer, advancedCommerceInfo.'
        );

        new RealtimeResponseBody(
            new Message('message_id'),
            new AlternateProduct('message_id', 'com.example.subscription.monthly')
        );
    }

    public function testRealtimeResponseBodyFactoriesCreateExpectedPayloads(): void
    {
        $alternateProductResponse = RealtimeResponseBody::fromAlternateProduct(
            new AlternateProduct('message_id', 'com.example.subscription.monthly')
        );
        $promotionalOfferResponse = RealtimeResponseBody::fromPromotionalOffer(
            new PromotionalOffer('message_id', 'signed_jws')
        );
        $advancedCommerceInfoResponse = RealtimeResponseBody::fromAdvancedCommerceInfo(
            new AdvancedCommerceInfo('message_id', 'base64_encoded_data')
        );

        $this->assertSame(
            '{"alternateProduct":{"messageIdentifier":"message_id","productId":"com.example.subscription.monthly"}}',
            json_encode($alternateProductResponse)
        );
        $this->assertSame(
            '{"promotionalOffer":{"messageIdentifier":"message_id","promotionalOfferSignatureV2":"signed_jws"}}',
            json_encode($promotionalOfferResponse)
        );
        $this->assertSame(
            '{"advancedCommerceInfo":{"messageIdentifier":"message_id","advancedCommerceData":"base64_encoded_data"}}',
            json_encode($advancedCommerceInfoResponse)
        );
    }
}
