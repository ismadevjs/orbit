<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class FeatureActivationController extends Controller
{
    public function index()
    {
        $maintenance = Setting::first();
        return view('backend.website_setup.features_activation', compact('maintenance'));
    }

    public function toggleMaintenance(Request $request)
    {
        // Assuming the setting is named 'maintenance' in the settings table
        $setting = Setting::first();

        if (!$setting) {
            return response()->json(['error' => 'Setting not found'], 404);
        }

        // Toggle the maintenance status
        $setting->maintenance = !$setting->maintenance;
        $setting->save();


        return response()->json('Status Changed');
    }
}
