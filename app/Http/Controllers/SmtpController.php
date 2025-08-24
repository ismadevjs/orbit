<?php

namespace App\Http\Controllers;

use App\Mail\InvestorMail;
use App\Mail\TestEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class SmtpController extends Controller
{
    public function index()
    {
//        $user = 'ismail';
//        Mail::to('agencedz213@gmail.com')
//            ->send(new TestEmail($user));
//
//        // Return a response indicating the email was sent
//        return response()->json(['message' => 'Email sent successfully']);

        return view('backend.smtp.smtp');

    }

    public function update(Request $request)
    {
        $request->validate([
            'mail_mailer' => 'required|string',
            'mail_host' => 'required|string',
            'mail_port' => 'required|integer',
            'mail_username' => 'required|string',
            'mail_password' => 'required|string',
            'mail_encryption' => 'required|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
        ]);

        // Load .env file contents
        $envPath = base_path('.env');
        $envContent = File::get($envPath);

        // Update each SMTP setting in the .env file
        $envContent = preg_replace("/^MAIL_MAILER=.*/m", 'MAIL_MAILER=' . $request->mail_mailer, $envContent);
        $envContent = preg_replace("/^MAIL_HOST=.*/m", 'MAIL_HOST=' . $request->mail_host, $envContent);
        $envContent = preg_replace("/^MAIL_PORT=.*/m", 'MAIL_PORT=' . $request->mail_port, $envContent);
        $envContent = preg_replace("/^MAIL_USERNAME=.*/m", 'MAIL_USERNAME=' . $request->mail_username, $envContent);
        $envContent = preg_replace("/^MAIL_PASSWORD=.*/m", 'MAIL_PASSWORD=' . $request->mail_password, $envContent);
        $envContent = preg_replace("/^MAIL_ENCRYPTION=.*/m", 'MAIL_ENCRYPTION=' . $request->mail_encryption, $envContent);
        $envContent = preg_replace("/^MAIL_FROM_ADDRESS=.*/m", 'MAIL_FROM_ADDRESS=' . $request->mail_from_address, $envContent);
        $envContent = preg_replace("/^MAIL_FROM_NAME=.*/m", 'MAIL_FROM_NAME="' . $request->mail_from_name . '"', $envContent);

        // Write updated content back to .env file
        File::put($envPath, $envContent);

        return redirect()->back()->with('success', 'SMTP settings updated successfully.');
    }

    public function send()
    {

        Mail::to('agencedz213@gmail.com')
            ->send(new InvestorMail());


        return back()->withSuccess('Email sent successfully');


    }
}
