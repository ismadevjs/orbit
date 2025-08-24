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
                        الأمان أولاً.
                    </p>
                    <p class="text-white-75 fw-medium">
                        حقوق النشر &copy; <span data-toggle="year-copy"></span>
                    </p>
                </div>
            </div>
            <div class="hero-static col-md-6 col-xl-4 d-flex align-items-center bg-body-extra-light">
                <div class="content content-full">
                    <!-- العنوان -->
                    @include('layouts.partials.authPartial', ['data' => $data])
                    <!-- نهاية العنوان -->

                    <!-- نموذج تأكيد كلمة المرور -->
                    <form method="POST" action="{{ route('password.confirm') }}" class="px-4">
                        @csrf

                        <!-- كلمة المرور -->
                        <div class="form-floating mb-4">
                            <input type="password" class="form-control" id="password" name="password"
                                   placeholder="أدخل كلمة المرور الخاصة بك" required autofocus>
                            <label for="password">كلمة المرور</label>
                            <x-input-error :messages="$errors->get('password')" class="mt-2"/>
                        </div>
                        <div id="cf-turnstile-container">
                        <div class="cf-turnstile" data-sitekey="{{ config('services.cloudflare_turnstile.site_key') }}">
                        </div>
                        <input type="hidden" id="turnstile-response" name="cf-turnstile-response" required>
                    </div>
                    @if ($errors->has('cf-turnstile-response'))
                        <div class="text-danger">{{ $errors->first('cf-turnstile-response') }}</div>
                    @endif
                        <!-- إرسال -->
                        <div class="mb-4">
                            <button type="submit" class="btn btn-lg btn-alt-primary fw-semibold">تأكيد</button>
                        </div>
                    </form>
                    <!-- نهاية نموذج تأكيد كلمة المرور -->
                </div>
            </div>
        </div>
    </div>
@endsection
