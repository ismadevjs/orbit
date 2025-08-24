@extends('layouts.mail')

@section('content')

<div class="header" style="text-align: center; padding: 20px; background-color: #f8f8f8;">
    <img src="https://amwalflow.com/storage/logos/Evz4MZCUFIj4L2SfRHjMlGVhqJiAYZQ7kSE6TQtl.png" 
         alt="شعار أموال فلو" style="margin-bottom: 20px; max-width: 150px;">
    <h1 style="color: #333;">كشف الحساب الشهري</h1>
</div>

<div class="content" style="padding: 20px; direction: rtl; text-align: right;">
    <p>عزيزي/عزيزتي <strong>{{ $user->name }}</strong>,</p>

    <p>مرفق مع هذه الرسالة كشف حسابك الاستثماري الشهري لشهر 
    @php
        $arabicMonths = [
            'January' => 'يناير',
            'February' => 'فبراير',
            'March' => 'مارس',
            'April' => 'أبريل',
            'May' => 'مايو',
            'June' => 'يونيو',
            'July' => 'يوليو',
            'August' => 'أغسطس',
            'September' => 'سبتمبر',
            'October' => 'أكتوبر',
            'November' => 'نوفمبر',
            'December' => 'ديسمبر',
        ];
        $englishMonth = now()->subMonth()->format('F');
        $arabicMonth = $arabicMonths[$englishMonth];
        $year = now()->subMonth()->format('Y');
        echo "$arabicMonth $year";
    @endphp
    .</p>

    <p>يرجى مراجعة الكشف المرفق للحصول على تفاصيل معاملاتك الشهرية.</p>

    <div class="cta" style="text-align: center; margin: 20px 0;">
        <a href="{{ route('login') }}" target="_blank" 
           style="padding: 10px 20px; color: #fff; background-color: #007bff; 
           text-decoration: none; border-radius: 5px;">الدخول إلى حسابك</a>
    </div>

    <div class="separator" style="border-top: 1px solid #ddd; margin: 20px 0;"></div>

    <p>إذا كان لديك أي استفسارات أو تحتاج إلى مساعدة، فلا تتردد في التواصل معنا.</p>

    <p>مع أطيب التحيات،<br>
    <strong>فريق الدعم | أموال فلو</strong></p>
</div>  

<div class="footer" style="text-align: center; padding: 20px; background-color: #f8f8f8; font-size: 0.9em;">
    <p>لأي استفسار، يرجى التواصل مع <a href="mailto:{{ getSettingValue('site_email') }}" 
       style="color: #007bff; text-decoration: none;">فريق الدعم</a>.</p>
    <p>© 2025 أموال فلو. جميع الحقوق محفوظة.</p>
</div>

@endsection