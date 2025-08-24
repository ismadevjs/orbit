<?php

namespace App\Http\Controllers;

use App\Models\LogData;
use App\Models\Setting;
use Illuminate\Http\Request;


class SettingController extends Controller
{

    public function maintenance()
    {
        return view('backend.maintenance');
    }

    public function settings()
    {
        $settings = Setting::first();
        return view('backend.settings.settings', compact('settings'));
    }


    public function updateLogo(Request $request)
    {
        $settings = Setting::firstOrCreate([]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $settings->logo = $path;
            $settings->save();
        }

        return redirect()->back()->with('success', 'Logo updated successfully!');
    }

    public function updateLogoWhite(Request $request)
    {
        $settings = Setting::firstOrCreate([]);

        if ($request->hasFile('logo_white')) {
            $path = $request->file('logo_white')->store('logos', 'public');
            $settings->logo_white = $path;
            $settings->save();
        }

        return redirect()->back()->with('success', 'White Logo updated successfully!');
    }

    public function updateFavicon(Request $request)
    {
        $settings = Setting::firstOrCreate([]);

        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('favicons', 'public');
            $settings->favicon = $path;
            $settings->save();
        }

        return redirect()->back()->with('success', 'Favicon updated successfully!');
    }

    public function updateSiteInfo(Request $request)
    {
        $settings = Setting::firstOrCreate([]);

        $settings->site_name = $request->input('site_name');
        $settings->site_description = $request->input('site_description');
        $settings->site_keywords = $request->input('site_keywords');
        $settings->save();

        return redirect()->back()->with('success', 'Site Information updated successfully!');
    }

    public function updateContactInfo(Request $request)
    {
        $settings = Setting::firstOrCreate([]);

        $settings->site_email = $request->input('site_email');
        $settings->site_phone = $request->input('site_phone');
        $settings->whatsapp = $request->input('whatsapp');
        $settings->whatsapp_message = $request->input('whatsapp_message');
        $settings->site_address = $request->input('site_address');
        $settings->save();

        return redirect()->back()->with('success', 'Contact Information updated successfully!');
    }

    public function updateFooter(Request $request)
    {
        // Fetch or create the settings record
        $settings = Setting::firstOrCreate([]);

        // Validate the incoming request data
        $request->validate([
            'footer_text' => 'required|string|max:1000',  // You can adjust the validation rules as needed
            'footer_links' => 'nullable|string|max:1000',
            'footer_big' => 'nullable|string',
        ]);

        // Update the footer settings
        $settings->footer_text = $request->input('footer_text');
        $settings->footer_links = $request->input('footer_links');
        $settings->footer_big = $request->input('footer_big');
        // Save the settings
        $settings->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Footer updated successfully!');
    }

    public function updateApps (Request $request) {
        $settings = Setting::firstOrCreate([]);

        $settings->play_store = $request->input('play_store');
        $settings->app_store = $request->input('app_store');
        $settings->save();

        return redirect()->back()->with('success', 'Apps updated successfully!');
    }


    public function header()
    {
        return view('backend.website_settings.header');
    }

    public function update_header(Request $request)
    {
        return $request;
    }

    public function footer()
    {
        $footerInfo = [
            [
                "status" => true,
                "message" => "",
                'title' => '',
                "data" => [
                    "list" => [
                        [
                            "url" => "/privacy-policy",
                            "title" => "Privacy Policy"
                        ],
                        [
                            "url" => "/terms-of-use",
                            "title" => "Terms Of Use"
                        ],
                        [
                            "url" => "/accessibility",
                            "title" => "Accessibility"
                        ]
                    ],
                    "address" => "445 GRAND AVENUE Dubai, NY 11238",
                    "copyright" => "Copyright © 2024 CrystalView. All Rights Reserved."
                ]
            ]
        ];

        return view('backend.website_settings.footer', compact('footerInfo'));
    }


    public function updateMap(Request $request)
    {
        $settings = Setting::firstOrCreate([]);

        $settings->long = $request->input('long');
        $settings->lat = $request->input('lat');
        $settings->save();

        return redirect()->back()->with('success', 'Map updated successfully!');
    }

    // Footer Info
    public function editInfo()
    {
        // Fetch footer info from the database
        return view('admin.footer.info', compact('footerInfo'));
    }

    public function updateInfo(Request $request)
    {
        // Validate and update footer info
    }

    // About Widget
    public function editAbout()
    {
        // Fetch about info from the database
        return view('admin.footer.about', compact('aboutInfo'));
    }

    public function updateAbout(Request $request)
    {
        // Validate and update about info
    }

    // Contact Info Widget
    public function editContact()
    {
        // Fetch contact info from the database
        return view('admin.footer.contact', compact('contactInfo'));
    }

    public function updateContact(Request $request)
    {
        // Validate and update contact info
    }

    // Link Widget
    public function editLinks()
    {
        // Fetch links from the database
        return view('admin.footer.links', compact('linkWidget', 'links'));
    }

    public function updateLinks(Request $request)
    {
        // Validate and update links
    }

    // Footer Bottom (Copyright)
    public function editCopyright()
    {
        // Fetch copyright info from the database
        return view('admin.footer.copyright', compact('copyrightInfo'));
    }

    public function updateCopyright(Request $request)
    {
        // Validate and update copyright info
    }

    // Social Links Widget
    public function editSocial()
    {
        // Fetch social links from the database
        return view('admin.footer.social', compact('socialLinks'));
    }

    public function updateSocial(Request $request)
    {
        // Validate and update social links
    }

    // Payment Methods Widget
    public function editPayment()
    {
        // Fetch payment info from the database
        return view('admin.footer.payment', compact('paymentInfo'));
    }

    public function updatePayment(Request $request)
    {
        // Validate and update payment info
    }

    public function serverStatus()
    {

        return view('backend.settings.server_status');
    }

    public function logs()
    {
        $logs = LogData::paginate(20);
        return view('backend.settings.logs', compact('logs'));
    }

}
