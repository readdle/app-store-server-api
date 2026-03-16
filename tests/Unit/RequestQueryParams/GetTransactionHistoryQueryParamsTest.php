<?php

declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Tests\Unit\RequestQueryParams;

use PHPUnit\Framework\TestCase;
use Readdle\AppStoreServerAPI\RequestQueryParams\GetTransactionHistoryQueryParams;

final class GetTransactionHistoryQueryParamsTest extends TestCase
{
    public function testDefaultsProduceEmptyQueryString(): void
    {
        $params = new GetTransactionHistoryQueryParams();

        $this->assertSame('', $params->getQueryString());
    }

    public function testArrayParamProducesRepeatedKeys(): void
    {
        $params = new GetTransactionHistoryQueryParams(['productId' => ['com.example.monthly', 'com.example.yearly']]);

        $this->assertSame('productId=com.example.monthly&productId=com.example.yearly', $params->getQueryString());
    }

    public function testArrayParamValuesAreUrlEncoded(): void
    {
        $params = new GetTransactionHistoryQueryParams(['productId' => ['com.example app', 'com.example/other']]);

        $this->assertSame('productId=com.example%20app&productId=com.example%2Fother', $params->getQueryString());
    }

    public function testBoolParamRenderedAsTrue(): void
    {
        $params = new GetTransactionHistoryQueryParams(['excludeRevoked' => true]);

        $this->assertSame('excludeRevoked=true', $params->getQueryString());
    }

    public function testIntParamIsRenderedWithoutEncoding(): void
    {
        $params = new GetTransactionHistoryQueryParams(['startDate' => 1700000000000]);

        $this->assertSame('startDate=1700000000000', $params->getQueryString());
    }

    public function testStringParamIsUrlEncoded(): void
    {
        $params = new GetTransactionHistoryQueryParams(['inAppOwnershipType' => 'FAMILY SHARED']);

        $this->assertSame('inAppOwnershipType=FAMILY%20SHARED', $params->getQueryString());
    }

    public function testMultipleParamsAreJoinedWithAmpersand(): void
    {
        $params = new GetTransactionHistoryQueryParams([
            'startDate' => 1700000000000,
            'endDate' => 1710000000000,
            'productId' => ['com.example.monthly'],
            'excludeRevoked' => true,
        ]);

        $this->assertSame(
            'startDate=1700000000000&endDate=1710000000000&productId=com.example.monthly&excludeRevoked=true',
            $params->getQueryString()
        );
    }
}
