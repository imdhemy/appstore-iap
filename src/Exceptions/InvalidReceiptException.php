<?php


namespace Imdhemy\AppStore\Exceptions;

use Exception;

/**
 * Class InvalidReceiptException
 * @package Imdhemy\AppStore\Exceptions
 */
class InvalidReceiptException extends Exception
{
    const STATUS_DOCS = 'https://developer.apple.com/documentation/appstorereceipts/status';

    /**
     * @param int $status
     * @return static
     */
    public static function create(int $status): self
    {
        $msg = sprintf(
            "Invalid receipt. The status of the app receipt is [%s]. Check %s form more information.",
            $status,
            self::STATUS_DOCS
        );

        return new self($msg, $status);
    }
}
