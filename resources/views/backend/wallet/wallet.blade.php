@extends('layouts.backend')

@can('browse wallet')
    @section('content')
        <div class="wallet-dashboard container py-5">
            <div class="wallet-header text-center mb-5">
                <h1 class="text-gradient display-4">لوحة التحكم بالمحفظة</h1>
                <p class="text-muted lead">مرحبًا بك، {{ auth()->user()->name }}. هنا ملخص محفظتك بتصميم فريد!</p>
            </div>

            

            <!-- Capital -->
            <div class="wallet-card gradient-blue shadow-lg mb-4">
                <div class="wallet-card-content d-flex align-items-center justify-content-between">
                    <div class="text-content">
                        <h2>رأس المال</h2>
                        <p class="wallet-value">{{ currency($wallet->capital) }}</p>
                    </div>
                    <div class="icon-circle">
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
            </div>

            <!-- Pending Capital -->
            @if ($wallet->pending_capital > 0)
                <div class="wallet-card gradient-light-blue shadow-lg mb-4">
                    <div class="wallet-card-content d-flex align-items-center justify-content-between">
                        <div class="text-content">
                            <h2>رأس المال المعلق</h2>
                            <p class="wallet-value">{{ currency($wallet->pending_capital) }}</p>
                        </div>
                        <div class="icon-circle">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Profit -->
            <div class="wallet-card gradient-purple shadow-lg mb-4">
                <div class="wallet-card-content d-flex align-items-center justify-content-between">
                    <div class="text-content">
                        <h2>الأرباح</h2>
                        <p class="wallet-value">{{ currency($wallet->profit) }}</p>
                    </div>
                    <div class="icon-circle">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>

            <!-- Pending Profit -->
            @if ($wallet->pending_profit > 0)
                <div class="wallet-card gradient-light-purple shadow-lg mb-4">
                    <div class="wallet-card-content d-flex align-items-center justify-content-between">
                        <div class="text-content">
                            <h2>الأرباح المعلقة</h2>
                            <p class="wallet-value">{{ currency($wallet->pending_profit) }}</p>
                        </div>
                        <div class="icon-circle">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Bonus -->
            <div class="wallet-card gradient-gold shadow-lg mb-4">
                <div class="wallet-card-content d-flex align-items-center justify-content-between">
                    <div class="text-content">
                        <h2>المكافأة</h2>
                        <p class="wallet-value">{{ currency($wallet->bonus) }}</p>
                    </div>
                    <div class="icon-circle">
                        <i class="fas fa-gift"></i>
                    </div>
                </div>
            </div>

            <!-- Pending Bonus -->
            @if ($wallet->pending_bonus > 0)
                <div class="wallet-card gradient-light-gold shadow-lg mb-4">
                    <div class="wallet-card-content d-flex align-items-center justify-content-between">
                        <div class="text-content">
                            <h2>المكافأة المعلقة</h2>
                            <p class="wallet-value">{{ currency($wallet->pending_bonus) }}</p>
                        </div>
                        <div class="icon-circle">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Status -->
            <div class="wallet-card shadow-lg mb-4" style="background: {{ $wallet->is_locked ? 'linear-gradient(135deg, #ff416c, #ff4b2b)' : 'linear-gradient(135deg, #56ab2f, #a8e063)' }};">
                <div class="wallet-card-content d-flex align-items-center justify-content-between">
                    <div class="text-content">
                        <h2>الحالة</h2>
                        <p class="wallet-status">{{ $wallet->is_locked ? '🔒 مغلقة' : '🔓 مفتوحة' }}</p>
                    </div>
                    <div class="icon-circle">
                        <i class="fas fa-lock"></i>
                    </div>
                </div>
            </div>
        </div>
    @endsection



    @push('styles')
        <style>
            /* Global Styles */
            body {
                font-family: 'Noto Kufi Arabic', sans-serif;
                background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
                margin: 0;
                padding: 0;
                color: #ffffff;
                direction: rtl;
                text-align: right;
            }

            .wallet-dashboard {
                padding: 40px;
                border-radius: 20px;
                background: rgba(255, 255, 255, 0.08);
                backdrop-filter: blur(10px);
                box-shadow: 0px 15px 35px rgba(0, 0, 0, 0.3);
            }

            .wallet-header h1 {
                font-size: 3.5rem;
                font-weight: 700;
                background: linear-gradient(90deg, #5A6C57, #85A98F);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .wallet-header p {
                color: rgba(255, 255, 255, 0.7);
                font-size: 1.3rem;
            }

            /* Wallet Card */
            .wallet-card {
                display: flex;
                align-items: center;
                justify-content: space-between;
                background: rgba(255, 255, 255, 0.12);
                backdrop-filter: blur(12px);
                padding: 25px 30px;
                border-radius: 15px;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                position: relative;
            }

            .wallet-card:hover {
                transform: translateY(-10px);
                box-shadow: 0px 25px 50px rgba(0, 0, 0, 0.3);
            }

            .wallet-card-content {
                display: flex;
                align-items: center;
                justify-content: space-between;
                width: 100%;
            }

            .text-content h2 {
                font-size: 1.8rem;
                margin: 0;
                color: #fff;
            }

            .wallet-value {
                font-size: 3.5rem;
                font-weight: bold;
                color: rgba(255, 255, 255, 0.95);
                margin-top: 10px;
            }

            .wallet-status {
                font-size: 2.2rem;
                font-weight: bold;
                color: rgba(255, 255, 255, 0.9);
            }

            /* Icon Styles */
            .icon-circle {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 90px;
                height: 90px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.15);
                box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
                font-size: 2.8rem;
                color: rgba(255, 255, 255, 0.9);
                animation: rotateIcon 6s linear infinite;
            }

            /* Gradient Backgrounds */
            .gradient-blue {
                background: linear-gradient(135deg, #396afc, #2948ff);
            }

            .gradient-light-blue {
                background: linear-gradient(135deg, #5c9ded, #397abf);
            }

            .gradient-gold {
                background: linear-gradient(135deg, #f3c623, #f39c12);
            }

            .gradient-light-gold {
                background: linear-gradient(135deg, #f6d65e, #f3b623);
            }

            .gradient-purple {
                background: linear-gradient(135deg, #8e2de2, #4a00e0);
            }

            .gradient-light-purple {
                background: linear-gradient(135deg, #a274e3, #7348b5);
            }

            /* Animations */
            @keyframes rotateIcon {
                0% {
                    transform: rotate(0deg);
                }
                100% {
                    transform: rotate(360deg);
                }
            }
        </style>
    @endpush
@endcan
