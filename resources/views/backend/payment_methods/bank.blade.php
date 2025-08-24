@extends('layouts.backend')

@section('content')

    @push('styles')
        <!-- Custom CSS -->
        <style>
            body {
                background: linear-gradient(to bottom, #00b09b, #96c93d);
                font-family: 'Poppins', sans-serif;
                color: #333;
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                margin: 0;
            }

            .checkout-container {
                width: 100%;
                max-width: 500px;
                border-radius: 12px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
                animation: fadeIn 0.8s ease-in-out;
                overflow: hidden;
                margin: auto; /* Center horizontally */
                background-color: #fff;
            }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-20px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .checkout-header {
                background: linear-gradient(135deg, #00b09b, #96c93d);
                color: #fff;
                text-align: center;
                padding: 20px;
            }

            .checkout-header h2 {
                margin: 0;
                font-size: 24px;
            }

            .checkout-header p {
                margin: 5px 0 0 0;
                font-size: 14px;
            }

            .checkout-body {
                padding: 30px;
                display: block;
            }

            .form-control {
                border-radius: 8px;
                border: 1px solid #ccc;
                padding: 10px;
            }

            .pay-btn {
                background: #00b09b;
                color: white;
                border: none;
                padding: 12px;
                border-radius: 8px;
                font-weight: bold;
                width: 100%;
                transition: background 0.3s, transform 0.2s;
                cursor: pointer;
            }

            .pay-btn:hover {
                background: #96c93d;
                transform: scale(1.02);
            }

            .success-message {
                display: none;
                text-align: center;
                padding: 50px 20px;
                animation: fadeIn 0.8s ease-in-out;
            }

            .checkmark {
                width: 80px;
                margin: 0 auto 20px;
            }

            .checkmark circle, .checkmark path {
                stroke: #28a745;
                stroke-width: 3;
                fill: none;
                stroke-dasharray: 100;
                stroke-dashoffset: 100;
                animation: draw 1s forwards ease-in-out;
            }

            @keyframes draw {
                to { stroke-dashoffset: 0; }
            }

            .success-message h2 {
                color: #28a745;
                margin-bottom: 10px;
            }

            .success-message p {
                color: #555;
                font-size: 16px;
            }

            .error-message {
                color: red;
                font-size: 14px;
                margin-top: 10px;
            }
        </style>
    @endpush

    @if(getTablesLimit('payment_methods',1))
        @php
            $card = getPayment('bank_transfer')
        @endphp
    @endif

    @push('scripts')
        
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const amountInput = document.getElementById("amount");
                const errorMessage = document.getElementById("error-message");
                const totalCostDisplay = document.getElementById("total-cost");
                const amountPay = document.getElementById("amount-pay");
                const finalCostInput = document.getElementById("final-cost");

                const taxRate = {{ $card->tax }};
                const minAmount = {{ $card->min }};
                const maxAmount = {{ $card->max }};

                function updateTotalCost() {
                    const amount = parseFloat(amountInput.value);
                    if (!isNaN(amount) && amount >= minAmount && amount <= maxAmount) {
                        const taxAmount = (amount * taxRate) / 100;
                        const totalCost = amount + taxAmount;
                        amountPay.value = totalCost.toFixed(2);
                        totalCostDisplay.innerText = `${totalCost.toFixed(2)} $`;
                        finalCostInput.value = totalCost.toFixed(2);
                        errorMessage.textContent = "";
                    } else if (amount > maxAmount) {
                        errorMessage.textContent = `المبلغ يجب أن يكون أقل من أو يساوي ${maxAmount} $.`;
                        totalCostDisplay.innerText = "---";
                        amountPay.value = "";
                        finalCostInput.value = "";
                    } else if (amount < minAmount) {
                        errorMessage.textContent = `المبلغ يجب أن يكون أكبر من أو يساوي ${minAmount} $.`;
                        totalCostDisplay.innerText = "---";
                        amountPay.value = "";
                        finalCostInput.value = "";
                    } else {
                        totalCostDisplay.innerText = "---";
                        errorMessage.textContent = "";
                        amountPay.value = "";
                        finalCostInput.value = "";
                    }
                }

                amountInput.addEventListener("input", updateTotalCost);

                @if(session('success'))
                // Hide the form and show success message
                document.querySelector('.checkout-body').style.display = 'none';
                document.querySelector('.checkout-header').style.display = 'none';
                document.querySelector('.success-message').style.display = 'block';
                @endif
            });
        </script>
    @endpush

    <div class="checkout-container">
        <!-- Header -->
        <div class="checkout-header">
            <h2>الدفع الآمن</h2>
            <p>أكمل عملية الدفع أدناه</p>
        </div>

        <!-- Body -->
        <div class="checkout-body">
            <form id="payment-form" action="{{ route('process.saveBankTransfer') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <span><strong>الطريقة:</strong> {{ $card->name }}</span>
                        </div>
                        <div class="col-md-6">
                            <span><strong>متوسط الوقت:</strong> {{ $card->time ?? 'غير متوفر' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span><strong>الحد الأدنى:</strong> {{ currency($card->min) }}</span>
                        </div>
                        <div class="col-md-6">
                            <span><strong>الحد الأقصى:</strong> {{ currency($card->max) }}</span>
                        </div>
                        <div class="col-md-6">
                            <span><strong>الاقتطاعات:</strong> {{ $card->tax }}%</span>
                        </div>
                        <div class="col-md-12">
                            <span><strong>مجموع التكاليف:</strong> <span id="total-cost" class="badge bg-success">---</span></span>
                        </div>

                       @if (json_decode($card->data, true))
                       @foreach ( json_decode($card->data, true) as $vals)
                            <div class="col-12 mt-2">
                                <div class="card">
                                    <div class="card-content bg-success p-2">
                                        {{ $vals['data_name'] }} : {{ $vals['data_value'] }}
                                        
                                    </div>
                                </div>
                            </div>
                        @endforeach
                       @endif
                    </div>
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">المبلغ ($)</label>
                    <input type="number" id="amount" name="amount" class="form-control" placeholder="أدخل المبلغ" required>
                    <div id="error-message" class="error-message"></div>
                </div>

                <div class="mb-3">
                    <label for="amount-pay" class="form-label">المبلغ المطلوب دفعه ($)</label>
                    <input type="text" id="amount-pay" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label for="bank_name" class="form-label">اسم البنك</label>
                    <input type="text" id="bank_name" name="bank_name" class="form-control" placeholder="أدخل اسم البنك" required>
                </div>

                <div class="mb-3">
                    <label for="account_number" class="form-label">رقم الحساب</label>
                    <input type="text" id="account_number" name="account_number" class="form-control" placeholder="أدخل رقم الحساب" required>
                </div>

                <div class="mb-3">
                    <label for="transaction_reference" class="form-label">رقم المعاملة</label>
                    <input type="text" id="transaction_reference" name="transaction_reference" class="form-control" placeholder="أدخل رقم المعاملة" required>
                </div>

                <div class="mb-3">
                    <label for="proof_of_payment" class="form-label">إثبات الدفع</label>
                    <input type="file" id="proof_of_payment" name="proof_of_payment" class="form-control" accept="image/*,application/pdf" required>
                </div>

                <div class="mb-3">
                    <label for="final-cost" class="form-label">المبلغ النهائي للدفع ($)</label>
                    <input type="text" id="final-cost" class="form-control" readonly>
                </div>

                <button type="submit" class="btn pay-btn mt-3">إرسال الدفع</button>
            </form>
        </div>

        <!-- Success Message -->
        <div class="success-message">
            <svg class="checkmark" viewBox="0 0 52 52">
                <circle cx="26" cy="26" r="25"></circle>
                <path d="M14 27l7 7 15-15"></path>
            </svg>
            <h2>تم الدفع بنجاح!</h2>
            <p>شكرًا لك على الدفع.</p>
            <a href="{{ route('investor.wallet.index') }}" class="btn btn-primary">
                <i class="fas fa-wallet"></i> المحفظة
            </a>

        </div>
    </div>

@endsection
