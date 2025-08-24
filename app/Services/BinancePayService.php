<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BinancePayService
{
    private $apiKey;
    private $apiSecret;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('BINANCE_API_KEY');
        $this->apiSecret = env('BINANCE_API_SECRET');
        $this->baseUrl = env('BINANCE_BASE_URL', 'https://bpay.binanceapi.com');
    }

    public function createOrder($orderId, $amount, $currency = 'USDT')
    {
        try {
            // Step 1: Generate timestamp and nonce
            $timestamp = (string) round(microtime(true) * 1000);
            $nonce = $this->generateNonce();

            // Step 2: Build payload
            $payload = [
                'merchantTradeNo' => $orderId,
                'orderAmount' => (float) $amount, // Ensure this is a float
                'currency' => $currency,
                'goods' => [
                    'goodsType' => '02', // Virtual Goods
                    'goodsCategory' => 'Z000', // Others
                    'referenceGoodsId' => $orderId,
                    'goodsName' => 'Payment for Order ' . $orderId,
                ],
            ];

            // Step 3: Encode payload to JSON
            $payloadJson = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

            // Step 4: Build the string to sign
            $stringToSign = $timestamp . "\n" . $nonce . "\n" . $payloadJson . "\n";

            // Step 5: Generate the signature
            $signature = strtoupper(hash_hmac('sha512', $stringToSign, $this->apiSecret));

            // Step 6: Log debugging info
            Log::info('Binance Pay Debug', [
                'Timestamp' => $timestamp,
                'Nonce' => $nonce,
                'String to Sign' => $stringToSign,
                'Generated Signature' => $signature,
                'Payload JSON' => $payloadJson
            ]);

            // Step 7: Make HTTP Request
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'BinancePay-Timestamp' => $timestamp,
                'BinancePay-Nonce' => $nonce,
                'BinancePay-Certificate-SN' => $this->apiKey,
                'BinancePay-Signature' => $signature,
            ])->withBody($payloadJson, 'application/json')
                ->post($this->baseUrl . '/binancepay/openapi/v2/order');

            $responseData = $response->json();
            Log::info('Binance Pay Response', $responseData);

            return $responseData;
        } catch (\Exception $e) {
            Log::error('Binance Pay Integration Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'status' => 'FAIL',
                'errorMessage' => $e->getMessage(),
            ];
        }
    }

    private function generateNonce()
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $nonce = '';
        for ($i = 0; $i < 32; $i++) {
            $nonce .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $nonce;
    }
}
