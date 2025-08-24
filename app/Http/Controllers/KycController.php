<?php

namespace App\Http\Controllers;

use App\Models\Kyc;
use App\Models\KYCRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class KycController extends Controller
{
    public function kycVerification()
    {
        return view('backend.kyc.kyc');
    }

    public function steps()
    {
        $countries = json_decode(File::get(public_path('countries.json')));

        return view('backend.kyc.steps', compact('countries'));
    }

    public function upload(Request $request)
    {
        // Validate the required fields. Adjust validation rules as needed.
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'dob' => 'required|date',
        ]);


        $userId = Auth::id();

        // Update User model with personal information
        $user = User::find($userId);
        if ($user) {
            $user->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'country' => $request->country,
                'address' => $request->address,
                'date_of_birth' => $request->dob,
            ]);
        } else {
            return redirect()->back()->with('error', 'User not found.');
        }


        KycRequest::updateOrCreate(
            ['user_id' => $userId],
            ['status' => 'needtopay']
        );

        $data = [
            'user' => $user,
        ];

        sendEmail($user->email, 'needtopay', $data);


        return redirect()->route('investor.investor_analytics.index')->withSuccess('تم إرسال طلب التحقق بنجاح!');
    }



    public function uploadImageToMinio(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'image' => 'required|string', // Base64 image string
            'type' => 'required|string', // Type of image (e.g., id_front, selfie, etc.)
        ]);

        try {
            // Decode the Base64 image
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->image));
            $fileName = $request->type . '_' . time() . '.png';
            $path = 'kyc/' . $fileName;

            // Store the file in MinIO
            Storage::disk('public')->put($path, $imageData);

            // Determine which field to update in the KycRequest table based on the type
            $updateFields = [];
            switch ($request->type) {
                case 'id-front-image-data':
                    $updateFields['front_photo_path'] = $path;
                    $updateFields['document_type'] = 'id_card';
                    break;
                case 'id-back-image-data':
                    $updateFields['back_photo_path'] = $path;
                    $updateFields['document_type'] = 'id_card';
                    break;
                case 'document-image-data':
                    $updateFields['passport_photo_path'] = $path;
                    $updateFields['document_type'] = 'passport';
                    break;
                case 'license-front-image-data':
                    $updateFields['license_front_photo_path'] = $path;
                    $updateFields['document_type'] = 'driving_license';
                    break;
                case 'license-back-image-data':
                    $updateFields['license_back_photo_path'] = $path;
                    $updateFields['document_type'] = 'driving_license';
                    break;
                case 'selfie-image-data':
                    $updateFields['selfie_path'] = $path;
                    break;
                case 'residency-image-data':
                    $updateFields['residency_photo_path'] = $path;
                    break;
                default:
                    throw new \Exception('Invalid image type');
            }

            $kyc = KycRequest::updateOrCreate(
                ['user_id' => $request->user_id],
                $updateFields
            );
            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'path' => $path, // Path to be saved in the database
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image: ' . $e->getMessage(),
            ], 500);
        }
    }



    public function submit(Request $request)
    {
        $request->validate([
            'document_type' => 'required|string|in:id_card,driving_license,passport',
            'selfie' => 'required|image|max:8192',
            'front_photo' => 'nullable|image|max:8192',
            'back_photo' => 'nullable|image|max:8192',
            'passport_photo' => 'nullable|image|max:8192',
        ]);

        $data = [
            'user_id' => auth()->id(),
            'document_type' => $request->document_type,
            'selfie_path' => $request->file('selfie')->store('kyc/selfies'),
            'front_photo_path' => $request->file('front_photo')?->store('kyc/front_photos'),
            'back_photo_path' => $request->file('back_photo')?->store('kyc/back_photos'),
            'passport_photo_path' => $request->file('passport_photo')?->store('kyc/passport_photos'),
            'additional_info' => $request->additional_info,
            'status' => 'needtopay',
            'is_signed' => 0
        ];



        KYCRequest::create($data);

        return back()->withSuccess('Your KYC request has been submitted.');
    }

    public function index()
    {
        $kycRequests = KYCRequest::with('user')->where('status', 'pending')->get();
        return view('admin.kyc.index', compact('kycRequests'));
    }

    public function needtopay($id)
    {
        $kyc = KYCRequest::findOrFail($id);
        $kyc->update(['status' => 'needtopay']);
        return back()->withSuccess('KYC changed to Payment process.');
    }
    public function approve($id)
    {
        $kyc = KYCRequest::findOrFail($id);
        $kyc->update(['status' => 'approved']);
        return back()->withSuccess('KYC approved.');
    }

    public function reject(Request $request, $id)
    {
        $kyc = KYCRequest::findOrFail($id);
        $kyc->additional_info = $request->reason;
        $kyc->update(['status' => 'rejected']);
        $data = ['user' => $kyc->user];
        sendEmail($kyc->user->email, 'rejected', $data);
        return back()->withSuccess('KYC rejected.');
    }


    public function pending(Request $request, $id)
    {
        $kyc = KYCRequest::findOrFail($id);
        $kyc->additional_info = $request->reason;
        $kyc->update(['status' => 'pending']);
        return back()->withSuccess('KYC pedning.');
    }

    public function processing(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'nin' => 'required|string|max:255'
        ]);

        try {


            // Find the KYC request and update its status
            $kyc = KYCRequest::findOrFail($id);
            $kyc->update(['status' => 'processing']);


            // Find the user and update the NIN
            $user = User::findOrFail($kyc->user->id);
            $user->nin = $request->nin;
            $user->save();


            // Send notification email
            $data = ['user' => $user];
            sendEmail($user->email, 'need_to_pay', $data);

            return back()->withSuccess('KYC is now pending.');
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error processing KYC request: ' . $e->getMessage());

            return back()->with('error', 'An error occurred while processing the KYC request.');
        }
    }


    public function stepOne()
    {
        $countries = json_decode(File::get(public_path('countries.json')));
        $user = Auth::user(); // Get the authenticated user
        $kyc = $user->kycRequest ?? null; // Get existing KYC record if any
        return view('backend.kyc.step1', compact('user', 'kyc', 'countries'));
    }

    // Handle Step 1 Form Submission
    public function submitStepOne(Request $request)
    {
        Log::info("Received Request Data:", $request->all());
        // Manual validation
        $errors = [];


        if ($request->wantsJson()) {
            if (!$request->filled('user_id') || !User::find($request->user_id)) {
                $errors['user_id'] = 'Valid user_id is required for API requests.';
            } else {
                $userId = $request->user_id;
            }
        } else {
            if (!Auth::check()) {
                return redirect()->route('login')->withErrors(['auth' => 'You must be logged in.']);
            }
            $userId = Auth::id();
        }




        if (!$request->filled('name') || strlen($request->name) > 255) {
            $errors['name'] = 'Name is required and must be a string up to 255 characters.';
        }

        if (!$request->filled('date_of_birth')) {
            $errors['date_of_birth'] = 'Date of birth is required.';
        }

        if (!$request->filled('gender') || !in_array($request->gender, ['ذكر', 'أنثى'])) {
            $errors['gender'] = 'Gender is required and must be either ذكر or أنثى.';
        }

        if (!$request->filled('country')) {
            $errors['country'] = 'Country is required and must be a string up to 100 characters.';
        }

        if (!$request->filled('phone')) {
            $errors['phone'] = 'Phone is required and must be valid.';
        }



        if (!$request->filled('address') || strlen($request->address) > 500) {
            $errors['address'] = 'Address is required and must be a string up to 500 characters.';
        }

        if (!$request->filled('postal_code') || strlen($request->postal_code) > 20) {
            $errors['postal_code'] = 'Postal code is required and must be a string up to 20 characters.';
        }

        if (!$request->filled('occupation') || strlen($request->occupation) > 100) {
            $errors['occupation'] = 'Occupation is required and must be a string up to 100 characters.';
        }



        Log::info("Validation Errors:", $errors);

        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $errors,
            ], 422);
        }

        // Fetch the user
        $user = User::find($userId);
        Log::info("User Found:", [$user]);

        // Update user details manually using $request
        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->country = $request->country;
        $user->phone = $request->phone;
        $user->date_of_birth = $request->date_of_birth;
        $user->address = $request->address;
        $user->postal_code = $request->postal_code;
        $user->occupation = $request->occupation;
        $user->save();

        Log::info("User Updated Successfully:", [$user]);

        // Update or create KYC record
        $kyc = KYCRequest::updateOrCreate(
            ['user_id' => $user->id],
            [
                'status' => 'pending',
                'additional_info' => json_encode(['step' => 1]),
            ]
        );

        Log::info("KYC Updated/Created:", [$kyc]);

        // Return JSON if API request
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إكمال الخطوة الأولى بنجاح!',
                'data' => [
                    'user' => $user,
                    'kyc' => $kyc,
                ],
                'next_step' => route('investor.kyc.step.two'),
            ]);
        }

        // Otherwise redirect with success flash message
        return redirect()
            ->route('investor.kyc.step.two')
            ->withSuccess('تم إكمال الخطوة الأولى بنجاح!');
    }




    public function stepTwo()
    {
        $kyc = Auth::user()->kycRequest ?? null;
        if (!$kyc) {
            return redirect()->route('investor.kyc.step.one')->with('error', 'يرجى إكمال الخطوة الأولى أولاً!');
        }
        return view('backend.kyc.step2', compact('kyc'));
    }

   public function submitStepTwo(Request $request)
{
    // Dynamic validation
    $rules = [
        'document_type' => 'required|in:passport,id_card,driving_license',
        'front_image' => 'required|string',
    ];

    if (in_array($request->input('document_type'), ['id_card', 'driving_license'])) {
        $rules['back_image'] = 'required|string';
    } else {
        $rules['back_image'] = 'nullable|string';
    }

    $validated = $request->validate($rules);

    // Determine user depending on request type
    if ($request->wantsJson()) {
        $user = User::find($request->input('user_id')); // API user
    } else {
        $user = Auth::user(); // Blade user
    }

    if (!$user) {
        if ($request->wantsJson()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        return redirect()->route('login')->withErrors('يرجى تسجيل الدخول أولاً!');
    }

    $kyc = $user->kycRequest;
    if (!$kyc) {
        if ($request->wantsJson()) {
            return response()->json(['error' => 'Step one not completed'], 400);
        }
        return redirect()->route('investor.kyc.step.one')->with('error', 'يرجى إكمال الخطوة الأولى أولاً!');
    }

    try {
        // Save front image
        $frontImageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->front_image));
        $frontPath = 'kyc_documents/' . $user->id . '_front_' . time() . '.png';
        Storage::disk('public')->put($frontPath, $frontImageData);

        // Save back image if exists
        $backPath = null;
        if ($request->filled('back_image')) {
            $backImageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->back_image));
            $backPath = 'kyc_documents/' . $user->id . '_back_' . time() . '.png';
            Storage::disk('public')->put($backPath, $backImageData);
        }

        // Update KYC record
        $additionalInfo = json_decode($kyc->additional_info, true) ?? [];
        $additionalInfo['step'] = 2;

        switch ($validated['document_type']) {
            case 'passport':
                $kyc->passport_photo_path = $frontPath;
                break;
            case 'id_card':
                $kyc->front_photo_path = $frontPath;
                $kyc->back_photo_path = $backPath;
                break;
            case 'driving_license':
                $kyc->license_front_photo_path = $frontPath;
                $kyc->license_back_photo_path = $backPath;
                break;
        }

        $kyc->document_type = $validated['document_type'];
        $kyc->additional_info = json_encode($additionalInfo);
        $kyc->save();

        // Return JSON if API
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إكمال الخطوة الثانية بنجاح!',
                'data' => [
                    'user' => $user,
                    'kyc' => $kyc,
                ],
                'next_step' => route('investor.kyc.step.three'),
            ]);
        }

        // Otherwise redirect
        return redirect()
            ->route('investor.kyc.step.three')
            ->withSuccess('تم إكمال الخطوة الثانية بنجاح!');
    } catch (\Exception $e) {
        \Log::error('KYC Step 2 submission failed: ' . $e->getMessage());

        if ($request->wantsJson()) {
            return response()->json(['error' => 'حدث خطأ أثناء حفظ الصور.'], 500);
        }

        return back()->withErrors('حدث خطأ أثناء حفظ الصور. يرجى المحاولة مرة أخرى.');
    }
}


    public function stepThree()
    {
        $kyc = Auth::user()->kycRequest ?? null;
        if (!$kyc || !isset(json_decode($kyc->additional_info, true)['step']) || json_decode($kyc->additional_info, true)['step'] < 2) {
            return redirect()->route('kyc.step.two')->with('error', 'يرجى إكمال الخطوة الثانية أولاً!');
        }
        return view('backend.kyc.step3', compact('kyc'));
    }

    public function submitStepThree(Request $request)
{
    $rules = [
        'selfie_image' => 'required|string', // Base64 encoded image
    ];

    $validated = $request->validate($rules);

    // Determine user based on request type
    if ($request->wantsJson()) {
        $user = User::find($request->input('user_id')); // API user
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'المستخدم غير موجود.'
            ], 404);
        }
    } else {
        $user = Auth::user(); // Blade user
    }

    $kyc = $user->kycRequest;
    if (!$kyc || !isset(json_decode($kyc->additional_info, true)['step']) || json_decode($kyc->additional_info, true)['step'] < 2) {
        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى إكمال الخطوة الثانية أولاً!'
            ], 422);
        }
        return redirect()->route('kyc.step.two')->with('error', 'يرجى إكمال الخطوة الثانية أولاً!');
    }

    try {
        // Decode and store selfie image
        $selfieImageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->selfie_image));
        $selfiePath = 'kyc_documents/' . $user->id . '_selfie_' . time() . '.png';
        Storage::disk('public')->put($selfiePath, $selfieImageData);

        // Update KYC record
        $additionalInfo = json_decode($kyc->additional_info, true) ?? [];
        $additionalInfo['step'] = 3;

        $kyc->selfie_path = $selfiePath;
        $kyc->additional_info = json_encode($additionalInfo);
        $kyc->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إكمال الخطوة الثالثة بنجاح!',
                'selfie_path' => $selfiePath
            ]);
        }

        return redirect()->route('investor.kyc.step.four')->with('success', 'تم إكمال الخطوة الثالثة بنجاح!');
    } catch (\Exception $e) {
        \Log::error('KYC Step 3 submission failed: ' . $e->getMessage());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ الصورة. يرجى المحاولة مرة أخرى.'
            ], 500);
        }

        return back()->withErrors('حدث خطأ أثناء حفظ الصورة. يرجى المحاولة مرة أخرى.');
    }
}


    public function stepFour()
    {
        $kyc = Auth::user()->kycRequest ?? null;
        if (!$kyc || !isset(json_decode($kyc->additional_info, true)['step']) || json_decode($kyc->additional_info, true)['step'] < 3) {
            return redirect()->route('kyc.step.three')->with('error', 'يرجى إكمال الخطوة الثالثة أولاً!');
        }
        return view('backend.kyc.step4', compact('kyc'));
    }

    // Step 4: Handle residency photo submission
   public function submitStepFour(Request $request)
{
    $rules = [
        'residency_image' => 'required|string', // Base64 encoded image
    ];

    $validated = $request->validate($rules);

    // Determine user based on request type
    if ($request->wantsJson()) {
        $user = User::find($request->input('user_id')); // API user
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'المستخدم غير موجود.'
            ], 404);
        }
    } else {
        $user = Auth::user(); // Blade user
    }

    $kyc = $user->kycRequest;
    if (!$kyc || !isset(json_decode($kyc->additional_info, true)['step']) || json_decode($kyc->additional_info, true)['step'] < 3) {
        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى إكمال الخطوة الثالثة أولاً!'
            ], 422);
        }
        return redirect()->route('kyc.step.three')->with('error', 'يرجى إكمال الخطوة الثالثة أولاً!');
    }

    try {
        // Decode and store residency image
        $residencyImageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->residency_image));
        $residencyPath = 'kyc_documents/' . $user->id . '_residency_' . time() . '.png';
        Storage::disk('public')->put($residencyPath, $residencyImageData);

        // Update KYC record
        $additionalInfo = json_decode($kyc->additional_info, true) ?? [];
        $additionalInfo['step'] = 4;

        $kyc->residency_photo_path = $residencyPath;
        $kyc->additional_info = json_encode($additionalInfo);
        $kyc->status = 'needtopay';
        $kyc->save();

        // Send email only for Blade requests
        if (!$request->wantsJson()) {
            $data = ['user' => $user];
            sendEmail($user->email, 'needtopay', $data);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إرسال طلب التحقق بنجاح!',
                'residency_path' => $residencyPath
            ]);
        }

        return redirect()->route('investor.investor_analytics.index')->with('success', 'تم إرسال طلب التحقق بنجاح!');
    } catch (\Exception $e) {
        \Log::error('KYC Step 4 submission failed: ' . $e->getMessage());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ الصورة. يرجى المحاولة مرة أخرى.'
            ], 500);
        }

        return back()->withErrors('حدث خطأ أثناء حفظ الصورة. يرجى المحاولة مرة أخرى.');
    }
}


}
