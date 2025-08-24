@extends('layouts.mail')

@section('content')

<div class="header" style="text-align: center; padding: 20px; background-color: #f8f8f8;">
    <img src="https://amwalflow.com/storage/logos/Evz4MZCUFIj4L2SfRHjMlGVhqJiAYZQ7kSE6TQtl.png" 
         alt="شعار اموال فلو" style="margin-bottom: 20px; max-width: 150px;">
    <h1 style="color: #dc3545;">❌ نأسف، لم يتم قبول الإيداع</h1>
</div>

<div class="content" style="padding: 20px;">
    <p>عزيزي/عزيزتي <strong>{{ $data['user']->name }}</strong>,</p>

    <p>نأسف لإبلاغك بأن عملية الإيداع (رقم الطلب: <strong>{{ $data['user']->latestTransaction->transaction_reference ?? 'N/A' }}</strong>) لم تتم بنجاح.</p>

    <p>يرجى التحقق من تفاصيل العملية والمحاولة مرة أخرى. إذا كنت بحاجة إلى مساعدة، لا تتردد في التواصل مع فريق الدعم.</p>

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
