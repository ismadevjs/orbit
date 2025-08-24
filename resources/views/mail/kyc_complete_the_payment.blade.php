<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{getSettingValue('site_name')}}</title>
    <style>
        /* Import Poppins font from Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            color: #333333;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            direction: rtl;
        }
        .header {
            background-color: #005f5f;
            color: #ffffff;
            text-align: center;
            padding: 30px 20px;
            position: relative;
        }
        .header img {
            max-width: 150px;
            margin-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            animation: slideIn 1s ease-out;
        }
        .content {
            padding: 30px 20px;
            line-height: 1.6;
            animation: fadeIn 1s ease-out;
        }
        .content h2 {
            color: #005f5f;
            margin-bottom: 20px;
            font-size: 22px;
            font-weight: 600;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 10px;
        }
        .content p {
            margin: 15px 0;
            font-size: 16px;
            color: #555555;
        }
        .cta {
            text-align: center;
            margin: 40px 0;
        }
        .cta a {
            background-color: #FF6F61;
            color: #ffffff;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
            display: inline-block;
            margin: 10px;
        }
        .cta a:hover {
            background-color: #e65c54;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .separator {
            border-top: 1px solid #e0e0e0;
            margin: 30px 0;
        }
        .footer {
            background-color: #f9f9f9;
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #888888;
        }
        .footer a {
            color: #005f5f;
            text-decoration: none;
            margin: 0 5px;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .social-icons img {
            width: 24px;
            margin: 0 5px;
            vertical-align: middle;
        }

        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Responsive Design */
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 20px;
            }
            .header, .content, .footer {
                padding: 20px 15px;
            }
            .header h1 {
                font-size: 24px;
            }
            .content h2 {
                font-size: 20px;
            }
            .content p {
                font-size: 15px;
            }
            .cta a {
                padding: 12px 24px;
                font-size: 15px;
                margin: 8px;
            }
            .social-icons img {
                width: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container" style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto;">
        <div class="header" style="text-align: center; padding: 20px; background-color: #f4f4f4; border-bottom: 1px solid #ddd;">
            <img src="https://via.placeholder.com/150x50?text=Logo" alt="شعار شركتك" style="margin-bottom: 10px;">
            <h1 style="margin: 0; font-size: 24px; color: #333;">مرحبا {{ $data['user']->name }},</h1>
        </div>
        <div class="content" style="padding: 20px;">
            <h2 style="font-size: 20px; color: #d9534f;">إجراء ضروري لإكمال العملية</h2>
            <p style="font-size: 16px; margin-bottom: 15px;">
                نود إعلامك بلطف أن رأس المال الخاص بك لا يزال غير مكتمل، ولم يتم الوصول إلى المبلغ المطلوب (25,000).
            </p>
            <p style="font-size: 16px; margin-bottom: 15px;">
                لإكمال عملية الاستثمار الخاصة بك، يرجى إيداع المبلغ المتبقي في أقرب وقت ممكن. بمجرد الانتهاء، سنواصل العملية ونتأكد من إكمال جميع الإجراءات.
            </p>
            <div class="cta" style="text-align: center; margin: 20px 0;">
                <a href="{{ route('login') }}" target="_blank" style="background-color: #5cb85c; color: white; text-decoration: none; padding: 10px 20px; font-size: 16px; border-radius: 5px;">إكمال الإيداع</a>
            </div>
            <div class="separator" style="border-top: 1px solid #ddd; margin: 20px 0;"></div>
            <p style="font-size: 16px; margin-bottom: 10px;">
                إذا كان لديك أي استفسار أو تحتاج إلى مساعدة، لا تتردد في التواصل مع فريق الدعم الخاص بنا.
            </p>
            <p style="font-size: 16px; color: #555;">فريق الدعم | شركتنا</p>
        </div>  
        <div class="footer" style="text-align: center; padding: 10px; background-color: #f4f4f4; border-top: 1px solid #ddd;">
            <p style="font-size: 14px; margin-bottom: 5px;">
                إذا كان لديك أي استفسار، يرجى التواصل مع <a href="mailto:{{ getSettingValue('site_email') }}" style="color: #007bff; text-decoration: none;">فريق الدعم</a>.
            </p>
            <p style="font-size: 14px; margin: 0;">© 2024 {{ getSettingValue('footer') }}. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</body>

</html>
