@extends('layouts.backend')

@section('content')

    @push('styles')

        <!-- CSS مخصص -->
        <style>
            body {
                background: linear-gradient(to bottom, #6b73ff, #000dff);
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
            }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-20px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .checkout-header {
                background: linear-gradient(135deg, #6b73ff, #000dff);
                color: #fff;
                text-align: center;
                padding: 20px;
            }

            .checkout-header h2 {
                margin: 0;
                font-size: 24px;
            }

            .checkout-body {
                padding: 30px;
            }

            #card-element {
                border: 1px solid #ddd;
                padding: 12px;
                border-radius: 8px;
                margin-bottom: 20px;
            }

            .form-control {
                border-radius: 8px;
                border: 1px solid #ccc;
                padding: 10px;
            }

            .pay-btn {
                background: #6b73ff;
                color: white;
                border: none;
                padding: 12px;
                border-radius: 8px;
                font-weight: bold;
                width: 100%;
                transition: background 0.3s, transform 0.2s;
            }

            .pay-btn:hover {
                background: #000dff;
                transform: scale(1.02);
            }

            .success-message {
                display: none;
                text-align: center;
                padding: 50px 20px;
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
            $card = getPayment('stripe')
        @endphp
    @endif

    @push('scripts')
        
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            const stripe = Stripe("{{ config('services.stripe.key') }}");
            const elements = stripe.elements();

            const cardElement = elements.create("card", {
                hidePostalCode: true,
                style: {
                    base: {
                        fontSize: "16px",
                        color: "#32325d",
                        fontFamily: "Poppins, sans-serif",
                        "::placeholder": { color: "#aab7c4" },
                    },
                    invalid: { color: "#fa755a" },
                }
            });

            cardElement.mount("#card-element");

            const form = document.getElementById("payment-form");

            // Live Calculation and Validation
            document.addEventListener("DOMContentLoaded", function () {
                const amountInput = document.getElementById("amount");
                const errorMessage = document.getElementById("error-message");
                const totalCostDisplay = document.getElementById("total-cost");
                const amountPay = document.getElementById("amount-pay");
                amountPay.value = "";
                amountInput.value = "";
                const taxRate = {{ $card->tax }};
                const minAmount = {{ $card->min }};
                const maxAmount = {{ $card->max }};

                function updateTotalCost() {
                    const amount = parseFloat(amountInput.value);
                    if (!isNaN(amount) && amount >= minAmount && amount <= maxAmount) {
                        const taxAmount = (amount * taxRate) / 100;
                        const totalCost = amount + taxAmount;
                        amountPay.value = totalCost;
                        totalCostDisplay.innerText = `${totalCost.toFixed(2)} $`;
                        errorMessage.textContent = "";
                    } else if (amount > maxAmount) {
                        errorMessage.textContent = `المبلغ يجب أن يكون أقل من أو يساوي ${maxAmount} $.`;
                        totalCostDisplay.innerText = "---";
                    } else if (amount < minAmount) {
                        errorMessage.textContent = `المبلغ يجب أن يكون أكبر من أو يساوي ${minAmount} $.`;
                        totalCostDisplay.innerText = "---";
                    } else {
                        totalCostDisplay.innerText = "---";
                        errorMessage.textContent = "";
                    }
                }

                amountInput.addEventListener("input", updateTotalCost);
            });

            form.addEventListener("submit", async (e) => {
                e.preventDefault();

                document.querySelector(".pay-btn").innerText = "جاري المعالجة...";
                document.querySelector(".pay-btn").disabled = true;

                const amount = document.getElementById("amount").value;

                try {
                    const response = await fetch("{{ route('process.payment') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        },
                        body: JSON.stringify({ amount }),
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        const errors = errorData.errors || { error: ["حدث خطأ غير متوقع"] };

                        let errorMessage = "";
                        Object.keys(errors).forEach(key => {
                            errorMessage += `${errors[key][0]}\n`;
                        });

                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ!',
                            text: errorMessage.trim(),
                        });

                        throw new Error(errorMessage);
                    }

                    const { clientSecret, name, email, phone } = await response.json();

                    const { error, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
                        payment_method: {
                            card: cardElement,
                            billing_details: {
                                name: name,
                                email: email,
                                phone: phone,
                            },
                        },
                    });

                    if (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ في الدفع!',
                            text: error.message,
                        });
                        document.querySelector(".pay-btn").innerText = "ادفع الآن";
                        document.querySelector(".pay-btn").disabled = false;
                    } else if (paymentIntent.status === "succeeded") {
                        document.querySelector(".checkout-body").style.display = "none";
                        document.querySelector(".success-message").style.display = "block";
                    }
                } catch (error) {
                    console.error(error);
                    document.querySelector(".pay-btn").innerText = "ادفع الآن";
                    document.querySelector(".pay-btn").disabled = false;
                }
            });
        </script>
    @endpush

    <div class="checkout-container">
        <!-- العنوان -->
        <div class="checkout-header">
            <h2>الدفع الآمن</h2>
            <p>أكمل عملية الدفع أدناه</p>
        </div>

        <!-- الجسم -->
        <div class="checkout-body">
            <form id="payment-form">

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
                    </div>
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">المبلغ ($)</label>
                    <input type="number" id="amount" class="form-control" placeholder="أدخل المبلغ"  value="" required>
                    <div id="error-message" class="error-message"></div>
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">المبلغ المطلوب دفعه ($)</label>
                    <input type="number" id="amount-pay" class="form-control" disabled required>
                    <div id="error-message" class="error-message"></div>
                </div>


                <div id="card-element"></div>
                <button type="submit" class="btn pay-btn mt-3">ادفع الآن</button>
            </form>
        </div>

        <!-- رسالة النجاح -->
        <div class="success-message">
            <svg class="checkmark" viewBox="0 0 52 52">
                <circle cx="26" cy="26" r="25"></circle>
                <path d="M14 27l7 7 15-15"></path>
            </svg>
            <h2>تم الدفع بنجاح!</h2>
            <p>شكرًا لك على الدفع.</p>
        </div>
    </div>

@endsection
