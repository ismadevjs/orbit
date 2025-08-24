@extends('layouts.mail')

@section('content')

<div class="header" style="text-align: center; padding: 20px; background-color: #f8f8f8;">
    <img src="https://amwalflow.com/storage/logos/Evz4MZCUFIj4L2SfRHjMlGVhqJiAYZQ7kSE6TQtl.png" 
         alt="شعار اموال فلو" style="margin-bottom: 20px; max-width: 150px;">
    <h1 style="color: #28a745;">✔ كلمة المرور تم تغييرها بنجاح</h1>
</div>

<div class="content" style="padding: 20px;">
    <p>عزيزي/عزيزتي <strong>{{ $user->name }}</strong>,</p>

    <p>نود إبلاغك بأن كلمة المرور الخاصة بحسابك في <strong>اموال فلو</strong> قد تم تغييرها بنجاح.</p>

    <p>إذا قمت أنت بهذا التغيير، فلا داعي لاتخاذ أي إجراء. أما إذا لم تكن أنت من قام بتغيير كلمة المرور، يرجى التواصل مع فريق الدعم فورًا لتأمين حسابك.</p>

    <div class="cta" style="text-align: center; margin: 20px 0;">
        <a href="{{ route('login') }}" target="_blank" 
           style="padding: 10px 20px; color: #fff; background-color: #28a745; 
           text-decoration: none; border-radius: 5px;">تسجيل الدخول إلى حسابك</a>
    </div>

    <div class="separator" style="border-top: 1px solid #ddd; margin: 20px 0;"></div>

    <p>إذا كنت بحاجة إلى مساعدة أو لديك أي استفسارات، لا تتردد في التواصل معنا.</p>

    <p>مع أطيب التحيات،<br>
    <strong>فريق الدعم | اموال فلو</strong></p>
</div>  

<div class="footer" style="text-align: center; padding: 20px; background-color: #f8f8f8; font-size: 0.9em;">
    <p>لأي استفسار، يرجى التواصل مع <a href="mailto:{{ getSettingValue('site_email') }}" 
       style="color: #007bff; text-decoration: none;">فريق الدعم</a>.</p>
    <p>© 2025 اموال فلو. جميع الحقوق محفوظة.</p>
</div>

@endsection