


@extends('layouts.mail')

@section('content')

<div class="header" style="text-align: center; padding: 20px; background-color: #f8f8f8;">
    <img src="https://amwalflow.com/storage/logos/Evz4MZCUFIj4L2SfRHjMlGVhqJiAYZQ7kSE6TQtl.png" 
         alt="شعار اموال فلو" style="margin-bottom: 20px; max-width: 150px;">
    <h1 style="color: #28a745;">✅ تم توقيع عقدك وختمه من الشركة</h1>
</div>

<div class="content" style="padding: 20px; text-align: right; direction: rtl;">
    <p>عزيزي/عزيزتي <strong>{{ $data['user']->name }}</strong>,</p>
    
    <p>يسرنا إعلامك بأن عقدك قد تم توقيعه وختمه رسميًا من قبل اموال فلو.</p>
    
    <p>إذا لم تكن أنت من وقع العقد أو إذا كنت غير راغب في الاستمرار، يرجى مراجعة فريق الدعم خلال 48 ساعة. في حال عدم تواصلك معنا خلال هذه الفترة، سيتم اعتماد العقد كما هو.</p>
    
    <p>وفي حال كنت قد تلقيت هذا البريد الإلكتروني عن طريق الخطأ، يرجى التواصل مع فريق الدعم وحذف هذا البريد.</p>
    
    <p>إذا كان لديك أي استفسارات أو تحتاج إلى مساعدة، فلا تتردد في التواصل معنا.</p>
    
    <p>مع أطيب التحيات،<br>
    <strong>فريق الدعم | اموال فلو</strong></p>
</div>

<div class="footer" style="text-align: center; padding: 20px; background-color: #f8f8f8; font-size: 0.9em;">
    <p>لأي استفسار، يرجى التواصل مع <a href="mailto:{{ getSettingValue('site_email') }}" 
       style="color: #007bff; text-decoration: none;">فريق الدعم</a>.</p>
    <p>© 2025 اموال فلو. جميع الحقوق محفوظة.</p>
</div>

@endsection
