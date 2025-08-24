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
                max-width: 600px;
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
                background: linear-gradient(135deg, #ff7e5f, #feb47b);
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

            .qr-container {
                text-align: center;
                margin-top: 20px;
            }
            .qr-tooltip {
                display: inline-block;
                position: relative;
                cursor: pointer;
            }
            .qr-tooltip::after {
                content: "Scan Me";
                position: absolute;
                bottom: -25px;
                left: 50%;
                transform: translateX(-50%);
                background: rgba(0, 0, 0, 0.7);
                color: #fff;
                padding: 5px 10px;
                border-radius: 5px;
                white-space: nowrap;
                font-size: 12px;
                display: none;
            }
            .qr-tooltip:hover::after {
                display: block;
            }
            .download-btn {
                display: block;
                margin: 10px auto;
                padding: 10px;
                background: #00b09b;
                color: white;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                transition: background 0.3s;
            }
            .download-btn:hover {
                background: #96c93d;
            }
            #qr-code {
                display: none; /* Initially hide QR code until address selected */
            }
        </style>
    @endpush

    @php
        // Fetch the crypto transfer payment method
        $crypto = getPayment('crypto');

        // Decode the JSON data to an associative array
        $cryptoAddresses = $crypto->data ? json_decode($crypto->data, true) : [];

        // Extract unique networks
        $networks = collect($cryptoAddresses)->pluck('data_name')->unique();
    @endphp

    <div class="checkout-container">
        <!-- Header -->
        <div class="checkout-header">
            <h2>الدفع الآمن بالعملات الرقمية</h2>
            <p>أكمل عملية الدفع أدناه</p>
        </div>

        <!-- Body -->
        <div class="checkout-body">
            <form id="payment-form" action="{{ route('process.saveCryptoTransfer') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <span><strong>الطريقة:</strong> {{ $crypto->name }}</span>
                        </div>
                        <div class="col-md-6">
                            <span><strong>متوسط الوقت:</strong> {{ $crypto->time ?? 'غير متوفر' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span><strong>الحد الأدنى:</strong> {{ currency($crypto->min) }}</span>
                        </div>
                        <div class="col-md-6">
                            <span><strong>الحد الأقصى:</strong> {{ currency($crypto->max) }}</span>
                        </div>
                        <div class="qr-container">
                            <div class="qr-tooltip">
                                <img id="qr-code" src="" alt="QR Code" width="150">
                            </div>
                            <button type="button" id="download-qr" class="download-btn">تحميل QR</button>
                        </div>
                        <div class="col-md-6">
                            <span><strong>الاقتطاعات:</strong> {{ $crypto->tax }}%</span>
                        </div>
                        <div class="col-md-12">
                            <span><strong>مجموع التكاليف:</strong> <span id="total-cost" class="badge bg-success">---</span></span>
                        </div>
                    </div>
                </div>

                <!-- Crypto Network Selection -->
                <div class="mb-3">
                    <label for="crypto_network" class="form-label">شبكة العملات الرقمية</label>
                    <select id="crypto_network" name="crypto_network" class="form-control" required>
                        <option value="" disabled>اختر شبكة العملات الرقمية</option>
                        @foreach($networks as $network)
                            <option value="{{ $network }}" {{ old('crypto_network') == $network ? 'selected' : '' }}>
                                {{ ucfirst($network) }}
                            </option>
                        @endforeach
                    </select>
                    @error('crypto_network')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Crypto Address Selection -->
                <div class="mb-3">
                    <label for="crypto_address" class="form-label">عنوان المحفظة</label>
                    <select id="crypto_address" name="crypto_address" class="form-control" required>
                        <option value="" disabled selected>اختر عنوان المحفظة</option>
                        <!-- Options will be populated based on selected network -->
                    </select>
                    @error('crypto_address')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Amount -->
                <div class="mb-3">
                    <label for="amount" class="form-label">المبلغ ($)</label>
                    <input type="number" id="amount" name="amount" class="form-control" placeholder="أدخل المبلغ" value="{{ old('amount') }}" required>
                    <div id="error-message" class="error-message">
                        @error('amount')
                        {{ $message }}
                        @enderror
                    </div>
                </div>

                <!-- Amount Payable -->
                <div class="mb-3">
                    <label for="amount-pay" class="form-label">المبلغ المطلوب دفعه ($)</label>
                    <input type="text" id="amount-pay" class="form-control" readonly value="{{ old('amount') ? number_format(old('amount') + (old('amount') * $crypto->tax / 100), 2) . ' $' : '' }}">
                </div>

                <!-- Transaction Hash (Optional) -->
                <div class="mb-3">
                    <label for="transaction_hash" class="form-label">رقم المعاملة (اختياري)</label>
                    <input type="text" id="transaction_hash" name="transaction_hash" class="form-control" placeholder="أدخل رقم المعاملة إذا توفر" value="{{ old('transaction_hash') }}">
                    @error('transaction_hash')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Proof of Payment -->
                <div class="mb-3">
                    <label for="proof_of_payment" class="form-label">إثبات الدفع (لقطة شاشة)</label>
                    <input type="file" id="proof_of_payment" name="proof_of_payment" class="form-control" accept="image/*,application/pdf" required>
                    <div id="proof-error" class="error-message">
                        @error('proof_of_payment')
                        {{ $message }}
                        @enderror
                    </div>
                </div>

                <!-- Final Cost -->
                <div class="mb-3">
                    <label for="final-cost" class="form-label">المبلغ النهائي للدفع ($)</label>
                    <input type="text" id="final-cost" name="final_cost" class="form-control" readonly value="{{ old('amount') ? number_format(old('amount') + (old('amount') * $crypto->tax / 100), 2) : '' }}">
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
            <a href="{{ route('investor.wallet.index') }}" class="btn btn-primary mt-3">
                <i class="fas fa-wallet"></i> المحفظة
            </a>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const amountInput = document.getElementById("amount");
                const errorMessage = document.getElementById("error-message");
                const totalCostDisplay = document.getElementById("total-cost");
                const amountPay = document.getElementById("amount-pay");
                const finalCostInput = document.getElementById("final-cost");
                const cryptoNetworkSelect = document.getElementById("crypto_network");
                const cryptoAddressSelect = document.getElementById("crypto_address");
                const qrCodeImg = document.getElementById("qr-code");
                const downloadBtn = document.getElementById("download-qr");
                const transactionHashInput = document.getElementById("transaction_hash");

                const taxRate = {{ $crypto->tax }};
                const minAmount = {{ $crypto->min }};
                const maxAmount = {{ $crypto->max }};

                // Create a mapping from network to addresses
                const cryptoData = @json($cryptoAddresses);

                const networkToAddresses = {};
                cryptoData.forEach(function(item) {
                    if (!networkToAddresses[item.data_name]) {
                        networkToAddresses[item.data_name] = [];
                    }
                    networkToAddresses[item.data_name].push(item.data_value);
                });

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

                // Function to update QR code
                function updateQRCode() {
                    const selectedAddress = cryptoAddressSelect.value;
                    if (selectedAddress) {
                        qrCodeImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(selectedAddress)}`;
                        qrCodeImg.style.display = "block"; // Make sure QR is visible
                    } else {
                        qrCodeImg.style.display = "none"; // Hide QR if no address selected
                    }
                }
                
                function downloadQRCode(e) {
                    e.preventDefault(); // Prevent form submission
                    const link = document.createElement('a');
                    link.href = qrCodeImg.src;
                    link.download = 'crypto_qr_code.png';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }

                function updateCryptoAddresses() {
                    const selectedNetwork = cryptoNetworkSelect.value;
                    const addresses = networkToAddresses[selectedNetwork] || [];

                    // Clear previous options
                    cryptoAddressSelect.innerHTML = '<option value="" disabled selected>اختر عنوان المحفظة</option>';

                    // Populate new options
                    addresses.forEach(function(address) {
                        const option = document.createElement('option');
                        option.value = address;
                        option.textContent = address;
                        cryptoAddressSelect.appendChild(option);
                    });

                    // If there's only one address, select it automatically
                    if (addresses.length === 1) {
                        cryptoAddressSelect.value = addresses[0];
                        updateQRCode(); // Generate QR code when address is auto-selected
                    }

                    // If there's an old selected address, set it
                    @if(old('crypto_address'))
                        cryptoAddressSelect.value = "{{ old('crypto_address') }}";
                        updateQRCode(); // Generate QR code for the old selected address
                    @endif
                }

                // Update total cost on amount input
                amountInput.addEventListener("input", updateTotalCost);

                // Update crypto addresses on network selection
                cryptoNetworkSelect.addEventListener("change", updateCryptoAddresses);
                
                // Update QR code when crypto address changes
                cryptoAddressSelect.addEventListener("change", updateQRCode);
                
                // Handle download button click
                downloadBtn.addEventListener("click", downloadQRCode);

                // Handle form repopulation on page load (e.g., after validation errors)
                @if(old('crypto_network'))
                    cryptoNetworkSelect.value = "{{ old('crypto_network') }}";
                    updateCryptoAddresses();
                @else
                    // If no old input, select the first network and populate its addresses
                    @if($networks->count() > 0)
                        cryptoNetworkSelect.value = "{{ $networks->first() }}";
                        updateCryptoAddresses();
                    @endif
                @endif

                // Initialize QR code if an address is already selected
                if (cryptoAddressSelect.value) {
                    updateQRCode();
                }

                // Initial total cost calculation if amount exists
                if (amountInput.value) {
                    updateTotalCost();
                }

                @if(session('success'))
                    // Hide the form and show success message
                    document.querySelector('.checkout-body').style.display = 'none';
                    document.querySelector('.checkout-header').style.display = 'none';
                    document.querySelector('.success-message').style.display = 'block';
                @endif
            });
        </script>
    @endpush

@endsection