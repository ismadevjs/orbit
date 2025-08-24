<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CryptoPaymentController extends Controller
{
    // Show the payment page
    public function showPaymentPage()
    {
        return view('backend.crypto.payment');
    }

    // Verify a transaction
    public function verifyTransaction(Request $request)
    {
        $transactionHash = $request->input('transaction_hash');
        $walletAddress = '0x991782BB622B3572642941E5F6e2aA3c5F1925dA'; // Replace with your recipient wallet address
        $infuraApiKey = '672e3bfa35cc428c9cf2a56088d65be2'; // Replace with your Infura API Key

        // Fetch transaction details using Infura API
        $response = Http::post("https://mainnet.infura.io/v3/$infuraApiKey", [
            'jsonrpc' => '2.0',
            'method' => 'eth_getTransactionByHash',
            'params' => [$transactionHash],
            'id' => 1,
        ]);

        $transaction = $response->json();

        if ($transaction && $transaction['result']) {
            $toAddress = strtolower($transaction['result']['to']);
            $value = hexdec($transaction['result']['value']) / 10**18; // Convert value from wei to ETH

            if ($toAddress === strtolower($walletAddress) && $value > 0) {
                return response()->json(['status' => 'success', 'value' => $value]);
            }
        }

        return response()->json(['status' => 'error', 'message' => 'Invalid or unverified transaction.']);
    }
}
