@extends('layouts.mail')

@section('content')

<div class="header" style="text-align: center; padding: 20px; background-color: #f8f8f8;">
    <img src="https://amwalflow.com/storage/logos/Evz4MZCUFIj4L2SfRHjMlGVhqJiAYZQ7kSE6TQtl.png" alt="شعار شركتنا" style="margin-bottom: 20px;">
    <h1 style="color: #333;">مرحباً {{ $data['user']->name }},</h1>
</div>

<div class="content" style="padding: 20px;">
    <h2 style="color: #28a745;">رمز التحقق (OTP) الخاص بك</h2>
    <p>استخدم رمز التحقق التالي لإكمال عملية تسجيل الدخول أو التحقق من الحساب:</p>
    <div style="text-align: center; font-size: 24px; font-weight: bold; color: #d9534f; padding: 10px; border: 2px dashed #d9534f; display: inline-block; margin: 20px 0;">
        {{ $data['otp'] }}
    </div>
    <p>هذا الرمز صالح لمدة 10 دقائق فقط. لا تشاركه مع أي شخص للحفاظ على أمان حسابك.</p>
    <div class="separator" style="border-top: 1px solid #ddd; margin: 20px 0;"></div>
    <p>إذا لم تطلب هذا الرمز، يمكنك تجاهل هذه الرسالة.</p>
    <p>مع أطيب التحيات،<br>فريق الدعم | شركتنا</p>
</div>  

<div class="footer" style="text-align: center; padding: 20px; background-color: #f8f8f8; font-size: 0.9em;">
    <p>إذا كان لديك أي استفسار، يرجى التواصل مع <a href="mailto:{{ getSettingValue('site_email') }}" style="color: #007bff; text-decoration: none;">فريق الدعم</a>.</p>
    <p>© 2025 {{ getSettingValue('footer') }}. جميع الحقوق محفوظة.</p>
</div>

@endsection
