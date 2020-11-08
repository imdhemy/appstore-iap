<?php


namespace Imdhemy\AppStore\Receipts;

use Imdhemy\AppStore\Receipts\Contracts\ReceiptResponseContract;
use Psr\Http\Message\ResponseInterface;

class ReceiptResponse implements ReceiptResponseContract
{
    /**
     * ReceiptResponse constructor.
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
    }
}
