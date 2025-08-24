<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Log;
class WalletController extends Controller
{
public function index(Request $request)
{

    if ($request->query('user_id')) {
        $wallet = Wallet::where('user_id', $request->query('user_id'))->first();
    } else {
        $wallet = Wallet::where('user_id', auth()->id())->first();
    }

    if ($request->expectsJson()) {
        return response()->json($wallet);
    }

    return view('backend.wallet.wallet', compact('wallet'));
}


}
