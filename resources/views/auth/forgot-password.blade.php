@extends('layouts.auth')

@php
    $data = [
        'name' => getSettingValue('site_name') ?? '',
        'message' => 'مرحبًا بك في لوحة التحكم الخاصة بك',
        'call' => 'يرجى تسجيل الدخول',
    ];
@endphp

@section('content')
    <div class="bg-image" style="background-image: url('{{ asset('assets/media/photos/photo34@2x.jpg') }}');">
        <div class="row mx-0 bg-black-50">
            <div class="hero-static col-md-6 col-xl-8 d-none d-md-flex align-items-md-end">
                <div class="p-4">
                    <p class="fs-3 fw-semibold text-white">
                        هل نسيت كلمة المرور؟
                    </p>
                    <p class="text-white-75 fw-medium">
                        لا تقلق، نحن هنا لمساعدتك. أدخل بريدك الإلكتروني لإعادة تعيينها.
                    </p>
                </div>
            </div>
            <div class="hero-static col-md-6 col-xl-4 d-flex align-items-center bg-body-extra-light">
                <div class="content content-full">
                    <!-- العنوان -->
                    @include('layouts.partials.authPartial', ['data' => $data])
                    <!-- نهاية العنوان -->

                    <!-- نموذج استعادة كلمة المرور -->
                    <form method="POST" action="{{ route('password.email') }}" class="px-4">
                        @csrf

                        <!-- حالة الجلسة -->
                        <x-auth-session-status class="mb-4 badge bg-danger" :status="session('status')"/>

                        <!-- البريد الإلكتروني -->
                        <div class="form-floating mb-4">
                            <input type="email" class="form-control" id="email" name="email"
                                   placeholder="أدخل بريدك الإلكتروني" value="{{ old('email') }}" required autofocus>
                            <label for="email">البريد الإلكتروني</label>
                            <x-input-error :messages="$errors->get('email')" class="mt-2 badge bg-danger"/>
                        </div>

                        <!-- إرسال -->
                        <div class="mb-4">
                            <button type="submit" class="btn btn-lg btn-alt-primary fw-semibold">إرسال رابط استعادة كلمة المرور</button>
                        </div>
                    </form>
                    <!-- نهاية نموذج استعادة كلمة المرور -->
                </div>
            </div>
        </div>
    </div>
@endsection
