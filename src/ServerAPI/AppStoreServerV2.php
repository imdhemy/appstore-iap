<?php

declare(strict_types=1);

namespace Imdhemy\AppStore\ServerAPI;

/**
 * This class is used to build AppStoreServerV2 requests
 */
class AppStoreServerV2 extends AppStoreServer
{
    public function __construct($config)
    {
        parent::__construct($config, 'v2');
    }

    /*
     * Get Transaction History
     * Get a customer’s in-app purchase transaction history for your app.
     *
     * https://developer.apple.com/documentation/appstoreserverapi/get-transaction-history
     */
    public function history($transactionId): mixed
    {
        $response = $this->service()->get("history/$transactionId");

        return $this->parseResponse($response);
    }
}
