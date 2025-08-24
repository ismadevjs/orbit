@extends('layouts.backend')

@section('content')

@push('styles')
    <style>
        body {
            background: linear-gradient(to bottom, #886E58, #5E3719);
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
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .checkout-header {
            background: linear-gradient(135deg, #886E58, #5E3719);
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        .checkout-body { padding: 30px; }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .pay-btn {
            background: #886E58;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: bold;
            width: 100%;
            transition: background 0.3s, transform 0.2s;
            cursor: pointer;
        }

        .pay-btn:hover { background: #5E3719; transform: scale(1.02); }
        .error-message { color: red; font-size: 14px; margin-top: 10px; }
    </style>
@endpush

@if(getTablesLimit('payment_methods', 1))
    @php $card = getPayment('crypto') @endphp
@endif

@if($card->type)
    <div class="checkout-container">
        <div class="checkout-header">
            <h2>السحب USDT</h2>
            <p>أكمل عملية السحب أدناه</p>
        </div>

        <div class="checkout-body">
            <form id="payment-form" action="{{ route('process.saveCryptoTransfer.withdrawal') }}" method="post">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">الاسم الكامل</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="أدخل اسمك الكامل" required>
                </div>

                <div class="mb-3">
                    <label for="crypto_type" class="form-label">نوع العملة الرقمية</label>
                    <select id="crypto_type" name="crypto_type" class="form-control" required>
                        <option value="USDT">USDT</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="network" class="form-label">الشبكة</label>
                    <select id="network" name="network" class="form-control" required>
                        <option value="BEP20">BEP20</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="wallet_address" class="form-label">عنوان المحفظة</label>
                    <input type="text" id="wallet_address" name="wallet_address" class="form-control" placeholder="أدخل عنوان المحفظة" required>
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

@endsection
