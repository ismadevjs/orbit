@extends('layouts.backend')

@section('content')
    <style>
        .card-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .crypto-card {
            width: 360px;
            background: #ffffff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            text-align: center;
            position: relative;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .crypto-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
        }

        .crypto-icon {
            font-size: 48px;
            color: #6C63FF; /* لون التمييز */
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .crypto-icon:hover {
            transform: scale(1.05);
        }

        .crypto-card h1 {
            font-size: 1.4rem;
            margin-bottom: 20px;
            font-weight: 600;
            color: #333;
        }

        .btn-custom {
            margin: 10px 5px;
            padding: 12px 24px;
            font-size: 0.95rem;
            border: none;
            border-radius: 25px;
            font-weight: 500;
            color: #fff;
            background: linear-gradient(90deg, #6C63FF, #7a70ff);
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .btn-custom:hover {
            background: linear-gradient(90deg, #615aed, #726bff);
            transform: translateY(-2px);
        }

        #status {
            margin-top: 20px;
            font-size: 0.95rem;
            font-weight: 500;
            color: #444;
            word-wrap: break-word;
        }

        /* تعديلات التوافق مع الأجهزة المحمولة */
        @media (max-width: 576px) {
            .crypto-card {
                width: 90%;
                padding: 30px;
            }

            .crypto-icon {
                font-size: 40px;
            }

            .crypto-card h1 {
                font-size: 1.2rem;
            }

            .btn-custom {
                font-size: 0.9rem;
                padding: 10px 20px;
            }
        }
    </style>

    <div class="card-wrapper">
        <div class="crypto-card">
            <i class="crypto-icon fa-brands fa-ethereum"></i>
            <h1>الدفع بالعملات الرقمية عبر MetaMask</h1>
            <button id="connectWallet" class="btn-custom">اتصال بالمحفظة</button>
            <button id="payWithCrypto" class="btn-custom">الدفع بالعملات الرقمية</button>
            <p id="status"></p>
        </div>
    </div>

    @push('scripts')
        <script type="module">
            import { ethers } from 'https://cdn.jsdelivr.net/npm/ethers@6.6.4/dist/ethers.min.js';

            if (typeof ethers === 'undefined') {
                console.error('Ethers.js غير محمل');
            } else {
                console.log('تم تحميل Ethers.js بنجاح (v6)');
            }

            const provider = new ethers.BrowserProvider(window.ethereum);

            document.getElementById('connectWallet').addEventListener('click', async () => {
                try {
                    await provider.send("eth_requestAccounts", []);
                    const signer = await provider.getSigner();
                    const address = await signer.getAddress();
                    document.getElementById('status').textContent = `المحفظة المتصلة: ${address}`;
                } catch (error) {
                    console.error("خطأ في الاتصال بالمحفظة:", error);
                    document.getElementById('status').textContent = "فشل الاتصال بالمحفظة. يرجى المحاولة مرة أخرى.";
                }
            });

            document.getElementById('payWithCrypto').addEventListener('click', async () => {
                try {
                    const signer = await provider.getSigner();
                    const recipientAddress = "0x7aAD69615A88dF52e7Ef763D08Aea5C7ADC3163D";
                    const amountInEth = "0.01";

                    const tx = await signer.sendTransaction({
                        to: recipientAddress,
                        value: ethers.parseEther(amountInEth),
                    });

                    document.getElementById('status').textContent = `تم إرسال العملية! Hash: ${tx.hash}`;
                    verifyTransaction(tx.hash);
                } catch (error) {
                    console.error("خطأ في الدفع:", error);
                    document.getElementById('status').textContent = "فشل الدفع. تأكد من توفر الأموال وحاول مرة أخرى.";
                }
            });

            async function verifyTransaction(transactionHash) {
                try {
                    const response = await fetch('{{ route('crypto.verify') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify({ transaction_hash: transactionHash }),
                    });

                    const data = await response.json();
                    if (data.status === 'success') {
                        document.getElementById('status').textContent = `تم التحقق من الدفع: ${data.value} ETH تم استلامها.`;
                    } else {
                        document.getElementById('status').textContent = `فشل التحقق من الدفع: ${data.message}`;
                    }
                } catch (error) {
                    console.error("خطأ في التحقق:", error);
                    document.getElementById('status').textContent = "فشل التحقق. يرجى المحاولة لاحقاً.";
                }
            }
        </script>
    @endpush
@endsection
