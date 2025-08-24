<?php

namespace App\Http\Controllers;

use App\Models\ContractList;
use App\Models\Investor;
use App\Models\KYCRequest;
use App\Models\Responsible;
use App\Models\Transaction;
use App\Models\User;
use Hassanhelfi\NumberToArabic\NumToArabic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use setasign\Fpdi\Fpdi;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class InvestorController extends Controller
{


    public function index(Request $request)
    {
        $role = $request->query('role'); // Get the role from query parameters
        $eligibility = $request->query('eligible'); // Get eligibility status from query parameters
        $myInvestors = $request->query('myInvestors'); // Get myInvestors status from query parameters
        $search = $request->query('search');
        $phone = $request->query('phone');
        $gender = $request->query('gender');
        $kycStatus = $request->query('kyc_status');
        $contractSigned = $request->query('contract_signed');
        $referralsMin = $request->query('referrals_min');
        $orderBy = $request->query('order_by');
        // Start building the query for investors
        $investorsQuery = Investor::query();

        // Filter by role if provided
        if ($role) {
            $investorsQuery->whereHas('user.roles', function ($query) use ($role) {
                $query->where('name', $role);
            });
        }

        // Check if $myInvestors is true
        if ($myInvestors) {
            $employee = auth()->user()->employe;  // might be null or an Employe instance

            if ($employee) {
                // Get all user IDs who are "investors" under the current employee
                $investorUserIDs = Responsible::where('employe_id', $employee->id)->pluck('investor_id');

                // Filter the Investor model by matching user_id in the investor table
                $investorsQuery->whereIn('user_id', $investorUserIDs);
            }
        }


        // Fetch the affiliate stage for the specified role
        $affiliateStage = null;
        if ($role) {
            $affiliateStage = \App\Models\AffiliateStage::with('role')
                ->whereHas('role', function ($query) use ($role) {
                    $query->where('name', $role);
                })
                ->first();
        }

        // Apply eligibility filter based on affiliate stage
        if ($eligibility !== null && $affiliateStage) {
            $investorsQuery->whereHas('user', function ($query) use ($affiliateStage, $eligibility) {
                $query->where(function ($subQuery) use ($affiliateStage) {
                    // Check team size
                    $subQuery->whereHas('referrals', function ($q) use ($affiliateStage) {
                        $q->havingRaw('COUNT(*) >= ?', [$affiliateStage->team_size]);
                    });

                    // Check capital
                    $subQuery->orWhereHas('wallet', function ($q) use ($affiliateStage) {
                        $q->where('capital', '>=', $affiliateStage->capital);
                    });

                    // Check KYC age
                    $subQuery->orWhereHas('kycRequest', function ($q) {
                        $q->whereRaw('DATEDIFF(NOW(), created_at) >= 30');
                    });
                });

                // Filter based on eligibility value
                if ($eligibility) {
                    $query->havingRaw('1 = 1'); // Eligible logic (if all conditions are true)
                } else {
                    $query->havingRaw('0 = 1'); // Not eligible logic (if any condition fails)
                }
            });
        }

        // Search filter
        // Search by Name or Email
        if ($search) {
            $investorsQuery->whereHas('user', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Search by Phone Number
        if ($phone) {
            $investorsQuery->whereHas('user', function ($query) use ($phone) {
                $query->where('phone', 'like', "%{$phone}%");
            });
        }



        // Filter by Gender
        if ($gender) {
            $investorsQuery->whereHas('user', function ($query) use ($gender) {
                $query->where('gender', $gender);
            });
        }

        // Filter by KYC Status
        if ($kycStatus) {
            $investorsQuery->whereHas('user.kycRequest', function ($query) use ($kycStatus) {
                $query->where('status', $kycStatus);
            });
        }


        // Filter by Number of Referrals
        if ($referralsMin) {
            $investorsQuery->whereHas('user.referrals', function ($query) use ($referralsMin) {
                $query->havingRaw('COUNT(*) >= ?', [$referralsMin]);
            });
        }

        // Order By
        if ($orderBy) {
            $investorsQuery->join('users', 'investors.user_id', '=', 'users.id');
            if ($orderBy == 'name_asc') {
                $investorsQuery->orderBy('users.name', 'asc');
            } elseif ($orderBy == 'name_desc') {
                $investorsQuery->orderBy('users.name', 'desc');
            } elseif ($orderBy == 'email_asc') {
                $investorsQuery->orderBy('users.email', 'asc');
            } elseif ($orderBy == 'email_desc') {
                $investorsQuery->orderBy('users.email', 'desc');
            }
        }


        // Paginate the results
        $investors = $investorsQuery->paginate(16);

        // Fetch roles for filtering
        $roles = \Spatie\Permission\Models\Role::whereNotIn('name', ['admin', 'employee'])->get();

        return view('backend.investors.investors', compact('investors', 'roles', 'role'));
    }




    // public function index()
    // {
    //     $investors = Investor::paginate(16);
    //     return view('backend.investors.investors', compact('investors'));
    // }

    public function detailId($id)
    {
        $investor = Investor::find($id);
        return view('backend.investors.details', compact('investor'));
    }



    public function editContract($investorId)
    {
        $contract = getActiveContract()
            ? public_path('storage/' . getActiveContract()->file)
            : public_path('contract.pdf');

        $logoPath = public_path('storage/' . getSettingValue('logo'));

        // Initialize mPDF
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => storage_path('app/mpdf_temp'),
            'fontDir' => array_merge($fontDirs, [
                public_path('fonts'), // Directory where your custom fonts are stored
            ]),
            'fontdata' => $fontData + [
                'cairo' => [ // Font name must be lowercase
                    'R' => 'Cairo-Regular.ttf',
                    'B' => 'Cairo-Bold.ttf',
                    'useOTL' => 0xFF,       // Enable advanced OpenType layout
                    'useKashida' => 75,     // Enable kashida justification for Arabic text
                ],
            ],
            'default_font' => 'cairo', // Set Cairo as the default font
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'default_font_size' => 10,
            'margin_top' => 60,      // Significantly increased top margin in mm
            'margin_right' => 20,    // Right margin in mm
            'margin_bottom' => 55,   // Bottom margin in mm - increased to make room for footer
            'margin_left' => 20,     // Left margin in mm
            'margin_header' => 15,   // Header margin in mm
            'margin_footer' => 30    // Footer margin in mm - increased to prevent overlap
        ]);

        $mpdf->allow_charset_conversion = true;
        $mpdf->SetDirectionality('rtl'); // Set RTL for Arabic text

        // Add global stylesheet
        $stylesheet = "
            body {
                font-family: Cairo;
                line-height: 1.6;
                padding-top: 20px;
                padding-bottom: 40px;
            }
            h2, h3 {
                margin-top: 15px;
                margin-bottom: 10px;
            }
            p {
                margin-bottom: 8px;
            }
            .container {
                padding: 15px;
            }
        ";
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

        // Define the footer HTML that will appear on all pages
        $footerText = "
        <div style='text-align: center; font-family: cairo; font-size: 12px; padding-top: 15px; margin-top: 20px; border-top: 1px solid #cccccc;'>
            <p>حرر بتاريخ: " . now()->format('Y-m-d') . "</p>
        </div>
        <div style='width: 100%; margin-top: 15px;'>
            <div style='width: 40%; text-align: left; float: left;'>
                <p><span style='border-bottom: 1px solid #000; display: inline-block; width: 150px;'></span></p>
                <p style='margin-top: 5px;'>امضاء المستثمر</p>
            </div>
            <div style='width: 40%; text-align: right; float: right;'>
                <p><span style='border-bottom: 1px solid #000; display: inline-block; width: 150px;'></span></p>
                <p style='margin-top: 5px;'>امضاء و ختم الادارة</p>
            </div>
            <div style='clear: both;'></div>
        </div>
        ";

        // Set the HTML footer - this will appear on all pages
        // $mpdf->SetHTMLFooter($footerText);

        // Add a header with the logo - positioned with more space
        $headerLogo = "
        <div style='text-align: center; padding: 10px 0 20px 0;'>
            <img src='{$logoPath}' width='50' style='max-width: 100%; margin-bottom: 10px;'>
            <div style='font-family: Cairo; font-size: 14px; margin-top: 10px; padding: 0 20px;'>" . getSettingValue('site_description') . "</div>
        </div>
        ";
        // $mpdf->SetHTMLHeader($headerLogo);

        // Load and import the PDF (contract template)
        $pageCount = $mpdf->SetSourceFile($contract);

        // Get investor details
        $investor = Investor::where('user_id', $investorId)->first();
        $user = $investor->user ?? null;

        if (!$user) {
            // Handle error - investor not found
            return back()->with('error', 'Investor not found');
        }

        $investorCode = $investor->id ?? '';
        $investorName = $user->name ?? '';
        $investorNin = $user->nin ?? '';
        $investorPhone = $user->phone ?? '';
        $investorWhatsapp = $user->whatsapp ?? '';
        $investorEmail = $user->email ?? '';
        $investorCapital = ($user->wallet->capital) ?? 0;
        $capitalCleaned = (int) filter_var($investorCapital, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $investorAddress = $user->address ?? '';
        $arabic = NumToArabic::number2Word(floor($capitalCleaned));

        // Retrieve all plans
        $plans = \App\Models\PricingPlan::all();

        // Find the applicable plan
        $applicablePlan = null;
        foreach ($plans as $plan) {
            if ($investorCapital >= $plan->min_amount) {
                $applicablePlan = $plan;
            }
        }

        $investorPlan = $applicablePlan ? number_format($applicablePlan->percentage) : 0;
        $typeOfDocument = $user->kycRequest->document_type ?? '';

        $documentTranslations = [
            'id_card' => 'الحامل لبطاقة الهوية',
            'passport' => 'الحامل لجواز السفر',
            'driving_license' => 'الحامل لرخصة القيادة',
        ];

        $documentPhrase = $documentTranslations[$typeOfDocument] ?? 'الحامل لوثيقة غير معروفة';

        $total_capital_tax = $applicablePlan ? ($investorCapital * ($applicablePlan->percentage / 100)) : 0;
        $formattedInvestorCapital = number_format($investorCapital);
        $formattedCapitalTax = number_format($total_capital_tax);

        // Additional Arabic content to append - with improved styling and more spacing
        $additionalContent = "
        <div style='text-align: right; font-family: Cairo; font-size: 12px; margin-top: 30px; margin-bottom: 30px; padding: 15px; background-color: #f9f9f9; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);'>
            <h2 style='margin-bottom: 20px; font-size: 16px;'>و بصفتك أنت السيد(ة): $investorName</h2>

            <div style='display: flex; justify-content: space-between; margin-bottom: 20px;'>
                <div style='width: 45%;'>
                    <h3 style='margin-bottom: 15px; font-size: 14px;'>$documentPhrase رقم : $investorNin</h3>
                </div>
                <div style='width: 45%;'>
                    <h3 style='margin-bottom: 12px; font-size: 14px;'>البريد الالكتروني: $investorEmail</h3>
                    <h3 style='margin-bottom: 12px; font-size: 14px;'>رقم الواتساب: $investorPhone</h3>
                    <h3 style='margin-bottom: 12px; font-size: 14px;'>العنوان: $investorAddress</h3>
                </div>
            </div>

            <h3 style='margin: 20px 0; font-size: 14px; line-height: 1.6;'>تتعهد أنك فتحت حساب مستثمر أونلاين عن طريق الموقع، و أنك اطلعت و وافقت على سياسة الخصوصية و شروط العمل و موافق على شروط هذا العقد</h3>

            <div style='background-color: #f0f0f0; padding: 15px; border-radius: 6px; margin: 20px 0; border-right: 4px solid #4a90e2;'>
                <h3 style='margin-bottom: 12px; font-size: 14px;'>حساب $investorCode مساهم برأس مال قدره: $formattedInvestorCapital دولار</h3>
                <h3 style='margin-bottom: 12px; font-size: 14px;'>بالاحرف : $arabic دولار</h3>
            </div>

            <div style='background-color: #f0f0f0; padding: 15px; border-radius: 6px; border-right: 4px solid #4a90e2;'>
                <h3 style='margin-bottom: 12px; font-size: 14px;'>أرباحك الشهرية المستهدفة تعادل %$investorPlan من رأس مالك أي ما يعادل : $formattedCapitalTax دولار</h3>
            </div>
        </div>
        ";

        // Get contract content
        $contractContent = getUserContract($user);

        // Process each page
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            // Import the page from template
            $templateId = $mpdf->ImportPage($pageNo);

            // Use the template
            $mpdf->UseTemplate($templateId);

            // Add spacer to ensure content starts well below any borders
            $mpdf->WriteHTML("<div style='height: 15px;'></div>");

            // Add the contract content with more spacing
            if ($contractContent && !empty($contractContent->content)) {
                // Wrap contract content in a div with padding
                $mpdf->WriteHTML("<div class='container'>");
                $mpdf->WriteHTML($contractContent->content);
                $mpdf->WriteHTML("</div>");
            }

            // Append the additional investor content on the first page only
            if ($pageNo == 1) {
                // Add spacer before the investor info section
                // $mpdf->WriteHTML("<div style='height: 15px;'></div>");
                // $mpdf->WriteHTML($additionalContent);
            }

            // Add spacer before end of page to ensure no footer overlap
            $mpdf->WriteHTML("<div style='height: 20px;'></div>");

            // If it's not the last page, add a new page
            if ($pageNo < $pageCount) {
                $mpdf->AddPage();
            }
        }

        // Output the final PDF
        $mpdf->Output('contract_edited.pdf', 'I');
    }

    // end


    //    contract generated by user to be sent


    public function sendContract(Request $request)
    {
        // Define the contracts directory path
        $contractsDir = storage_path('app/public/contracts');
        if (!File::exists($contractsDir)) {
            File::makeDirectory($contractsDir, 0755, true);
        }

        // Retrieve the active contract or default
        $contract = getActiveContract()
            ? public_path('storage/' . getActiveContract()->file)
            : public_path('contract.pdf');

        $logoPath = public_path('storage/' . getSettingValue('logo'));

        // Initialize mPDF with same configuration as editContract
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => storage_path('app/mpdf_temp'),
            'fontDir' => array_merge($fontDirs, [
                public_path('fonts'),
            ]),
            'fontdata' => $fontData + [
                'cairo' => [
                    'R' => 'Cairo-Regular.ttf',
                    'B' => 'Cairo-Bold.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
            ],
            'default_font' => 'cairo',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'default_font_size' => 10,
            'margin_top' => 60,
            'margin_right' => 20,
            'margin_bottom' => 55,
            'margin_left' => 20,
            'margin_header' => 15,
            'margin_footer' => 30
        ]);

        $mpdf->allow_charset_conversion = true;
        $mpdf->SetDirectionality('rtl');

        // Add global stylesheet (same as editContract)
        $stylesheet = "
            body {
                font-family: Cairo;
                line-height: 1.6;
                padding-top: 20px;
                padding-bottom: 40px;
            }
            h2, h3 {
                margin-top: 15px;
                margin-bottom: 10px;
            }
            p {
                margin-bottom: 8px;
            }
            .container {
                padding: 15px;
            }
        ";
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

        // Define and set footer (same as editContract)
        $footerText = "
        <div style='text-align: center; font-family: cairo; font-size: 12px; padding-top: 15px; margin-top: 20px; border-top: 1px solid #cccccc;'>
            <p>حرر بتاريخ: " . now()->format('Y-m-d') . "</p>
        </div>
        <div style='width: 100%; margin-top: 15px;'>
            <div style='width: 40%; text-align: left; float: left;'>
                <p><span style='border-bottom: 1px solid #000; display: inline-block; width: 150px;'></span></p>
                <p style='margin-top: 5px;'>امضاء المستثمر</p>
            </div>
            <div style='width: 40%; text-align: right; float: right;'>
                <p><span style='border-bottom: 1px solid #000; display: inline-block; width: 150px;'></span></p>
                <p style='margin-top: 5px;'>امضاء و ختم الادارة</p>
            </div>
            <div style='clear: both;'></div>
        </div>
        ";
        // $mpdf->SetHTMLFooter($footerText);

        // Add header (same as editContract)
        $headerLogo = "
        <div style='text-align: center; padding: 10px 0 20px 0;'>
            <img src='{$logoPath}' width='50' style='max-width: 100%; margin-bottom: 10px;'>
            <div style='font-family: Cairo; font-size: 14px; margin-top: 10px; padding: 0 20px;'>" . getSettingValue('site_description') . "</div>
        </div>
        ";
        // $mpdf->SetHTMLHeader($headerLogo);

        // Load and import the PDF
        $pageCount = $mpdf->SetSourceFile($contract);

        // Fetch investor details
        $investor = Investor::where('user_id', $request->investorId)->with('user')->first();
        if (!$investor || !$investor->user) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Investor not found'], 404);
            }
            return redirect()->back()->with('error', 'Investor not found.');
        }

        // Investor data preparation (same as editContract)
        $investorCode = $investor->id ?? '';
        $investorName = $investor->user->name ?? '';
        $investorNin = $investor->user->nin ?? '';
        $investorPhone = $investor->user->phone ?? '';
        $investorWhatsapp = $investor->user->whatsapp ?? '';
        $investorEmail = $investor->user->email ?? '';
        $investorCapital = ($investor->user->wallet->capital) ?? 0;
        $capitalCleaned = (int) filter_var($investorCapital, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $investorAddress = $investor->user->address ?? '';
        $arabic = NumToArabic::number2Word(floor($capitalCleaned));

        $plans = \App\Models\PricingPlan::all();
        $applicablePlan = null;
        foreach ($plans as $plan) {
            if ($investorCapital >= $plan->min_amount) {
                $applicablePlan = $plan;
            }
        }

        $investorPlan = $applicablePlan ? number_format($applicablePlan->percentage) : 0;
        $typeOfDocument = $investor->user->kycRequest->document_type ?? '';

        $documentTranslations = [
            'id_card' => 'الحامل لبطاقة الهوية',
            'passport' => 'الحامل لجواز السفر',
            'driving_license' => 'الحامل لرخصة القيادة',
        ];
        $documentPhrase = $documentTranslations[$typeOfDocument] ?? 'الحامل لوثيقة غير معروفة';

        $total_capital_tax = $applicablePlan ? ($investorCapital * ($applicablePlan->percentage / 100)) : 0;
        $formattedInvestorCapital = number_format($investorCapital);
        $formattedCapitalTax = number_format($total_capital_tax);

        // Additional content (same styling as editContract)
        $additionalContent = "
        <div style='text-align: right; font-family: Cairo; font-size: 12px; margin-top: 30px; margin-bottom: 30px; padding: 15px; background-color: #f9f9f9; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);'>
            <h2 style='margin-bottom: 20px; font-size: 16px;'>و بصفتك أنت السيد(ة): $investorName</h2>

            <div style='display: flex; justify-content: space-between; margin-bottom: 20px;'>
                <div style='width: 45%;'>
                    <h3 style='margin-bottom: 15px; font-size: 14px;'>$documentPhrase رقم : $investorNin</h3>
                </div>
                <div style='width: 45%;'>
                    <h3 style='margin-bottom: 12px; font-size: 14px;'>البريد الالكتروني: $investorEmail</h3>
                    <h3 style='margin-bottom: 12px; font-size: 14px;'>رقم الواتساب: $investorPhone</h3>
                    <h3 style='margin-bottom: 12px; font-size: 14px;'>العنوان: $investorAddress</h3>
                </div>
            </div>

            <h3 style='margin: 20px 0; font-size: 14px; line-height: 1.6;'>تتعهد أنك فتحت حساب مستثمر أونلاين عن طريق الموقع، و أنك اطلعت و وافقت على سياسة الخصوصية و شروط العمل و موافق على شروط هذا العقد</h3>

            <div style='background-color: #f0f0f0; padding: 15px; border-radius: 6px; margin: 20px 0; border-right: 4px solid #4a90e2;'>
                <h3 style='margin-bottom: 12px; font-size: 14px;'>حساب $investorCode مساهم برأس مال قدره: $formattedInvestorCapital دولار</h3>
                <h3 style='margin-bottom: 12px; font-size: 14px;'>بالاحرف : $arabic دولار</h3>
            </div>

            <div style='background-color: #f0f0f0; padding: 15px; border-radius: 6px; border-right: 4px solid #4a90e2;'>
                <h3 style='margin-bottom: 12px; font-size: 14px;'>أرباحك الشهرية المستهدفة تعادل %$investorPlan من رأس مالك أي ما يعادل : $formattedCapitalTax دولار</h3>
            </div>
        </div>
        ";

        // Process each page (same as editContract)
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $mpdf->ImportPage($pageNo);
            $mpdf->UseTemplate($templateId);

            $mpdf->WriteHTML("<div style='height: 15px;'></div>");

            $contractContent = getUserContract($investor->user);
            if ($contractContent && !empty($contractContent->content)) {
                $mpdf->WriteHTML("<div class='container'>");
                $mpdf->WriteHTML($contractContent->content);
                $mpdf->WriteHTML("</div>");
            }

            if ($pageNo == 1) {
                $mpdf->WriteHTML("<div style='height: 15px;'></div>");
                // $mpdf->WriteHTML($additionalContent);
            }

            $mpdf->WriteHTML("<div style='height: 20px;'></div>");

            if ($pageNo < $pageCount) {
                $mpdf->AddPage();
            }
        }

        // Generate and save PDF
        $pdfName = 'contract_' . Str::slug($investor->user->name) . '_' . now()->timestamp . '.pdf';
        $pdfPath = 'contracts/' . $pdfName;
        $mpdf->Output(storage_path('app/public/' . $pdfPath), \Mpdf\Output\Destination::FILE);

        // Contract list handling (unchanged from original)
        $existingContract = ContractList::where('user_id', $request->investorId)->first();
        if ($existingContract) {
            if ($existingContract->user_role !== $investor->user->roles->pluck('id')->first() || is_null($existingContract->user_role)) {
                $contractEntry = ContractList::create([
                    'user_id' => $request->investorId,
                    'user_role' => $investor->user->roles->pluck('id')->first(),
                    'pdf_name' => $pdfName,
                    'pdf_path' => $pdfPath,
                    'signature_user' => null,
                    'signature_pdf_company' => '',
                    'status' => 'pending',
                ]);
            } else {
                $existingContract->update([
                    'pdf_name' => $pdfName,
                    'pdf_path' => $pdfPath,
                    'signature_user' => null,
                    'signature_pdf_company' => '',
                    'status' => 'pending',
                ]);
                $contractEntry = $existingContract;
            }
        } else {
            $contractEntry = ContractList::create([
                'user_id' => $request->investorId,
                'user_role' => $investor->user->roles->pluck('id')->first(),
                'pdf_name' => $pdfName,
                'pdf_path' => $pdfPath,
                'signature_user' => null,
                'signature_pdf_company' => '',
                'status' => 'pending',
            ]);
        }

        // KYC and email handling (unchanged from original)
        $kycRequest = KYCRequest::where('user_id', $investor->user->id)->firstOrFail();
        $kycRequest->update(['status' => 'approved', 'is_signed' => 1]);

        $data = [
            'user' => $investor->user,
        ];


        // sendEmail($investor->user->email, 'kyc_processing_contract_available', $data);
        sendEmail($investor->user->email, 'send_contract', $data);
        // Return response (unchanged from original)
        if ($request->ajax()) {
            return response()->json([
                'message' => 'Contract sent and saved successfully. and email send',
                'contract' => $contractEntry,
                'pdf_url' => asset('storage/' . $pdfPath),
            ], 201);
        }

        return redirect()->back()->with('success', 'Contract sent and saved successfully.');
    }

    // end


    public function viewContract($contractId)
    {


        // Retrieve the contract entry
        $contract = ContractList::findOrFail($contractId);

        // Ensure the contract belongs to the authenticated user or has necessary permissions
        // Implement authorization logic here as needed

        // Generate the URL to the PDF
        $pdfUrl = asset('storage/' . $contract->pdf_path);

        return view('backend.contracts.sign', compact('contract', 'pdfUrl'));
    }


    public function showMonthlyStatement($investorId)
    {
        $investor = Investor::where('user_id', auth()->user()->id)->first();

        if (!$investor || auth()->user()->id != $investorId) { // Adjust authorization as needed
            abort(403, 'You do not have permission to access this page.');
        }

        $fileName = "monthly_statement_{$investorId}_" . now()->format('Y_m') . ".pdf";
        $filePath = storage_path("app/public/{$fileName}");

        // Always regenerate the PDF
        $this->generateMonthlyStatement($investorId);

        return view('backend.investors.statement', ['fileName' => $fileName, 'investorId' => $investorId]);
    }
    public function generateMonthlyStatement($investorId)
    {
        $investor = Investor::where('user_id', $investorId)->first();
        $user = $investor->user ?? null;

        if (!$user) {
            return back()->with('error', 'Investor not found');
        }

        // Define the path to your existing PDF template
        $templatePath = public_path('AMWALFLOO.pdf');

        // Initialize mPDF
        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => storage_path('app/mpdf_temp'),
            'fontDir' => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], [
                public_path('fonts'),
            ]),
            'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
                'cairo' => [
                    'R' => 'Cairo-Regular.ttf',
                    'B' => 'Cairo-Bold.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
            ],
            'default_font' => 'cairo',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'default_font_size' => 10,
            'margin_top' => 30,
            'margin_right' => 20,
            'margin_bottom' => 30,
            'margin_left' => 20,
        ]);

        $mpdf->SetDirectionality('rtl');

        // Load the existing PDF template
        if (file_exists($templatePath)) {
            $pageCount = $mpdf->SetSourceFile($templatePath);
        } else {
            return back()->with('error', 'PDF template not found');
        }

        // Get transactions for the current month
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        $transactions = Transaction::where('user_id', $investorId)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->get();

        // Prepare table content
        $tableRows = '';
        foreach ($transactions as $transaction) {
            $tableRows .= "
                <tr>
                    <td>{$transaction->id}</td>
                    <td>{$transaction->created_at->format('Y-m-d')}</td>
                    <td>{$transaction->type}</td>
                    <td>" . number_format($transaction->amount) . " دولار</td>
                    <td>{$transaction->description}</td>
                </tr>
            ";
        }

        // HTML content with absolute positioning
        $content = "
            <style>
                body {
                    font-family: Cairo;
                    direction: rtl;
                    line-height: 1.5;
                }
                .content-wrapper {
                    position: absolute;
                    top: 250px; /* Starts 150px from the top */
                    left: 20px;
                    right: 20px;
                    padding: 20px;
                    max-width: 90%;
                    margin-left: auto;
                    margin-right: auto;
                }
                .header {
                    text-align: center;
                    margin-bottom: 25px;
                    padding-bottom: 15px;
                    border-bottom: 1px solid #ddd;
                }
                .header h2 {
                    font-size: 16pt;
                    margin: 0 0 10px 0;
                    color: #333;
                }
                .header p {
                    font-size: 11pt;
                    margin: 5px 0;
                    color: #555;
                }
                .table-container {
                    width: 100%;
                    display: flex;
                    justify-content: center;
                    margin: 20px 0;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    background-color: #fff;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                }
                th, td {
                    border: 1px solid #ccc;
                    padding: 10px;
                    text-align: center;
                    font-size: 10pt;
                }
                th {
                    background-color: #f5f5f5;
                    font-weight: bold;
                    color: #333;
                }
                td {
                    color: #666;
                }
                .footer {
                    text-align: center;
                    margin-top: 25px;
                    padding-top: 15px;
                    border-top: 1px solid #ddd;
                    font-size: 10pt;
                    color: #777;
                }
            </style>

            <div class='content-wrapper'>
                <div class='header'>
                    <h2>كشف الحساب الشهري</h2>
                    <p>الاسم: {$user->name}</p>
                    <p>رقم المستثمر: {$investor->id}</p>
                    <p>الشهر: " . now()->format('F Y') . "</p>
                </div>

                <div class='table-container'>
                    <table>
                        <thead>
                            <tr>
                                <th>رقم المعاملة</th>
                                <th>التاريخ</th>
                                <th>النوع</th>
                                <th>المبلغ</th>
                                <th>الوصف</th>
                            </tr>
                        </thead>
                        <tbody>
                            " . ($tableRows ?: '<tr><td colspan="5">لا توجد معاملات لهذا الشهر</td></tr>') . "
                        </tbody>
                    </table>
                </div>

                <div class='footer'>
                    <p>تم إنشاء هذا الكشف بتاريخ: " . now()->format('Y-m-d') . "</p>
                </div>
            </div>
        ";

        // Process each page of the template
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $mpdf->ImportPage($pageNo);
            $mpdf->UseTemplate($templateId);

            if ($pageNo == 1) {
                $mpdf->WriteHTML($content);
            }

            if ($pageNo < $pageCount) {
                $mpdf->AddPage();
            }
        }

        // Define the PDF filename
        $fileName = "monthly_statement_{$investorId}_" . now()->format('Y_m') . ".pdf";
        $filePath = storage_path("app/public/{$fileName}");

        // Delete the old file if it exists to ensure a fresh version
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Save the new PDF
        $mpdf->Output($filePath, 'F');

        return response()->json(['file' => $fileName]);
    }



    /**
     * Save the user's signature and embed it into the PDF.
     */
    public function saveSignature(Request $request, $contractId)
    {
        // Validate the incoming request
        $request->validate([
            'signature' => 'required|string', // Expecting a base64 encoded image
        ]);

        // Retrieve the contract entry
        $contract = ContractList::findOrFail($contractId);

        // Decode the signature image
        $signatureData = $request->input('signature');
        $signatureImage = $this->decodeSignature($signatureData);

        if (!$signatureImage) {
            return response()->json(['error' => 'Invalid signature data.'], 400);
        }

        // Define the path to save the signature image
        $signatureName = 'signature_' . Str::random(10) . '.png';
        $signaturePath = 'signatures/' . $signatureName;

        // Ensure the signatures directory exists
        if (!Storage::disk('public')->exists('signatures')) {
            Storage::disk('public')->makeDirectory('signatures');
        }

        // Save the signature image
        Storage::disk('public')->put($signaturePath, $signatureImage);

        // Update the contract entry with the signature path
        $contract->signature_user = $signaturePath;
        $contract->status = 'signed'; // Update status as needed
        $contract->save();

        // Embed the signature into the PDF
        $updatedPdfPath = $this->embedSignatureIntoPdf($contract->pdf_path, $signaturePath, $contractId);

        if (!$updatedPdfPath) {
            return response()->json(['error' => 'Failed to embed signature into PDF.'], 500);
        }

        // Update the pdf_path if the PDF was modified
        $contract->pdf_path = $updatedPdfPath;
        $contract->save();

        $kycRequest = KYCRequest::where('user_id', Auth::user()->id)->first();
        $kycRequest->update(['is_signed' => true]);

        return response()->json(['message' => 'Signature saved and embedded successfully.'], 200);
    }

    /**
     * Decode the base64 signature image.
     */
    private function decodeSignature($data)
    {
        // Extract base64 data
        if (preg_match('/data:image\/png;base64,(.+)/', $data, $matches)) {
            return base64_decode($matches[1]);
        }

        return false;
    }

    /**
     * Embed the signature image into the PDF.
     */
    private function embedSignatureIntoPdf($pdfPath, $signaturePath, $contractId)
    {
        try {
            // Define storage paths
            $originalPdf = storage_path('app/public/' . $pdfPath);
            $signatureImage = storage_path('app/public/' . $signaturePath);
            $newPdfName = 'signed_contract_' . $contractId . '.pdf';
            $newPdfPath = 'contracts/' . $newPdfName;

            // Initialize FPDI
            $fpdi = new Fpdi();

            // Set the source file
            $pageCount = $fpdi->setSourceFile($originalPdf);

            // Loop through each page and import it
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $fpdi->AddPage();
                $templateId = $fpdi->importPage($pageNo);
                $fpdi->useTemplate($templateId);

                // On the last page, add the signature
                if ($pageNo === $pageCount) {
                    // Define signature placement coordinates
                    $x = 0; // Adjust as needed
                    $y = 260; // Adjust as needed
                    $width = 70; // Adjust as needed

                    // Add the signature image
                    $fpdi->Image($signatureImage, $x, $y, $width);
                }
            }

            // Ensure the contracts directory exists
            if (!Storage::disk('public')->exists('contracts')) {
                Storage::disk('public')->makeDirectory('contracts');
            }

            // Save the new PDF
            $fpdi->Output(storage_path('app/public/' . $newPdfPath), 'F');

            return $newPdfPath;
        } catch (\Exception $e) {
            Log::error('Failed to embed signature into PDF: ' . $e->getMessage());
            return false;
        }
    }

    public function uploadSignedContract(Request $request, $investorId)
    {
        // Validate the incoming request
        $request->validate([
            'signed_contract' => 'required|file|mimes:pdf|max:8192', // Max 2MB
        ], [
            'signed_contract.required' => 'Please upload a PDF file.',
            'signed_contract.mimes' => 'Only PDF files are allowed.',
            'signed_contract.max' => 'The PDF must not exceed 2MB.',
        ]);

        // Retrieve the investor
        $investor = Investor::findOrFail($investorId);

        // Check if the investor has an existing contract entry
        $contract = ContractList::where('user_id', $investor->user_id)->first();

        if (!$contract) {
            return redirect()->back()->with('error', 'No existing contract found for this investor.');
        }

        // Handle the uploaded file
        if ($request->hasFile('signed_contract')) {
            $file = $request->file('signed_contract');

            // Generate a unique file name to prevent overwriting
            $fileName = 'contract_' . $investor->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Store the file in the 'contracts/' directory within the 'public' disk
            $filePath = $file->storeAs('contracts', $fileName, 'public');

            // Update only the pdf_path field in the contract_list table
            $contract->pdf_path = $filePath;
            $contract->save();

            // Update the KYCRequest status to 'completed'


            if ($investor->user->kycRequest) {
                $investor->user->kycRequest->status = 'completed';
                $investor->user->kycRequest->save();
                $investor->user->active = true;
                $investor->user->save();
                $data = ['user' => $investor->user];
                sendEmail($investor->user->email, 'account_accepted', $data);
            }

            return redirect()->back()->with('success', 'Signed contract uploaded successfully.');
        }

        return redirect()->back()->with('error', 'Failed to upload the signed contract. Please try again.');
    }



    // investors panel/
    public function analytics()
    {
        return view('backend.investors.analytics');
    }

    public function deposits()
    {
        return view('backend.investors.deposits');
    }

    public function withdrawals()
    {
        return view('backend.investors.withdrawals');
    }

    public function contracts()
    {
        return view('backend.investors.contract');
    }


    // managers

    public function managers_requests()
    {
        return view('backend.investors.managers.managers_requests');
    }


    public function managerApiIndex()
    {
        return DataTables::of(
            Investor::whereHas('user', function ($query) {
                $query->whereHas('roles', function ($roleQuery) {
                    $roleQuery->where('name', 'manager');
                });
            })
        )
            ->addIndexColumn()
            ->addColumn('investor', function ($row) {
                // Return the name of the associated user, or 'N/A' if no user exists
                return $row->user ? $row->user->name : 'N/A';
            })
            ->addColumn('message', function ($row) {
                // Return the name of the associated user, or 'N/A' if no user exists
                return $row->user ? $row->user->kycRequest->message : 'N/A';
            })
            ->addColumn('status', function ($row) {
                // Return the name of the associated user, or 'N/A' if no user exists
                return $row->user ? $row->user->kycRequest->status : 'N/A';
            })
            ->addColumn('team', function ($row) {
                // Return the name of the associated user, or 'N/A' if no user exists
                return $row->user ? $row->user->referrals->count() : 'N/A';
            })
            ->addColumn('capital', function ($row) {
                // Return the name of the associated user, or 'N/A' if no user exists
                return $row->user ? $row->user->wallet->capital : 'N/A';
            })
            ->make(true);
    }

    public function changeStatus(Request $request)
    {

        $user = User::where('id', $request->user_id)->first();
        $user->nin = $request->nin;
        $user->save();
        $kycRequest = KycRequest::where('id', $user->kycRequest->id)->first();
        $kycRequest->status = $request->status;
        $kycRequest->save();

        $data = [
            'user' => $user,
            // Add other data here if required by specific templates
        ];

        sendEmail($user->email, $request->template, $data);

        return back()->withSuccess('Status updated successfully.');
    }




    public function transactionHistory()
    {
        return view('backend.investors.transactions_history');
    }


        public function transactionHistoryApi(Request $request)
        {


            $userId = $request->query('user_id');

            $transactions = Transaction::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json($transactions);
        }




    public function delete(Request $request)
    {
        try {
            // Find investor with related user and kycRequest
            $investor = Investor::with('user.kycRequest')->find($request->id);

            if (!$investor) {
                return response()->json(['message' => 'Investor not found'], 404);
            }

            // Check if user exists before proceeding
            if ($investor->user) {
                $user = $investor->user;

                // Wrap related data deletion in a transaction
                DB::transaction(function () use ($user) {
                    // Delete KYC request if exists
                    if ($user->kycRequest) {
                        $user->kycRequest->delete();
                    }

                    // Delete related data
                    $user->referralLinks()->delete();
                    $user->wallet()->delete();
                    $user->employe()->delete();

                    // Handle contract deletion if it exists
                    if ($user->contract) {
                        $user->contract->delete();
                    }

                    // Delete the user
                    $user->delete();
                });
            }

            // Delete the investor record
            $investor->delete();

            return response()->json(['message' => 'Investor and related data deleted successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting investor and related data',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function updateCapital(Request $request)
    {
        $validated = $request->validate([
            'investor_id' => 'required|exists:investors,id',
            'capital' => 'required|numeric|min:1'
        ]);

        // Find investor and update capital
        $investor = Investor::with('user.wallet')->findOrFail($validated['investor_id']);

        if (!$investor->user || !$investor->user->wallet) {
            throw ValidationException::withMessages(['error' => 'Wallet not found for this investor.']);
        }

        $investor->user->wallet->capital = $validated['capital'];
        $investor->user->wallet->save();

        return back()->with('success', 'Capital updated successfully.');
    }
    public function updateDuration(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:investors,id',
            'duration' => 'required'
        ]);

        $investor = Investor::find($request->id);

        if (!$investor)
            return back()->withErrors('Investor not found');

        $investor->duration = $request->duration;
        $investor->save();

        return back()->withSucces('Duration updated successfully');
    }

    public function update_comission(Request $request) {

        // dd($request->all());
        $request->validate([
            'percentage' => 'required',
        ]);

        if($request->percentage == 0) return back()->withErrors('comission should be at least 1');

        Investor::where('id', $request->investor_id)->update(['percentage' => $request->percentage]);

        return back()->withSuccess('updated');
    }

}


