<?php

namespace App\Http\Controllers;

use App\Mail\InvestorMail;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{

    public function index()
    {
        return view('backend.notifications.notifications');
    }

    public function apiIndex(Request $request)
    {
        // Fetch notifications for the logged-in user
        if ($request->ajax()) {
            $notifications = Notification::where('user_id', Auth::id())
                ->latest()
                ->get();

            return datatables()->of($notifications)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // Initialize the action buttons
                    $actionButtons = '';

                    // Check if the notification is read
                    if (!$row->is_read) {
                        $actionButtons .= '<button class="btn btn-sm btn-success mark-read mx-1" data-id="' . $row->id . '">Mark as Read</button>';
                    }

                    // Always add the Delete button
                    $actionButtons .= '<button class="btn btn-sm btn-danger delete-notification mx-1" data-id="' . $row->id . '">Delete</button>';

                    return $actionButtons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    public function show($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json(['error' => 'Notification not found'], 404);
        }

        return response()->json($notification);
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->is_read = true;
        $notification->save();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        // Update all notifications for the authenticated user to mark them as read
        $affectedRows = Notification::where('user_id', Auth::id())
            ->where('is_read', false) // Optionally filter only unread notifications
            ->update(['is_read' => true]);

        // Check if any rows were updated
        if ($affectedRows > 0) {
            return response()->json(['success' => true, 'message' => 'All notifications marked as read.']);
        } else {
            return response()->json(['success' => false, 'message' => 'No unread notifications found.']);
        }
    }

    public function sendNotification(Request $request)
    {
        // Define the validation rules
        $rules = [
            'user_id'   => 'required|exists:users,id',
            'template'  => 'required|string|in:' . implode(',', array_keys($this->getSubjectMap())),
        ];

        // Define custom error messages (optional)
        $messages = [
            'user_id.required'  => 'معرف المستخدم مطلوب.',
            'user_id.exists'    => 'المستخدم غير موجود.',
            'template.required' => 'اسم القالب مطلوب.',
            'template.in'       => 'اسم القالب غير صالح.',
        ];

        // Validate the incoming request data
        $validator = Validator::make($request->all(), $rules, $messages);

        // Handle validation failures
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Retrieve the validated input data
        $userId    = $request->input('user_id');
        $template  = $request->input('template');

        // Find the user by ID
        $user = User::find($userId);

        // Double-check if the user exists (optional, since validation already checks)
        if (!$user) {
            return back()->withError('المستخدم غير موجود.');
        }

        // Prepare the data to pass to the email template
        $data = [
            'user' => $user,
            // Add other data here if required by specific templates
        ];

        // Send the email using the helper function
        $emailSent = sendEmail($user->email, $template, $data);

        // Check if the email was sent successfully
        if ($emailSent) {
            return back()->withSuccess('تم إرسال الإشعار بنجاح.');
        } else {
            return back()->withErrors('فشل في إرسال الإشعار.');
        }
    }

    /**
     * Get the subject map for validation in the controller.
     *
     * @return array
     */
    private function getSubjectMap()
    {
        return [
            'kyc_completed_active'               => '🎉 تهانينا! تم إكمال التحقق من KYC الخاص بك',
            'kyc_completed_inactive'             => '✔️ تم إكمال التحقق من KYC – الحساب غير نشط',
            'kyc_pending'                        => '⏳ عملية التحقق من KYC قيد الانتظار – مطلوب إجراء',
            'kyc_processing_contract_available'   => '📄 عقد الاستثمار الخاص بك جاهز',
            'kyc_processing_incomplete_capital'   => '⚠️ رأس المال غير مكتمل – مطلوب إجراء',
            'kyc_rejected'                       => '❌ تم رفض التحقق من KYC – يرجى إعادة التقديم',
            'kyc_approved_contract_reminder'      => '📝 تذكير: يرجى توقيع عقد الاستثمار الخاص بك',
            'kyc_approved_incomplete_capital'     => '⚠️ رأس المال غير مكتمل – أكمل استثمارك',
            'contract_signed'                    => '🖋️ تم توقيع عقد الاستثمار الخاص بك وجاهز للاستخدام',
            'contract_reminder'                  => '📬 تذكير: يرجى مراجعة وتوقيع عقد الاستثمار الخاص بك',
            'kyc_rejection_notification'         => '📢 مطلوب إجراء: يرجى تحديث معلومات KYC الخاصة بك',
            'upload_new_signed_contract'          => '📥 قم بتحميل عقد الاستثمار الموقع الجديد الخاص بك',
            'general_notification'                => '📨 تحديث هام بخصوص حسابك',
            'need_to_pay'                         => '🎉 تهانينا! تم إكمال التحقق من KYC الخاص بك',
            'kyc_complete_the_payment'              => '📢 مطلوب إجراء: قم بتحويل رأس المال الخاص بك',
        ];
    }
}
