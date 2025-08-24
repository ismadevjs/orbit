<?php

namespace App\Http\Controllers;

use App\Services\BtcPayService;
use Illuminate\Http\Request;

class BtcPayController extends Controller
{
    protected $btcpay;

    public function __construct(BtcPayService $btcpay)
    {
        $this->btcpay = $btcpay;
    }

    public function createInvoice(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.0001',
            'name'   => 'required|string',
        ]);

        try {
            $invoice = $this->btcpay->createInvoice(
                (float) $request->input('amount'), // Amount as float
                'USD',                            // Currency
                ['buyerName' => $request->input('name')] // Metadata
            );

            return response()->json([
                'message' => 'Invoice created successfully',
                'url'     => $invoice->getCheckoutLink(), // Checkout URL
                'id'      => $invoice->getId(),           // Invoice ID
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
