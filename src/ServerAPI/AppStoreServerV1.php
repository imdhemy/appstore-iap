<?php

declare(strict_types=1);

namespace Imdhemy\AppStore\ServerAPI;

use Imdhemy\AppStore\Jws\Parser;
use Imdhemy\AppStore\ValueObjects\ConsumptionRequest;
use Imdhemy\AppStore\ValueObjects\JwsTransactionInfo;

/**
 * This class is used to build AppStoreServerV1 service and requests
 */
class AppStoreServerV1 extends AppStoreServer
{
    public function __construct($config)
    {
        parent::__construct($config, 'v1');
    }

    /*
    * Send Consumption Information
     *
    * https://developer.apple.com/documentation/appstoreserverapi/send-consumption-information
     *
    */
    public function sendConsumption($transactionId, ConsumptionRequest $consumption)
    {
        $response = $this->service()->get("transactions/consumption/$transactionId}", $consumption->toArray());

        return $this->parseResponse($response);
    }

    /*
    * Get a customer’s in-app purchases from a receipt using the order ID.
     *
     * When customers make one or more in-app purchases in your app, the App Store emails them a receipt.
     * The receipt contains an order ID. Use this order ID to call Look Up Order ID. Customers can also
     * retrieve their order IDs from their purchase history on the App Store; for more information,
     * see See your purchase history for the App Store, iTunes store, and more.
     *
    * https://developer.apple.com/documentation/appstoreserverapi/look-up-order-id
     *
    */
    public function order($orderId)
    {
        $response = $this->service()->get("lookup/$orderId");

        return $this->parseResponse($response);
    }

    /*
    * Get information about a single transaction for your app.
     *
    * https://developer.apple.com/documentation/appstoreserverapi/get-transaction-info
     *
    */
    public function transaction($transactionId): JwsTransactionInfo
    {
        $response = $this->service()->get("transactions/$transactionId");

        return new JwsTransactionInfo(Parser::toJws($this->parseResponse($response)['signedTransactionInfo']));
    }

    /*
    * Validate a transaction with the App Store Server
     *
    * Returns a JwsTransactionInfo
     *
    */
    public function validateTransaction($transactionId): JwsTransactionInfo
    {
        return $this->transaction($transactionId);
    }

    /*
     * Get the statuses for all of a customer’s auto-renewable subscriptions in your app.
     *
     * https://developer.apple.com/documentation/appstoreserverapi/get-all-subscription-statuses
     *
     * transactionId (Required) The identifier of a transaction that belongs to the customer, and which may be an original transaction identifier
     * $status (optional) An optional filter that indicates the status of subscriptions to include in the response. Your query may specify more than one status query parameter.
     */
    public function subscriptions($transactionId, ?int $status = null)
    {
        $params = [];

        if (! is_null($status)) {
            $params['status'] = $status;
        }

        $response = $this->service()->get("subscriptions/$transactionId", $params);

        return $this->parseResponse($response);
    }

    /*
     * Ask App Store Server to send a test notification to your server.
     */
    public function requestTestNotification(): string
    {
        $response = $this->service()->get('notifications/test');

        $content = $this->parseResponse($response);

        assert(is_array($content) && array_key_exists('testNotificationToken', $content));

        $token = $content['testNotificationToken'];

        assert(is_string($token));

        return $token;
    }
}
