<?php

namespace App\Http\Controllers;

use App\Models\Investor;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the payment methods.
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::all();

        // Decode JSON fields for display
        foreach ($paymentMethods as $method) {
            $method->data = $this->safeJsonDecode($method->data);
            $method->fields = $this->safeJsonDecode($method->fields);
        }

        return view('backend.payment_methods.payment_methods', compact('paymentMethods'));
    }

    /**
     * Store a newly created or update an existing payment method.
     */
    public function store(Request $request)
    {

        $rules = [
            'name' => 'required|string|max:255',
            'provider' => 'required|string|max:255',
            'time' => 'nullable|string|max:255',
            'tax' => 'required|numeric|min:0',
            'min' => 'required|numeric|min:0',
            'max' => 'required|numeric|min:0',
            'status' => 'sometimes|string',
            'type' => 'sometimes|string',
            'data_name' => 'nullable|array',
            'data_value' => 'nullable|array',
            'field_name' => 'nullable|array',
            'field_type' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $paymentMethod = $request->id
            ? PaymentMethod::findOrFail($request->id)
            : new PaymentMethod();

        $paymentMethod->name = $request->name;
        $paymentMethod->provider = $request->provider;
        $paymentMethod->time = $request->time;
        $paymentMethod->tax = $request->tax;
        $paymentMethod->min = $request->min;
        $paymentMethod->max = $request->max;
        $paymentMethod->status = $request->input('status') === 'on';
        $paymentMethod->type = $request->input('type') === 'on';

        // Handle Data
        $data = [];
        if ($request->filled('data_name') && $request->filled('data_value')) {
            foreach ($request->data_name as $key => $name) {
                if (!empty($name)) {
                    $data[] = [
                        'data_name' => $name,
                        'data_value' => $request->data_value[$key] ?? '',
                    ];
                }
            }
        }
        $paymentMethod->data = $data; // Assign array directly

        // Handle Fields
        $fields = [];
        if ($request->filled('field_name') && $request->filled('field_type')) {
            foreach ($request->field_name as $key => $fieldName) {
                if (!empty($fieldName)) {
                    $fields[] = [
                        'field_name' => $fieldName,
                        'field_type' => $request->field_type[$key] ?? 'text',
                    ];
                }
            }
        }
        $paymentMethod->fields = $fields; // Assign array directly



        // Handle Image Upload
        if ($request->hasFile('image')) {
            if ($paymentMethod->image && Storage::disk('public')->exists($paymentMethod->image)) {
                Storage::disk('public')->delete($paymentMethod->image);
            }

            $imagePath = $request->file('image')->store('payment-methods', 'public');
            $paymentMethod->image = $imagePath;
        }

        $paymentMethod->save();

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ البطاقة بنجاح.',
            'payment_method' => $paymentMethod,
        ]);
    }

    /**
     * Remove the specified payment method from storage.
     */
    public function destroy($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        if ($paymentMethod->image && Storage::disk('public')->exists($paymentMethod->image)) {
            Storage::disk('public')->delete($paymentMethod->image);
        }

        $paymentMethod->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف البطاقة بنجاح.',
        ]);
    }

    /**
     * Safe JSON decoding to handle errors gracefully.
     */
    private function safeJsonDecode($json, $assoc = true)
    {
        if (is_string($json) && !empty($json)) {
            $decoded = json_decode($json, $assoc);
            return json_last_error() === JSON_ERROR_NONE ? $decoded : [];
        }

        return [];
    }

    // payment methods


    public function checkout()
    {
        return view('backend.payment_methods.checkout');
    }

    public function processPayment(Request $request)
    {
        // التحقق من المدخلات
        $request->validate([
            'amount' => 'required|numeric|min:1|max:999999.99',
        ], [
            'amount.max' => 'المبلغ يجب أن لا يتجاوز $999,999.99',
        ]);

        // استدعاء إعدادات Stripe
        Stripe::setApiKey(config('services.stripe.secret'));

        // الحصول على المستخدم المُصادق عليه
        $user = Auth::user();

        // إعداد الاقتطاعات والضرائب
        $taxRate = getPayment('stripe')->tax ?? 0; // نسبة الضريبة
        $baseAmount = $request->amount; // المبلغ الذي أدخله المستخدم
        $taxAmount = ($baseAmount * $taxRate) / 100;
        $totalCost = $baseAmount + $taxAmount; // المبلغ الكلي مع الضريبة

        try {
            // إنشاء عملية الدفع على أساس المبلغ الكلي
            $paymentIntent = PaymentIntent::create([
                'amount' => round($totalCost * 100), // تحويل إلى السنتات
                'currency' => 'usd',
                'metadata' => [
                    'customer_id' => $user->id,
                    'customer_name' => $user->name,
                    'customer_email' => $user->email,
                    'customer_phone' => $user->phone,
                    'customer_country' => $user->country,
                    'base_amount' => $baseAmount,
                    'tax_amount' => $taxAmount,
                    'total_cost' => $totalCost,
                ],
            ]);

            if ($paymentIntent->status == 'succeeded') {

                // حفظ بيانات المعاملة في جدول transactions
                $saved = saveTransaction(
                    userId: $user->id,
                    paymentMethod: 'stripe',
                    amount: $totalCost,
                    currency: 'usd',
                    transactionReference: $paymentIntent->id,
                    status: 'completed', // ستكون المعاملة "معلقة" إلى أن يتم تأكيدها
                    description: 'Stripe payment for user ' . $user->id,
                    details: [
                        'type' => 'deposit',
                        'type_payment' => 'stripe',
                        'total_cost' => $totalCost,
                        'tax_amount' => $taxAmount,
                        'base_amount' => $baseAmount,
                    ],
                    paymentAccount: $user->email,
                    paymentGatewayFee: null, // يمكنك تخصيص رسوم Stripe هنا إذا لزم الأمر
                    isTest: config('app.env') === 'local', // تحقق من بيئة التطبيق
                    ipAddress: $request->ip(),
                    amoutWithoutTax: $baseAmount
                );


                if (!$saved) {
                    throw new \Exception('Failed to save transaction');
                }

            }
            // إرجاع البيانات إلى الواجهة الأمامية
            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'country' => $user->country,
                'totalCost' => number_format($totalCost, 2),
            ]);

        } catch (\Exception $e) {
            // تسجيل الخطأ وإرجاع استجابة فاشلة
            \Log::error('Stripe Payment Error: ' . $e->getMessage());

            return response()->json([
                'message' => 'There was an error processing your payment. Please try again later.',
            ], 500);
        }
    }



    public function transactions()
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $transactions = \Stripe\PaymentIntent::all(['limit' => 10]);

        return view('backend.transactions', ['transactions' => $transactions->data]);
    }

    public function bankCheckout()
    {
        return view('backend.payment_methods.bank');
    }


    public function saveBankTransfer(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1|max:999999.99',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'transaction_reference' => 'required|string|max:255',
            'proof_of_payment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:8192',
        ], [
            'proof_of_payment.mimes' => 'The proof of payment must be a file of type: jpg, jpeg, png, or pdf.',
            'proof_of_payment.max' => 'The proof of payment must not exceed 2MB.',
        ]);

        try {
            // Store the proof of payment file
            $proofPath = $request->file('proof_of_payment')->store('proofs', 'public');

            // Get the authenticated user
            $user = Auth::user();

            // Calculate details
            $baseAmount = $request->amount;
            $taxRate = getPayment('bank_transfer')->tax ?? 0;
            $taxAmount = ($baseAmount * $taxRate) / 100;
            $totalCost = $baseAmount + $taxAmount;
            // Use the helper function to save the transaction
            $saved = saveTransaction(
                userId: $user->id,
                paymentMethod: 'bank_transfer',
                amount: $totalCost,
                currency: 'usd',
                transactionReference: $request->transaction_reference,
                status: 'pending', // Mark as pending for admin confirmation
                description: 'Bank transfer payment for user ' . $user->id,
                details: [
                    'type' => 'deposit',
                    'method_type' => 'bank_transfer',
                    'type_payment' => 'bank_transfer',
                    'proof_of_payment' => $proofPath,
                    'total_cost' => $totalCost,
                    'tax_amount' => $taxAmount,
                    'base_amount' => $baseAmount,
                    'bank_name' => $request->bank_name,
                    'account_number' => $request->account_number,
                ],
                paymentAccount: $user->email,
                paymentGatewayFee: null,
                isTest: config('app.env') === 'local',
                ipAddress: $request->ip(),
                amoutWithoutTax: $request->amount
            );

            if (!$saved) {
                throw new \Exception('Failed to save the transaction');
            }

            return back()->withSuccess('Your bank transfer has been successfully submitted. Please wait for confirmation.');
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while processing your request. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    // approve payments for cash and bank or other payment methods that need an approvements

    public function approvePayment(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
        ]);

        try {
            // Begin a database transaction
            DB::beginTransaction();

            // Fetch the transaction using DB::table
            $transaction = DB::table('transactions')->where('id', $request->transaction_id)->first();

            // Check if the transaction exists and is pending
            if (!$transaction) {
                throw new \Exception('Transaction not found.');
            }
            if ($transaction->status !== 'pending') {
                throw new \Exception('Only pending transactions can be approved.');
            }

            // Fetch the investor using DB::table
            $investor = DB::table('investors')->where('user_id', $transaction->user_id)->first();

            if (!$investor) {
                throw new \Exception('Investor not found for this transaction.');
            }

            // Update the transaction status to "approved"
            DB::table('transactions')->where('id', $transaction->id)->update([
                'status' => 'approved',
                'updated_at' => now(), // Update the timestamp
            ]);

            // Add the transaction amount to the investor's capital
            DB::table('investors')->where('user_id', $transaction->user_id)->update([
                'capital' => $investor->capital + $transaction->amount,
                'updated_at' => now(), // Update the timestamp
            ]);

            // Commit the transaction
            DB::commit();

            return back()->withSuccess('The payment has been successfully approved.');
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();

            \Log::error('Payment Approval Error: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while approving the payment. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }




    public function cryptoCheckout()
    {
        return view('backend.payment_methods.crypto');
    }

    public function cryptoCheckoutWithdrawal()
    {
        return view('backend.payment_methods.crypto_withdrawal');
    }

    public function saveCryptoTransferWithdrawal(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1|max:999999.99',
            'crypto_type' => 'required|string|max:255',
            'network' => 'required|string|max:255',
            'wallet_address' => 'required|string|max:255',
        ]);

        try {
            // Get the authenticated user
            $user = Auth::user();
            $wallet = $user->wallet;

            if (!$wallet) {
                return back()->withErrors('لم يتم العثور على محفظة للمستخدم.');
            }

            // Check if the user's wallet profit is sufficient
            if ($wallet->profit <= 0) {
                return back()->withErrors('محفظتك لا تحتوي على ربح كافٍ لإجراء هذه المعاملة.');
            }

            // Check if the withdrawal amount is greater than the user's available wallet profit
            $withdrawalAmount = $request->amount;
            if ($withdrawalAmount > $wallet->profit) {
                return back()->withErrors('المبلغ الذي ترغب في سحبه أكبر من ربحك المتاح في المحفظة.');
            }

            $name = $request->name;
            // Calculate details
            $baseAmount = $request->amount;
            $taxRate = getPayment('cash')->tax ?? 0;
            $taxAmount = ($baseAmount * $taxRate) / 100;
            $totalCost = $baseAmount + $taxAmount;

            // Move the amount from profit to pending_profit
            $wallet->decrement('profit', $totalCost);
            $wallet->increment('pending_profit', $totalCost);

            // Save the transaction
            $saved = saveTransaction(
                userId: $user->id,
                paymentMethod: 'crypto_withdrawal',
                amount: $totalCost,
                currency: 'usdt',
                transactionReference: $request->transaction_reference,
                status: 'pending', // Mark as pending for admin confirmation
                description: 'دفع عملة رقمية للمستخدم ' . $user->id,
                details: [
                    'type' => 'withdrawal',
                    'type_payment' => 'crypto_withdrawal',
                    'name' => $name,
                    'method_type' => 'crypto_withdrawal',
                    'total_cost' => $totalCost,
                    'tax_amount' => $taxAmount,
                    'base_amount' => $baseAmount,
                ],
                paymentAccount: $user->email,
                ipAddress: $request->ip(),
                amoutWithoutTax: $baseAmount
            );

            if (!$saved) {
                // Revert changes in case of failure
                $wallet->increment('profit', $totalCost);
                $wallet->decrement('pending_profit', $totalCost);
                throw new \Exception('فشل في حفظ المعاملة');
            }

            return back()->withSuccess('تم تقديم سحب الأموال بنجاح. يرجى الانتظار للتأكيد.');
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ أثناء معالجة طلبك. يرجى المحاولة لاحقًا.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    

    public function saveCryptoTransfer(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'crypto_network' => 'required|string|max:255',
            'crypto_address' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1|max:999999.99',
            'transaction_hash' => 'nullable|string|max:255',
            'proof_of_payment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:8192',
        ], [
            'crypto_network.required' => 'يرجى اختيار شبكة العملات الرقمية.',
            'crypto_address.required' => 'يرجى اختيار عنوان المحفظة.',
            'amount.required' => 'يرجى إدخال المبلغ.',
            'amount.numeric' => 'يجب أن يكون المبلغ رقميًا.',
            'amount.min' => 'يجب أن يكون المبلغ على الأقل 1 دولار.',
            'amount.max' => 'يجب ألا يتجاوز المبلغ 999999.99 دولار.',
            'proof_of_payment.required' => 'يرجى تحميل إثبات الدفع.',
            'proof_of_payment.mimes' => 'يجب أن يكون إثبات الدفع ملفًا من نوع: jpg، jpeg، png، أو pdf.',
            'proof_of_payment.max' => 'يجب ألا يتجاوز إثبات الدفع 2MB.',
        ]);

        try {
            // Fetch the crypto_transfer payment method details
            $crypto = getPayment('crypto_transfer');

            // Calculate tax and total amount
            $baseAmount = $request->input('amount');
            $taxRate = $crypto->tax ?? 0; // Ensure taxRate is defined
            $taxAmount = ($baseAmount * $taxRate) / 100;
            $totalCost = $baseAmount + $taxAmount;

            // Store the proof of payment file in the 'proofs' directory within 'public' disk
            $proofPath = $request->file('proof_of_payment')->store('proofs', 'public');

            // Get the authenticated user
            $user = Auth::user();

            // Save the transaction using the helper function
            $saved = saveTransaction(
                userId: $user->id,
                paymentMethod: 'crypto',
                amount: $totalCost,
                currency: 'usd',
                transactionReference: $request->input('transaction_hash'),
                status: 'pending', // Mark as pending for admin confirmation
                description: 'Crypto transfer payment for user ' . $user->id,
                details: [
                    'method_type' => 'crypto',
                    'type_payment' => 'crypto_deposit',
                    'proof_of_payment' => $proofPath,
                    'total_cost' => $totalCost,
                    'tax_amount' => $taxAmount,
                    'base_amount' => $baseAmount,
                    'crypto_network' => $request->input('crypto_network'),
                    'crypto_address' => $request->input('crypto_address'),
                ],
                paymentAccount: $user->email,
                paymentGatewayFee: null,
                isTest: config('app.env') === 'local',
                ipAddress: $request->ip(),
                amoutWithoutTax: $baseAmount
            );

            // Check if the transaction was saved successfully
            if (!$saved) {
                throw new \Exception('فشل في حفظ المعاملة');
            }

            // Optionally, you can store the proof path in the transaction details or elsewhere
            // For example:
            // $saved->details['proof_path'] = $proofPath;
            // $saved->save();

            // Redirect back with a success message
            return back()->withSuccess('تم تقديم تحويل العملات الرقمية بنجاح. يرجى الانتظار للتأكيد.');

        } catch (\Exception $e) {
            // Log the exception if necessary
            \Log::error('Crypto Transfer Error: ' . $e->getMessage());

            // Redirect back with an error message
            return back()->withErrors(['error' => 'حدث خطأ أثناء معالجة طلبك. يرجى المحاولة مرة أخرى لاحقًا.']);
        }
    }

    public function cashCheckout()
    {
        return view('backend.payment_methods.cash');
    }


    public function saveCashTransfer(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1|max:999999.99',
            'transaction_reference' => 'required|string|max:255',
            'proof_of_payment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:8192',
        ], [
            'proof_of_payment.mimes' => 'The proof of payment must be a file of type: jpg, jpeg, png, or pdf.',
            'proof_of_payment.max' => 'The proof of payment must not exceed 2MB.',
        ]);

        try {
            // Store the proof of payment file
            $proofPath = $request->file('proof_of_payment')->store('proofs', 'public');

            // Get the authenticated user
            $user = Auth::user();

            // Calculate details
            $baseAmount = $request->amount;
            $taxRate = getPayment('cash')->tax ?? 0;
            $taxAmount = ($baseAmount * $taxRate) / 100;
            $totalCost = $baseAmount + $taxAmount;

            // Use the helper function to save the transaction
            $saved = saveTransaction(
                userId: $user->id,
                paymentMethod: 'cash',
                amount: $totalCost,
                currency: 'usd',
                transactionReference: $request->transaction_reference,
                status: 'pending', // Mark as pending for admin confirmation
                description: 'Bank transfer payment for user ' . $user->id,
                details: [
                    'method_type' => 'cash',
                    'type_payment' => 'cash_deposit',
                    'proof_of_payment' => $proofPath,
                    'total_cost' => $totalCost,
                    'tax_amount' => $taxAmount,
                    'base_amount' => $baseAmount,
                ],
                paymentAccount: $user->email,
                paymentGatewayFee: null,
                isTest: config('app.env') === 'local',
                ipAddress: $request->ip(),
                amoutWithoutTax: $baseAmount
            );

            if (!$saved) {
                throw new \Exception('Failed to save the transaction');
            }

            return back()->withSuccess('Your cash transfer has been successfully submitted. Please wait for confirmation.');
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while processing your request. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function cashCheckoutWithdrawal()
    {
        return view('backend.payment_methods.cash_withdrawal');
    }


    public function saveCashTransferWithdrawal(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1|max:999999.99',
        ]);

        try {
            // Get the authenticated user
            $user = Auth::user();
            $wallet = $user->wallet;

            if (!$wallet) {
                return back()->withErrors('لم يتم العثور على محفظة للمستخدم.');
            }

            // Check if the user's wallet profit is sufficient
            if ($wallet->profit <= 0) {
                return back()->withErrors('محفظتك لا تحتوي على ربح كافٍ لإجراء هذه المعاملة.');
            }

            // Check if the withdrawal amount is greater than the user's available wallet profit
            $withdrawalAmount = $request->amount;
            if ($withdrawalAmount > $wallet->profit) {
                return back()->withErrors('المبلغ الذي ترغب في سحبه أكبر من ربحك المتاح في المحفظة.');
            }

            $name = $request->name;
            // Calculate details
            $baseAmount = $request->amount;
            $taxRate = getPayment('cash')->tax ?? 0;
            $taxAmount = ($baseAmount * $taxRate) / 100;
            $totalCost = $baseAmount + $taxAmount;

            // Move the amount from profit to pending_profit
            $wallet->decrement('profit', $totalCost);
            $wallet->increment('pending_profit', $totalCost);

            // Save the transaction
            $saved = saveTransaction(
                userId: $user->id,
                paymentMethod: 'cash_withdrawal',
                amount: $totalCost,
                currency: 'usd',
                transactionReference: $request->transaction_reference,
                status: 'pending', // Mark as pending for admin confirmation
                description: 'دفع سحب نقدي للمستخدم ' . $user->id,
                details: [
                    'type' => 'withdrawal',
                    'type_payment' => 'cash_withdrawal',
                    'name' => $name,
                    'method_type' => 'bank_transfer_withdrawal',
                    'total_cost' => $totalCost,
                    'tax_amount' => $taxAmount,
                    'base_amount' => $baseAmount,
                ],
                paymentAccount: $user->email,
                paymentGatewayFee: null,
                isTest: config('app.env') === 'local',
                ipAddress: $request->ip(),
                amoutWithoutTax: $baseAmount
            );

            if (!$saved) {
                // Revert changes in case of failure
                $wallet->increment('profit', $totalCost);
                $wallet->decrement('pending_profit', $totalCost);
                throw new \Exception('فشل في حفظ المعاملة');
            }

            return back()->withSuccess('تم تقديم سحب الأموال بنجاح. يرجى الانتظار للتأكيد.');
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ أثناء معالجة طلبك. يرجى المحاولة لاحقًا.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function bankCheckoutWithdrawal()
    {
        return view('backend.payment_methods.bank_withdrawal');
    }


    public function saveBankTransferWithdrawal(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1|max:999999.99',
            'iban' => 'required|string|max:34',
            'swift' => 'required|string|max:11',
            'bank_name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        try {
            // Get the authenticated user
            $user = Auth::user();
            // Check if the user's wallet profit is zero or negative
            if ($user->wallet->profit <= 0) {
                return back()->withErrors('محفظتك لا تحتوي على ربح كافٍ لإجراء هذه المعاملة.');
            }

            // Check if the withdrawal amount is greater than the user's available wallet profit
            $withdrawalAmount = $request->amount;
            if ($withdrawalAmount > $user->wallet->profit) {
                return back()->withErrors('المبلغ الذي ترغب في سحبه أكبر من ربحك المتاح في المحفظة.');
            }

            $name = $request->name;
            $iban = $request->iban;
            $swift = $request->swift;
            $bank_name = $request->bank_name;
            $country = $request->country;

            // Calculate details
            $baseAmount = $withdrawalAmount;
            $taxRate = getPayment('cash')->tax ?? 0;
            $taxAmount = ($baseAmount * $taxRate) / 100;
            $totalCost = $baseAmount + $taxAmount;

            // Update wallet balances
            $wallet = $user->wallet;
            $wallet->profit -= $withdrawalAmount; // Deduct from profit
            $wallet->pending_profit += $withdrawalAmount; // Add to pending profit
            $wallet->save(); // Save the changes

            // Use the helper function to save the transaction
            $saved = saveTransaction(
                userId: $user->id,
                paymentMethod: 'bank_withdrawal',
                amount: $totalCost,
                currency: 'usd',
                transactionReference: $request->transaction_reference,
                status: 'pending',
                description: 'دفع البنك للمستخدم ' . $user->id,
                details: [
                    'type' => 'withdrawal',
                    'type_payment' => 'bank_withdrawal',
                    'name' => $name,
                    'iban' => $iban,
                    'swift' => $swift,
                    'bank_name' => $bank_name,
                    'country' => $country,
                    'method_type' => 'bank_transfer_withdrawal',
                    'total_cost' => $totalCost,
                    'tax_amount' => $taxAmount,
                    'base_amount' => $baseAmount,
                ],
                paymentAccount: $user->email,
                paymentGatewayFee: null,
                isTest: config('app.env') === 'local',
                ipAddress: $request->ip(),
                amoutWithoutTax: $baseAmount
            );

            if (!$saved) {
                throw new \Exception('فشل في حفظ المعاملة');
            }

            return back()->withSuccess('تم تقديم سحب الأموال بنجاح. يرجى الانتظار للتأكيد.');
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ أثناء معالجة طلبك. يرجى المحاولة لاحقًا.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function generateQr(Request $request)
    {
        $address = $request->input('address');
        if (!$address) {
            return response()->json(['error' => 'No address provided'], 400);
        }

        $qrCode = QrCode::size(150)->generate($address);
        return response($qrCode)->header('Content-Type', 'image/svg+xml');
    }
}
