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
            <div class="hero-static col-md-6 col-xl-4 d-flex align-items-center bg-body-extra-light">
                <div class="content content-full">
                    <div class="px-4 py-2 mb-4">
                        @include('layouts.partials.authPartial', ['data' => $data])
                    </div>

                    <!-- نموذج كود التحقق -->
                    <form method="POST" action="{{ route('verification.verify') }}" class="px-4">
                        @csrf

                        <!-- كود التحقق -->
                        <div class="form-floating mb-4">
                            <x-input-label for="verification_code" :value="__('كود التحقق')"/>
                            <x-text-input id="verification_code" class="form-control" type="text"
                                          name="verification_code" required autofocus/>
                            <x-input-error :messages="$errors->get('verification_code')" class="mt-2"/>
                        </div>

                        <div class="mb-4">
                            <x-primary-button>
                                {{ __('تأكيد البريد الإلكتروني') }}
                            </x-primary-button>
                        </div>
                    </form>
                    <!-- نهاية نموذج كود التحقق -->
                </div>
            </div>
        </div>
    </div>
@endsection
