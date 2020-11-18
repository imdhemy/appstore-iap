<?php


namespace Imdhemy\AppStore\Receipts\Contracts;

/**
 * Interface VerifierContract
 * @package Imdhemy\AppStore\Receipts\Contracts
 */
interface VerifierContract
{
    /**
     * @param bool $excludeOldTransactions
     * @return ReceiptResponseContract
     */
    public function verify(bool $excludeOldTransactions = false): ReceiptResponseContract;
}
