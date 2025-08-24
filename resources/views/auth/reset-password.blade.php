@extends('layouts.auth')

@php
    $data = [
        'name' => getSettingValue('site_name') ?? 'استثمر بذكاء',
        'message' => 'استثمر بثقة، ارفع قيمتك',
        'call' => 'إعادة تعيين كلمة المرور',
    ];
@endphp

@section('content')
    <div class="login-wrapper">
        <!-- Left Section: Reset Password Form -->
        <div class="login-left">
            <div class="logo">نجاحك يبدأ بخطوة واثقة</div>
            <div class="form-container">
                <h2 class="form-title">إعادة تعيين كلمة المرور</h2>
                <p class="form-subtitle">أدخل بريدك الإلكتروني وكلمة المرور الجديدة.</p>
 
                <!-- Reset Password Form -->
                <form method="POST" action="{{ route('password.store') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-3 text-success fw-medium text-center" :status="session('status')"/>

                    <!-- Email Field -->
                    <div class="form-group mb-3">
                        <input type="email" class="form-control" id="email" name="email"
                               placeholder="البريد الإلكتروني" value="{{ old('email', $request->email) }}" 
                               required autofocus>
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-danger text-sm"/>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group mb-4 position-relative">
                        <input type="password" class="form-control" id="password" name="password"
                               placeholder="كلمة المرور" required>
                        <span class="toggle-password" onclick="togglePassword()">
                            <i class="bi bi-eye-slash text-light"></i>
                        </span>
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-danger text-sm"/>
                    </div>

                    <!-- Password Confirmation Field -->
                    <div class="form-group mb-4 position-relative">
                        <input type="password" class="form-control" id="password_confirmation"36
                               name="password_confirmation" placeholder="تأكيد كلمة المرور" required>
                        <span class="toggle-password" onclick="togglePasswordConfirm()">
                            <i class="bi bi-eye-slash text-light"></i>
                        </span>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-danger text-sm"/>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn submit-btn">{{ $data['call'] }}</button>

                    <!-- Login Link -->
                    <p class="text-center mt-3">
                        <span class="text-dark">تذكرت كلمة المرور؟ </span>
                        <a href="{{ route('login') }}" class="link">تسجيل الدخول</a>
                    </p>
                </form>
            </div>
        </div>

        <!-- Right Section: Promotional Content with Animation -->
        <div class="login-right">
            <div class="promo-content">
                <h1 class="promo-title">{{ $data['message'] }}</h1>
                <div class="investment-animation">
                    <div class="chart-container">
                        <svg class="chart-line" viewBox="0 0 400 200">
                            <polyline points="0,180 50,150 100,170 150,120 200,140 250,90 300,110 350,70 400,100"
                                      fill="none" stroke="#ECEFF1" stroke-width="4" stroke-linecap="round">
                                <animate attributeName="points"
                                         values="0,180 50,180 100,180 150,180 200,180 250,180 300,180 350,180 400,180;
                                                 0,180 50,150 100,170 150,120 200,140 250,90 300,110 350,70 400,100"
                                         dur="2s" repeatCount="indefinite"/>
                            </polyline>
                            <!-- Glowing Points -->
                            <circle cx="50" cy="150" r="5" fill="#FFD700">
                                <animate attributeName="r" values="5;7;5" dur="1s" repeatCount="indefinite"/>
                                <animate attributeName="opacity" values="1;0.5;1" dur="1s" repeatCount="indefinite"/>
                            </circle>
                            <circle cx="150" cy="120" r="5" fill="#FFD700">
                                <animate attributeName="r" values="5;7;5" dur="1.2s" repeatCount="indefinite"/>
                                <animate attributeName="opacity" values="1;0.5;1" dur="1.2s" repeatCount="indefinite"/>
                            </circle>
                            <circle cx="250" cy="90" r="5" fill="#FFD700">
                                <animate attributeName="r" values="5;7;5" dur="1.4s" repeatCount="indefinite"/>
                                <animate attributeName="opacity" values="1;0.5;1" dur="1.4s" repeatCount="indefinite"/>
                            </circle>
                            <circle cx="350" cy="70" r="5" fill="#FFD700">
                                <animate attributeName="r" values="5;7;5" dur="1.6s" repeatCount="clock
                                <animate attributeName="r" values="5;7;5" dur="1s" repeatCount="indefinite"/>
                                <animate attributeName="opacity" values="1;0.5;1" dur="1s" repeatCount="indefinite"/>
                            </circle>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inline Styles -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;700&display=swap');

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            background: linear-gradient(135deg, #0A3D62 0%, #1B263B 80%);
            font-family: 'Cairo', sans-serif;
            overflow: hidden;
            position: relative;
            color: #ECEFF1;
        }

        /* Gradient Wave Background */
        .login-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(255, 215, 0, 0.05), transparent);
            animation: wave 10s infinite linear;
            opacity: 0.3;
            z-index: 0;
        }

        /* Left Section */
        .login-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            background: #FFFFFF;
            border-radius: 0 30px 30px 0;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
            animation: slideInLeft 1s ease-out;
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 0 30px 30px 0;
            border: 2px solid transparent;
            background: linear-gradient(45deg, #0A3D62, #FFD700) border-box;
            animation: borderGlow 4s infinite linear;
            z-index: -1;
        }

        .login-left:hover {
            transform: translateY(-5px);
        }

        .logo {
            font-size: 32px;
            font-weight: 700;
            color: #0A3D62;
            margin-bottom: 40px;
            text-align: center;
            text-shadow: 0 0 10px rgba(10, 61, 98, 0.2);
        }

        .form-container {
            max-width: 100vw;
            padding: 30px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.9));
            border-radius: 20px;
            box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.05);
        }

        .form-title {
            color: #0A3D62 !important;
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 15px;
            text-align: center;
        }

        .form-subtitle {
            color: #78909C;
            font-size: 16px;
            font-weight: 400;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-control {
            background: #F5F5F5;
            border: 2px solid #B0BEC5;
            border-radius: 12px;
            padding: 15px;
            color: #263238;
            font-size: 16px;
            width: 100%;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #0A3D62;
            box-shadow: 0 0 15px rgba(10, 61, 98, 0.3);
            transform: scale(1.02);
            outline: none;
        }

        .form-group {
            position: relative;
            margin-bottom: 20px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #78909C;
            transition: color 0.3s ease;
        }

        .toggle-password:hover {
            color: #0A3D62;
        }

        .submit-btn {
            background: linear-gradient(90deg, #0A3D62, #1B263B);
            color: #FFFFFF;
            border: none;
            border-radius: 12px;
            padding: 15px;
            width: 100%;
            font-size: 18px;
            font-weight: 600;
            transition: all 0.3s ease;
            animation: pulse 2s infinite ease-in-out;
        }

        .submit-btn:hover {
            background: linear-gradient(90deg, #1B263B, #0A3D62);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(10, 61, 98, 0.4);
        }

        .link {
            color: #0A3D62;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .link:hover {
            color: #FFD700;
        }

        /* Right Section */
        .login-right {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
            animation: slideInRight 1s ease-out;
        }

        .login-right::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(27, 38, 59, 0.9), rgba(10, 61, 98, 0.95));
            z-index: 1;
            animation: shimmer 5s infinite ease-in-out;
        }

        .promo-content {
            text-align: center;
            max-width: 600px;
            position: relative;
            z-index: 2;
        }

        .promo-title {
            font-size: 50px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 50px;
            text-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
            color: #ECEFF1;
        }

        .investment-animation {
            position: relative;
            height: 250px;
            width: 100%;
            max-width: 450px;
            margin: 0 auto;
        }

        .chart-container {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .chart-line {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2;
        }

        /* Animations */
        .animate-reveal {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 10px rgba(10, 61, 98, 0.3); }
            50% { box-shadow: 0 0 20px rgba(10, 61, 98, 0.5); }
        }

        @keyframes shimmer {
            0% { background-position: 0 0; }
            100% { background-position: 100% 100%; }
        }

        @keyframes wave {
            0% { background-position: 0 0; }
            100% { background-position: 100% 100%; }
        }

        @keyframes slideInLeft {
            from { transform: translateX(-50px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes slideInRight {
            from { transform: translateX(50px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes borderGlow {
            0% { border-color: rgba(10, 61, 98, 0.3); }
            50% { border-color: rgba(255, 215, 0, 0.3); }
            100% { border-color: rgba(10, 61, 98, 0.3); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
            }
            .login-right {
                display: none;
            }
            .login-left {
                flex: none;
                width: 100%;
                height: 100vh;
                padding: 30px;
            }

            .login-left {
                border-radius: 0;
            }

            .login-left::before {
                border-radius: 0;
            }

            .form-container {
                max-width: 100%;
                padding: 20px;
            }

            .promo-title {
                font-size: 36px;
                margin-bottom: 30px;
            }

            .investment-animation {
                height: 150px;
                max-width: 350px;
            }

            .chart-line {
                transform: scale(0.8);
            }
        }

        @media (max-width: 480px) {
            .login-left, .login-right {
                padding: 20px;
            }

            .form-control, .submit-btn {
                font-size: 14px;
                padding: 12px;
            }

            .promo-title {
                font-size: 28px;
            }

            .investment-animation {
                height: 120px;
                max-width: 300px;
            }

            .logo {
                font-size: 24px;
            }

            .form-title {
                font-size: 28px;
            }

            .form-subtitle {
                font-size: 14px;
            }

            .chart-line {
                transform: scale(0.7);
            }
        }
    </style>

    <!-- Inline JavaScript -->
    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const icon = password.nextElementSibling.querySelector('i');
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                password.type = 'password';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            }
        }

        function togglePasswordConfirm() {
            const passwordConfirm = document.getElementById('password_confirmation');
            const icon = passwordConfirm.nextElementSibling.querySelector('i');
            if (passwordConfirm.type === 'password') {
                passwordConfirm.type = 'text';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                passwordConfirm.type = 'password';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            }
        }
    </script>
@endsection