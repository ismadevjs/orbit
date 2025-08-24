@extends('layouts.backend')

@section('content')

@push('styles')
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
            margin: auto;
            background: linear-gradient(to bottom, #fff, #f7f1ea);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .checkout-header {
            background: linear-gradient(to bottom, #00b09b, #96c93d);
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        .checkout-body {
            padding: 30px;
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

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }

        .note-container {
            background: rgba(255, 255, 255, 0.2);
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
            text-align: right;
        }

        .note-container p {
            font-weight: bold;
            color: #000;
        }

        .note-container ul {
            list-style: none;
            padding: 0;
        }

        .note-container li {
            color: #333;
            padding: 5px 0;
        }
    </style>
@endpush

@if(getTablesLimit('payment_methods', 1))
    @php
        $card = getPayment('cash')
    @endphp
@endif

@if($card->type)
    <div class="checkout-container">
        <div class="checkout-header">
            <h2>السحب عبر البنك</h2>
            <p>أكمل عملية السحب أدناه</p>
        </div>

        <div class="checkout-body">
            <div class="note-container">
                <p>ملاحظة :</p>
                <ul>
                    <li>في السحب عبر البنك، نحن لا نتعامل مع طرف ثالث، يرجى التأكد أن الحساب هو حسابك الخاص 
                    </li>
                </ul>
            </div>

            <form id="payment-form" action="{{ route('process.saveBankTransfer.withdrawal') }}" method="post">
                @csrf
                
                <div class="mb-3">
                    <label for="name" class="form-label">الاسم الكامل</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="أدخل اسمك الكامل" required>
                </div>

                <div class="mb-3">
                    <label for="iban" class="form-label">IBAN</label>
                    <input type="text" id="iban" name="iban" class="form-control" placeholder="أدخل رقم الـ IBAN" required>
                </div>

                <div class="mb-3">
                    <label for="country" class="form-label">الدولة</label>
                    <input type="text" id="country" name="country" class="form-control" placeholder="أدخل اسم دولتك" required>
                </div>

                <div class="mb-3">
                    <label for="bank_name" class="form-label">اسم البنك</label>
                    <input type="text" id="bank_name" name="bank_name" class="form-control" placeholder="أدخل اسم البنك" required>
                </div>

                <div class="mb-3">
                    <label for="swift" class="form-label">SWIFT</label>
                    <input type="text" id="swift" name="swift" class="form-control" placeholder="أدخل رمز SWIFT للبنك" required>
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">المبلغ ($)</label>
                    <input type="number" id="amount" name="amount" class="form-control" placeholder="أدخل المبلغ" required>
                    <div id="error-message" class="error-message"></div>
                </div>

                <button type="submit" class="btn pay-btn mt-3">إرسال طلب السحب</button>
            </form>
        </div>
    </div>
@endif

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const amountInput = document.getElementById("amount");
            const errorMessage = document.getElementById("error-message");
            const minAmount = {{ $card->min }};
            const maxAmount = {{ $card->max }};

            amountInput.addEventListener("input", function () {
                const amount = parseFloat(amountInput.value);
                if (amount < minAmount) {
                    errorMessage.textContent = `المبلغ يجب أن يكون أكبر من أو يساوي ${minAmount} $.`;
                } else if (amount > maxAmount) {
                    errorMessage.textContent = `المبلغ يجب أن يكون أقل من أو يساوي ${maxAmount} $.`;
                } else {
                    errorMessage.textContent = "";
                }
            });
        });
    </script>
@endpush
@endsection
