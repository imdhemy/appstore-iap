<?php

namespace Imdhemy\AppStore\Tests\Unit\ValueObjects;

use Imdhemy\AppStore\Jws\Parser;
use Imdhemy\AppStore\Tests\TestCase;
use Imdhemy\AppStore\ValueObjects\JwsTransactionInfo;

class JwsTransactionInfoTest extends TestCase
{
    /**
     * @test
     * @return JwsTransactionInfo
     */
    public function jws_transaction_info_is_a_jws(): JwsTransactionInfo
    {
        $jws = Parser::toJws($this->faker->jwsOf([]));

        $sut = new JwsTransactionInfo($jws);

        $this->assertEquals($jws->__toString(), $sut->__toString());
        $this->assertEquals($jws->getClaims(), $sut->getClaims());
        $this->assertEquals($jws->getHeaders(), $sut->getHeaders());
        $this->assertEquals($jws->getSignature(), $sut->getSignature());

        return $sut;
    }

    /**
     * @test
     * @depends jws_transaction_info_is_a_jws
     */
    public function all_props_are_nullable(JwsTransactionInfo $sut): void
    {
        $props = [
            'appAccountToken',
            'bundleId',
            'environment',
            'expiresDate',
            'inAppOwnershipType',
            'isUpgraded',
            'offerIdentifier',
            'offerType',
            'originalPurchaseDate',
            'originalTransactionId',
            'productId',
            'purchaseDate',
            'quantity',
            'revocationDate',
            'revocationReason',
            'signedDate',
            'subscriptionGroupIdentifier',
            'transactionId',
            'type',
            'webOrderLineItemId',
        ];

        foreach ($props as $prop) {
            $getter = 'get' . ucfirst($prop);
            $this->assertNull($sut->$getter());
        }
    }
}
