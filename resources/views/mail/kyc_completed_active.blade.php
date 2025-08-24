@extends('layouts.mail')

@section('content')

<div class="header" style="text-align: center; padding: 20px; background-color: #f8f8f8;">
            <img src="https://via.placeholder.com/150x50?text=Logo" alt="شعار شركتك" style="margin-bottom: 20px;">
            <h1 style="color: #333;">مرحباً {{ $data['user']->name }},</h1>
        </div>
        <div class="content" style="padding: 20px;">
            <h2 style="color: #28a745;">تهانينا! حسابك الآن نشط</h2>
            <p>نحن سعداء لإبلاغك بأن جميع خطوات عملية التحقق من الهوية (KYC) قد اكتملت بنجاح.</p>
            <p>يمكنك الآن الاستمتاع بالوصول الكامل إلى حسابك والاستفادة من جميع الخدمات والفرص المتاحة.</p>
            <div class="cta" style="text-align: center; margin: 20px 0;">
                <a href="{{ route('login') }}" target="_blank" style="padding: 10px 20px; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 5px;">الدخول إلى حسابك</a>
            </div>
            <div class="separator" style="border-top: 1px solid #ddd; margin: 20px 0;"></div>
            <p>إذا كان لديك أي استفسارات أو تحتاج إلى مساعدة، فلا تتردد في التواصل معنا.</p>
            <p>مع أطيب التحيات،<br>فريق الدعم | شركتنا</p>
        </div>  
        <div class="footer" style="text-align: center; padding: 20px; background-color: #f8f8f8; font-size: 0.9em;">
            <p>إذا كان لديك أي استفسار، يرجى التواصل مع <a href="mailto:{{ getSettingValue('site_email') }}" style="color: #007bff; text-decoration: none;">فريق الدعم</a>.</p>
            <p>© 2025 {{ getSettingValue('footer') }}. جميع الحقوق محفوظة.</p>
        </div>


@endsection