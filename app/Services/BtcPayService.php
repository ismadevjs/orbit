<?php

namespace App\Services;

use BTCPayServer\Client\Invoice;
use BTCPayServer\Util\PreciseNumber;

class BtcPayService
{
    protected $client;
    protected $storeId;

    public function __construct()
    {
        $serverUrl = config('btcpay.server_url');
        $apiKey    = config('btcpay.api_key');
        $this->storeId = config('btcpay.store_id');

        $this->client = new Invoice($serverUrl, $apiKey);
    }

    public function createInvoice(float $amount, string $currency = 'USD', array $metadata = [])
    {
        // Convert the amount to a PreciseNumber object
        $amountPrecise = PreciseNumber::fromFloat($amount);

        return $this->client->createInvoice(
            $this->storeId,       // Store ID
            $currency,            // Currency code
            $amountPrecise,       // Amount as PreciseNumber
            null,                 // Optional Order ID
            null,                 // Buyer email
            $metadata             // Optional metadata
        );
    }
}
