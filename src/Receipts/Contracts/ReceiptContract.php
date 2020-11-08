<?php


namespace Imdhemy\AppStore\Receipts\Contracts;

/**
 * Interface ReceiptContract
 * @package Imdhemy\AppStore\Receipts\Contracts
 */
interface ReceiptContract
{
    /**
     * @param bool $excludeOldTransactions
     * @return ReceiptResponseContract
     */
    public function verify(bool $excludeOldTransactions = false): ReceiptResponseContract;
}
