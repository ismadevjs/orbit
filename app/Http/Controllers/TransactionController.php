<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class TransactionController extends Controller
{
    public function index()
    {
        return view('transactions.index');
    }




    public function fetch(Request $request)
    {
        $transactions = Transaction::query()->with('user')->where('user_id', Auth::id());

        return DataTables::of($transactions)
            ->addColumn('user', function ($transaction) {
                return $transaction->user->name ?? 'N/A';
            })
            ->editColumn('status', function ($transaction) {
                return ucfirst($transaction->status);
            })
            ->rawColumns(['user', 'status'])
            ->make(true);
    }


    public function fetchUserId($userId)
    {


        $transactions = Transaction::query()->with('user')->where('user_id', $userId);

        return DataTables::of($transactions)
            ->addColumn('user', function ($transaction) {
                return $transaction->user->name ?? 'N/A';
            })
            ->editColumn('status', function ($transaction) {
                return ucfirst($transaction->status);
            })
            ->rawColumns(['user', 'status'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'payment_method' => 'required|string|max:50',
            'amount' => 'required|numeric',
            'currency' => 'required|string|max:10',
            'status' => 'required|in:pending,completed,failed,refunded',
            'description' => 'nullable|string',
        ]);

        Transaction::create($validated);

        return response()->json(['message' => 'Transaction created successfully.']);
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return response()->json(['message' => 'Transaction deleted successfully.']);
    }
    public function accept(Request $request)
    {
        $transaction = Transaction::findOrFail($request->transaction_id);
    
        if ($transaction->status === 'pending') {
            $wallet = Wallet::where('user_id', $transaction->user_id)->first();
    
            if (!$wallet) {
                return response()->json(['message' => 'Wallet not found'], 404);
            }
    
            // Check if the transaction type is 'withdrawal'
            if (isset($transaction->details['type']) && $transaction->details['type'] === 'withdrawal') {
                // Remove the amount from pending_profit since the user received the payment
                $wallet->decrement('pending_profit', $transaction->details['total_cost']);
            } else {
                // Otherwise, update the capital and pending capital
                $wallet->increment('capital', $transaction->details['base_amount']);
                
                if ($wallet->pending_capital > 0) {
                    $wallet->decrement('pending_capital', $transaction->details['base_amount']);
                }
            }
    
            // Mark the transaction as completed
            $transaction->update(['status' => 'completed']);

            $data = [
                'user' => $transaction->user
            ];
            sendEmail($transaction->user->email, 'accept_pay', $data);
    
            return response()->json(['message' => 'Transaction accepted successfully'], 200);
        }
    
        return response()->json(['message' => 'Transaction is not pending'], 400);
    }
    
    

    public function declined(Request $request)
    {
        $transaction = Transaction::findOrFail($request->transaction_id);
        if ($transaction->status === 'pending') {
    
            $wallet = Wallet::where('user_id', $transaction->user_id)->first();
    
            if (isset($transaction->details['type']) && $transaction->details['type'] === 'withdrawal') {
                // Remove the amount from pending_profit since the user received the payment
                $wallet->decrement('pending_profit', $transaction->details['base_amount']);
                $wallet->increment('capital', $transaction->details['base_amount']);
            } else {
                // Decrement the amount from pending_capital if it exists and is greater than zero
                if ($wallet && $wallet->pending_capital > 0) {
                    $wallet->decrement('pending_capital', $transaction->details['base_amount']);
                }
            }
    
            $transaction->update(['status' => 'failed']);
            $data = [
                'user' => $transaction->user,
            ];

            sendEmail($transaction->user->email, 'reject_pay', $data);
            return response()->json(['message' => 'Transaction declined successfully'], 200);
        }
    
        return response()->json(['message' => 'Transaction is not pending'], 400);
    }
    
}
