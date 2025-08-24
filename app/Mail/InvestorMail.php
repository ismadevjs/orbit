<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvestorMail extends Mailable
{
    use Queueable, SerializesModels;

    public $templateName;
    public $data;

    /**
     * Create a new message instance.
     *
     * @param string $templateName Name of the email template
     * @param array $data Data to pass to the email template
     * @return void
     */
    public function __construct(string $templateName, array $data = [])
    {
        $this->templateName = $templateName;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Define a mapping between template names and their subjects
         $subjectMap = [
            'kyc_completed_active' => '🎉 تهانينا! تم إكمال التحقق من KYC الخاص بك',
            'kyc_completed_inactive' => '✔️ تم إكمال التحقق من KYC – الحساب غير نشط',
            'kyc_pending' => '⏳ عملية التحقق من KYC قيد الانتظار – مطلوب إجراء',
            'kyc_processing_contract_available' => '📄 عقد الاستثمار الخاص بك جاهز',
            'kyc_processing_incomplete_capital' => '⚠️ رأس المال غير مكتمل – مطلوب إجراء',
            'kyc_rejected' => '❌ تم رفض التحقق من KYC – يرجى إعادة التقديم',
            'kyc_approved_contract_reminder' => '📝 تذكير: يرجى توقيع عقد الاستثمار الخاص بك',
            'kyc_approved_incomplete_capital' => '⚠️ رأس المال غير مكتمل – أكمل استثمارك',
            'contract_signed' => '🖋️ تم توقيع عقد الاستثمار الخاص بك وجاهز للاستخدام',
            'contract_reminder' => '📬 تذكير: يرجى مراجعة وتوقيع عقد الاستثمار الخاص بك',
            'kyc_rejection_notification' => '📢 مطلوب إجراء: يرجى تحديث معلومات KYC الخاصة بك',
            'upload_new_signed_contract' => '📥 قم بتحميل عقد الاستثمار الموقع الجديد الخاص بك',
            'general_notification' => '📨 تحديث هام بخصوص حسابك',
            'registration' => '🎉 لقد تم إنشاء حسابك بنجاح',
            'needtopay' => 'مطلوب الدفع',
            'pending_deposite' => 'معلق',
            'accept_pay' => 'مقبول',
            'reject_pay' => 'مرفوض',
            'send_contract' => 'إرسال العقد',
            'password_changed' => '✔ كلمة المرور تم تغييرها بنجاح'
        ];

        // Get the subject based on the template name
        $subject = $subjectMap[$this->templateName] ?? '📢 تذكير هام';

        // Define the view path based on the template name
        $viewPath = "mail.{$this->templateName}";

        return $this->subject($subject)
                    ->view($viewPath)
                    ->with(['data' => $this->data]);
    }
}
