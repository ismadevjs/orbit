@extends('layouts.mail')

@section('content')

<div class="header" style="text-align: center; padding: 20px; background-color: #f8f8f8;">
    <img src="https://amwalflow.com/storage/logos/Evz4MZCUFIj4L2SfRHjMlGVhqJiAYZQ7kSE6TQtl.png" 
         alt="شعار اموال فلو" style="margin-bottom: 20px; max-width: 150px;">
    <h1 style="color: #007bff;">📄 يرجى توقيع العقد وإرساله</h1>
</div>

<div class="content" style="padding: 20px;">
    <p>عزيزي/عزيزتي <strong>{{ $data['user']->name }}</strong>,</p>

    <p>يرجى التفضل بتوقيع العقد على كل صفحة وإرساله عبر البريد الإلكتروني مع إرفاق صورة من جواز السفر إلى:</p>

    <p style="text-align: center; font-weight: bold; color: #007bff;">
        <a href="mailto:support@amwalflow.com" style="color: #007bff; text-decoration: none;">
            support@amwalflow.com
        </a>
    </p>

    <p>إذا كان لديك أي استفسارات أو تحتاج إلى مساعدة، فلا تتردد في التواصل معنا.</p>

    <div class="separator" style="border-top: 1px solid #ddd; margin: 20px 0;"></div>

    <p>مع أطيب التحيات،<br>
    <strong>فريق الدعم | اموال فلو</strong></p>
</div>  

<div class="footer" style="text-align: center; padding: 20px; background-color: #f8f8f8; font-size: 0.9em;">
    <p>لأي استفسار، يرجى التواصل مع <a href="mailto:{{ getSettingValue('site_email') }}" 
       style="color: #007bff; text-decoration: none;">فريق الدعم</a>.</p>
    <p>© 2025 اموال فلو. جميع الحقوق محفوظة.</p>
</div>

@endsection
