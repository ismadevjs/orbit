<?php

namespace App\Http\Controllers;

use App\Models\Investor;
use Illuminate\Http\Request;
use Log;

class FileUploadController extends Controller
{
    // Method to handle file upload
    public function upload(Request $request)
    {
        // Log the incoming request data
        Log::info('File upload request received', ['request_data' => $request->all()]);

        // Validate file types and sizes for passport, ID card (front/back), and license (front/back)
        $request->validate([
            'passport_file' => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
            'id_card_front_file' => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
            'id_card_back_file' => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
            'license_front_file' => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
            'license_back_file' => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
            'residency_photo_path' => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
            'selfie_path' => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
         
        ]);

        // Initialize the paths array to store the file paths
        $paths = [];

         // Upload selfie_path file
         if ($request->hasFile('residency_photo_path')) {
            Log::info('residency file found, uploading...');
            $paths['residency_photo_path'] = $request->file('residency_photo_path')->store('uploads', 'public');
            Log::info('residency file uploaded successfully', ['path' => $paths['residency_photo_path']]);
        }


           // Upload selfie_path file
           if ($request->hasFile('selfie_path')) {
            Log::info('Selfie file found, uploading...');
            $paths['selfie_path'] = $request->file('selfie_path')->store('uploads', 'public');
            Log::info('Selfie file uploaded successfully', ['path' => $paths['selfie_path']]);
        }

           // Upload Passport file
           if ($request->hasFile('passport_file')) {
            Log::info('Passport file found, uploading...');
            $paths['passport_photo_path'] = $request->file('passport_file')->store('uploads', 'public');
            Log::info('Passport file uploaded successfully', ['path' => $paths['passport_photo_path']]);
        }


        // Upload Passport file
        if ($request->hasFile('passport_file')) {
            Log::info('Passport file found, uploading...');
            $paths['passport_photo_path'] = $request->file('passport_file')->store('uploads', 'public');
            Log::info('Passport file uploaded successfully', ['path' => $paths['passport_photo_path']]);
        }

        // Upload ID card front file
        if ($request->hasFile('id_card_front_file')) {
            Log::info('ID card front file found, uploading...');
            $paths['front_photo_path'] = $request->file('id_card_front_file')->store('uploads', 'public');
            Log::info('ID card front file uploaded successfully', ['path' => $paths['front_photo_path']]);
        }

        // Upload ID card back file
        if ($request->hasFile('id_card_back_file')) {
            Log::info('ID card back file found, uploading...');
            $paths['back_photo_path'] = $request->file('id_card_back_file')->store('uploads', 'public');
            Log::info('ID card back file uploaded successfully', ['path' => $paths['back_photo_path']]);
        }

        // Upload License front file
        if ($request->hasFile('license_front_file')) {
            Log::info('License front file found, uploading...');
            $paths['license_front_photo_path'] = $request->file('license_front_file')->store('uploads', 'public');
            Log::info('License front file uploaded successfully', ['path' => $paths['license_front_photo_path']]);
        }

        // Upload License back file
        if ($request->hasFile('license_back_file')) {
            Log::info('License back file found, uploading...');
            $paths['license_back_photo_path'] = $request->file('license_back_file')->store('uploads', 'public');
            Log::info('License back file uploaded successfully', ['path' => $paths['license_back_photo_path']]);
        }

        // Return the file paths in the response to be used later
        return response()->json([
            'status' => 'success',
            'message' => 'Files uploaded successfully!',
            'paths' => $paths // Pass the file paths to be used later for saving
        ]);
    }

    // Method to save investor data (will be called after the upload is successful)
    

    public function saveInvestorData(Request $request)
{
    // Let's assume you have the log data in the request
    $ismailData = $request->ismail;

    // If it's a JSON string, decode it into an array or object
    $ismailData = json_decode($ismailData, true);  // Use true for an associative array

    // Find the investor by ID
    $investor = Investor::find($request->investor_id);
    if (!$investor) {
        Log::error('Investor not found', ['investor_id' => $request->investor_id]);
        return response()->json(['status' => 'error', 'message' => 'Investor not found.'], 404);
    }

    // Get the KYC request (or create one if it doesn't exist)
    $kycrequest = $investor->user->kycRequest ?? new KycRequest(['user_id' => $investor->user->id]);

    // Loop through all the paths in the decoded data
    if (isset($ismailData['paths']) && is_array($ismailData['paths'])) {
        foreach ($ismailData['paths'] as $field => $value) {
            if ($value !== null) {
                // Dynamically set the field if it has a value
                $kycrequest->$field = $value;
            }
        }
    }

    // Save the updated or newly created KYC request
    $kycrequest->save();

    Log::info('Investor data saved successfully', ['investor_id' => $investor->id]);

    return response()->json([
        'status' => 'success',
        'message' => 'Investor data saved successfully!',
    ]);
}


}

