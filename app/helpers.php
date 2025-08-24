<?php

use App\Models\Incentive;
use App\Models\Investor;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\Type;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\IncentiveInvestor;
use App\Models\LogData;
use Jenssegers\Agent\Agent;


if (!function_exists('required')) {
    function required()
    {
        echo '<span class="text-danger">*</span>';
    }
}

if (!function_exists('gender')) {
    function gender()
    {
        return [
            "male",
            "female"
        ];
    }
}


if (!function_exists('routes_list')) {
    function routes_list()
    {
        return [
            "dashboard",
            "brands",
            "colors",
            'employees',
            "features",
            "facilities",
            'types',
            "headings",
            'affiliates',
            'contract_type',
            'contracts',
            'contract_themes',
            'sounds',
            'aksams',
            'jobs',
            "reviews",
            "carousel",
            "services",
            "leads",
            "deals",
            "categories",
            "posts",
            "videos",
            "contacts",
            "testimonials",
            'members',
            "socials",
            "faqs",
            'incentives',
            'plans',
            "tags",
            "permissions",
            "pages",
            'images',
            "roles",
            'currencies',
            'tax',
            'responsibles',
            'sections',
            "menus",
            "features_activation",
            'smtp',
            'newsletter',
            'media_files',
            'server_status',
            'achievements',
            'can_sign_managers',
            'payment_methods',
            'settings',
            'investors',
            'popups',

            // 'managers_requests',
            //investor panel permissions
            'investor_analytics',
            'investor_deposit',
            'investor_withdraw',
            'investor_contract',
            'investor_transaction_history',
            'referrals',
            'referals_links',
            'wallet'


        ];
    }
}

if (!function_exists('permissions_list')) {
    function permissions_list()
    {
        return [
            "browse",
            "create",
            "edit",
            "delete",
        ];
    }
}


if (!function_exists('getActiveContract')) {
    function getActiveContract()
    {
        try {
            // Retrieve the first (and should be only) active contract theme using DB::table()
            $activeContractTheme = DB::table('contract_themes')
                ->where('is_active', true)
                ->first();

            // Return the active contract theme if found, otherwise return null
            return $activeContractTheme;
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error retrieving active contract theme: ' . $e->getMessage());

            // Return null if there's any error
            return null;
        }
    }
}

if (!function_exists('sendNotification')) {
    function sendNotification($userId, $type, $title, $message)
    {
        // You can adjust the logic here as needed
        Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
        ]);
    }
}
if (!function_exists('showNotifications')) {
    function showNotifications()
    {
        return Notification::where('user_id', Auth::id())->limit(5)->latest()->get();
    }
}
if (!function_exists('getSettingValue')) {
    function getSettingValue($column)
    {
        $setting = Setting::first();
        return $setting && isset($setting->{$column}) ? $setting->{$column} : '';
    }
}

if (!function_exists('getTablesLimit')) {
    function getTablesLimit($table, $limit)
    {
        try {
            $records = DB::table($table)->limit($limit)->get();
            return $records->isNotEmpty() ? $records : null;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            Log::error("Error fetching records from table {$table}: " . $e->getMessage());
            return null;
        }
    }
}

if (!function_exists('getTablesLimitWith')) {
    function getTablesLimitWith($table, $limit, $with = [])
    {
        try {
            // Get the model associated with the table
            $model = 'App\\Models\\' . ucfirst(Str::singular($table)); // Use Str::singular() to get the singular form of the table name

            // Check if the model exists
            if (class_exists($model)) {
                $query = $model::query();

                // Apply eager loading if relationships are provided
                if (!empty($with)) {
                    $query->with($with);
                }

                // Limit the records and get them
                $records = $query->limit($limit)->get();

                return $records->isNotEmpty() ? $records : null;
            } else {
                // Fallback to query builder if the model doesn't exist
                $records = DB::table($table)->limit($limit)->get();
                return $records->isNotEmpty() ? $records : null;
            }
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            Log::error("Error fetching records from table {$table}: " . $e->getMessage());
            return null;
        }
    }
}

if (!function_exists('getTablesLimitFilter')) {
    function getTablesLimitFilter($table, $limit, $offset = 0)
    {
        return DB::table($table)->where('code', 'projects')->offset($offset)->limit($limit)->get();
    }
}

if (!function_exists('getSocial')) {
    function getSocial(string $filter)
    {
        return DB::table('socials')
            ->where('name', '=', $filter)
            ->first();
    }
}


if (!function_exists('getRandomMember')) {
    function getRandomMember()
    {
        return DB::table('members')->inRandomOrder()->first();
    }
}


if (!function_exists('getMenusLimit')) {
    function getMenusLimit($limit)
    {
        try {
            $records = DB::table('menus')->limit($limit)->where('parent_id', null)->get();
            return $records->isNotEmpty() ? $records : null;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            Log::error("Error fetching records from table " . $e->getMessage());
            return null;
        }
    }
}

if (!function_exists('getTestimonials')) {
    function getTestimonials()
    {
        try {
            // Ensure 'video' is explicitly checked for null or empty value
            $records = DB::table('testimonials')
                ->whereNull('video')
                ->limit(6)
                ->get();

            return $records->isNotEmpty() ? $records : null;
        } catch (\Exception $e) {
            // Log the error message with more context if needed
            Log::error("Error fetching testimonials: " . $e->getMessage());
            return null;
        }
    }
}

if (!function_exists('getCategorie')) {
    function getCategorie($type)
    {
        try {
            // Validate the input to ensure it's not null or empty
            if (empty($type)) {
                Log::warning("The provided type is null or empty.");
                return null;
            }

            // Fetch the type object based on the provided name
            $getType = Type::where('name', $type)->first();

            // Check if $getType is null to avoid accessing properties on null
            if (!$getType) {
                Log::warning("No type found for the provided name: " . $type);
                return null;
            }

            // Fetch the related categories and limit the results
            $records = DB::table('categories')
                ->where('type_id', $getType->id)
                ->limit(15)
                ->get();

            // Return the records if they are not empty, otherwise null
            return $records->isNotEmpty() ? $records : null;
        } catch (\Exception $e) {
            // Log the error message with more context
            Log::error("Error fetching categories for type '{$type}': " . $e->getMessage());
            return null;
        }
    }
}




if (!function_exists('getTables')) {

    function getTables($table, $with = [], $paginate = false)
    {
        try {
            $records = DB::table($table);

            // Apply 'with' if it's not empty and the table uses Eloquent relationships
            if (!empty($with) && method_exists($records, 'with')) {
                $records = $records->with($with);
            }


            // Apply pagination if requested
            if ($paginate) {
                $records = $records->paginate(20);
            } else {
                $records = $records->get(); // Fetch all records
            }

            return $records->isNotEmpty() ? $records : null;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            Log::error("Error fetching records from table {$table}: " . $e->getMessage());
            return null;
        }
    }
}

if (!function_exists('currency')) {

    /**
     * Format the amount with the currency symbol.
     *
     * @param float $amount
     * @return string
     */
    function currency($amount)
    {
        try {
            // Fetch the first active currency symbol and its positioning (is_left)
            $currency = DB::table('currencies')->where('is_active', true)->first();

            // Check if currency exists and has a valid symbol
            if (!$currency || empty($currency->code)) {
                return $amount; // Return the raw amount if no currency code found
            }

            $currencySymbol = $currency->code;
            $symbolOnLeft = $currency->is_left; // Get the symbol position (left or right)

            // Format the amount to two decimal places
            $formattedAmount = number_format($amount, 0);

            // Return the formatted currency string based on whether the symbol is on the left or right
            $formattedAmount = trim($formattedAmount);
            $currencySymbol = trim($currencySymbol);

            return $symbolOnLeft
                ? "{$currencySymbol}{$formattedAmount}"
                : "{$formattedAmount}{$currencySymbol}";
        } catch (\Exception $e) {
            // Log the error message for debugging
            Log::error("Error fetching currency symbol: " . $e->getMessage());
            return $amount; // Return the raw amount if there is an error
        }
    }
}


if (!function_exists('format_large_number')) {

    function format_large_number($number)
    {
        // Ensure the number is a float and sanitize it
        $number = is_numeric($number) ? (float) $number : 0;

        // Check for invalid or infinite numbers
        if ($number == 0 || is_nan($number) || is_infinite($number)) {
            return 0; // Or handle this case however you prefer
        }

        // Format the number with appropriate suffixes (K, M, B)
        if ($number >= 1000000000) {
            return number_format($number / 1000000000, 1) . 'B'; // Billions (1 decimal place)
        } elseif ($number >= 1000000) {
            return number_format($number / 1000000, 1) . 'M'; // Millions (1 decimal place)
        } elseif ($number >= 1000) {
            return number_format($number / 1000, 1) . 'K'; // Thousands (1 decimal place)
        } else {
            return number_format($number, 0); // No suffix for smaller numbers
        }
    }
}


if (!function_exists('getPayment')) {
    function getPayment($filter)
    {
        try {
            $record = DB::table('payment_methods')->where('provider', $filter)->first();
            return $record ?? null;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            Log::error("Error fetching the first record from table : " . $e->getMessage());
            return null;
        }
    }
}

if (!function_exists('getWallet')) {
    function getWallet($id)
    {
        try {
            $record = DB::table('wallets')->where('user_id', $id)->first();
            return $record ?? null;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            Log::error("Error fetching the first record from table : " . $e->getMessage());
            return null;
        }
    }
}

if (!function_exists('getCurrency')) {
    function getCurrency()
    {
        try {
            $record = DB::table('currencies')->where('is_active', true)->first();
            return $record ?? null;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            Log::error("Error fetching the first record from table : " . $e->getMessage());
            return null;
        }
    }
}



if (!function_exists('getTable')) {
    function getTable($table)
    {
        try {
            $record = DB::table($table)->first();
            return $record ?? null;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            Log::error("Error fetching the first record from table {$table}: " . $e->getMessage());
            return null;
        }
    }
}


if (!function_exists('getContract')) {
    function getContract($type)
    {
        try {
            $record = DB::table('contracts')->where('status', 'ACTIVE')->where('type_id', $type)->first();
            return $record ?? null;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            Log::error("Error fetching the first record from table: " . $e->getMessage());
            return null;
        }
    }
}


if (!function_exists('getUserContract')) {
    /**
     * Get the contract associated with the user's role through affiliate stages.
     *
     * @return \App\Models\Contract|null
     */
    function getUserContract($user)
    {
        try {
            if (!$user) {
                throw new \Exception("No authenticated user found.");
            }

            // Fetch the user's role
            $userRole = $user->getRoleNames()->first();
            if (!$userRole) {
                throw new \Exception("User does not have an assigned role.");
            }

            // Fetch the affiliate stage corresponding to the user's role
            $affiliateStageQuery = \App\Models\AffiliateStage::whereHas('role', function ($query) use ($userRole) {
                $query->where('name', '=', $userRole);
            });



            $affiliateStage = $affiliateStageQuery->first();

            if (!$affiliateStage) {
                throw new \Exception("No affiliate stage found for the user's role: {$userRole}");
            }

            // Fetch the latest contract associated with the affiliate stage
            $contract = $affiliateStage->contract()->latest()->first();

            if (!$contract) {
                throw new \Exception("No contract associated with the affiliate stage ID: {$affiliateStage->id}");
            }

            return $contract;

        } catch (\Exception $e) {
            // Log the error with additional details
            Log::error("Error fetching contract for user ID {$user->id}: " . $e->getMessage());
            return null;
        }
    }

}



if (!function_exists('getSection')) {
    function getSection($name)
    {
        try {
            $record = DB::table('landing_page_sections')->where('unique_name', $name)->first();
            return $record ?? null;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            Log::error("Error fetching the first record from table: " . $e->getMessage());
            return null;
        }
    }
}



if (!function_exists('getPage')) {
    function getPage($name)
    {
        try {
            $record = DB::table('pages')->where('unique_name', $name)->first();
            return $record ?? null;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            Log::error("Error fetching the first record from table {: " . $e->getMessage());
            return null;
        }
    }
}

if (!function_exists('getHeading')) {
    function getHeading($data)
    {
        $record = DB::table('headings')->where('slug', $data)->first();
        return $record ?? null;
    }
}

if (!function_exists('section')) {
    function section($data)
    {
        $record = DB::table('landing_page_sections')->where('unique_name', $data)->first();
        return $record ?? null;
    }
}

if (!function_exists('under_maintenance')) {
    function under_maintenance()
    {
        $record = DB::table('settings')->first();
        return $record ? $record->maintenance : null;
    }
}
if (!function_exists('getMenus')) {
    function getMenus()
    {
        try {
            // Fetch all menu items
            $menus = DB::table('menus')->get();

            // Build a tree structure for menus
            $menuTree = $menus->where('parent_id', null)->map(function ($menu) use ($menus) {
                $menu->children = $menus->where('parent_id', $menu->id)->map(function ($child) use ($menus) {
                    $child->children = $menus->where('parent_id', $child->id);
                    return $child;
                });
                return $menu;
            });

            return $menuTree;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            Log::error("Error building menu structure: " . $e->getMessage());
            return collect(); // Return an empty collection
        }
    }
}

if (!function_exists('convertYoutube')) {
    /**
     * Convert a YouTube link to a standard YouTube watch URL.
     *
     * @param string $youtubeUrl The YouTube URL to convert.
     * @return string The converted YouTube watch URL.
     */
    function convertYoutube($youtubeUrl)
    {
        // Extract video ID from the YouTube URL
        preg_match('/(?:https?:\/\/(?:www\.)?youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=))([^"&?\/\s]{11})/', $youtubeUrl, $matches);

        // If the YouTube URL is valid and the video ID is extracted
        if (isset($matches[1])) {
            $videoId = $matches[1];
            // Construct the standard YouTube watch URL
            return "https://www.youtube.com/watch?v=$videoId";
        }

        // If the URL is invalid, return an empty string or handle the error as needed
        return '';
    }
}


if (!function_exists('getYoutubeThumbnail')) {
    /**
     * Get the thumbnail URL from a YouTube video link.
     *
     * @param string $youtubeUrl The YouTube URL to extract the thumbnail from.
     * @return string The thumbnail URL or an empty string if the URL is invalid.
     */
    function getYoutubeThumbnail($youtubeUrl)
    {
        // Extract video ID from the YouTube URL
        preg_match('/(?:https?:\/\/(?:www\.)?youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=))([^"&?\/\s]{11})/', $youtubeUrl, $matches);

        // If the YouTube URL is valid and the video ID is extracted
        if (isset($matches[1])) {
            $videoId = $matches[1];
            // Return the YouTube thumbnail URL
            return "https://img.youtube.com/vi/$videoId/maxresdefault.jpg";
        }

        // If the URL is invalid, return an empty string or handle the error as needed
        return '';
    }
}



if (!function_exists('saveTransaction')) {
    /**
     * Save a transaction to the transactions table.
     *
     * @param int $userId
     * @param string $paymentMethod
     * @param float $amount
     * @param string $currency
     * @param string|null $transactionReference
     * @param string $status
     * @param string|null $description
     * @param array|null $details
     * @param string|null $paymentAccount
     * @param float|null $paymentGatewayFee
     * @param bool $isTest
     * @param string|null $ipAddress
     * @return bool
     */
    function saveTransaction(
        int $userId,
        string $paymentMethod,
        float $amount,
        string $currency,
        string $amoutWithoutTax,
        string $status = 'pending',
        bool $isTest = false,
        ?string $description = null,
        ?string $transactionReference = null,
        ?array $details = null,
        ?string $paymentAccount = null,
        ?float $paymentGatewayFee = null,
        ?string $ipAddress = null,
    ): bool {
        try {

            $transactionData = [
                'payment_method' => $paymentMethod,
                'amount' => $amount,
                'currency' => $currency,
                'amoutWithoutTax' => $amoutWithoutTax,
                'description' => $description,
                'transaction_reference' => $transactionReference
            ];


            DB::table('transactions')->insert([
                'user_id' => $userId,
                'payment_method' => $paymentMethod,
                'transaction_reference' => $transactionReference,
                'amount' => $amount,
                'currency' => $currency,
                'status' => $status,
                'description' => $description,
                'details' => $details ? json_encode($details) : null,
                'payment_account' => $paymentAccount,
                'payment_gateway_fee' => $paymentGatewayFee,
                'is_test' => $isTest,
                'ip_address' => $ipAddress,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $wallet = DB::table('wallets')->where('user_id', $userId)->first();

            $columnToUpdate = ($paymentMethod === 'cash' || $paymentMethod === 'bank_transfer' || $paymentMethod === 'crypto') ? 'pending_capital' : 'capital';

            if ($wallet) {
                // If wallet exists, increment the appropriate column
                DB::table('wallets')
                    ->where('user_id', $userId)
                    ->increment($columnToUpdate, $amoutWithoutTax);
            } else {
                // If wallet does not exist, create a new record
                DB::table('wallets')->insert([
                    'user_id' => $userId,
                    'capital' => $columnToUpdate === 'capital' ? $$amoutWithoutTax : 0,
                    'pending_capital' => $columnToUpdate === 'pending_capital' ? $amoutWithoutTax : 0,
                ]);
            }

            $user = User::find($userId);
            if (!$user) {
                throw new \Exception("User with ID $userId not found.");
            }

            $data = ['user' => $user];
            sendEmail($user->email, 'pending_deposite', $data);
            generateAndSendInvoice(
                $userId,
                $transactionData,
                "INV-" . time()
            );

            return true;
        } catch (\Exception $e) {
            // Log the error if needed
            Log::error('Error saving transaction: ' . $e->getMessage());
            return false;
        }
    }
}


if (!function_exists('checkInvestorRules')) {
    function checkInvestorRules(\App\Models\Investor $investor): bool
    {
        if (is_null($investor)) {
            Log::warning("Helper Function: Received null Investor. Skipping rule check.");
            return false;
        }

        Log::info("Helper Function: checkInvestorRules called for Investor ID {$investor->id}.");

        // Fetch the affiliate stage rules
        $rules = DB::table('affiliate_stages')->where('role_id', $investor->user->roles->first()->id)->first();

        if (!$rules) {
            return false;
        }

        $user = $investor->user;

        if (!$user) {
            return false;
        }

        $conditionsMet = true;

        // Check if the investor meets the minimum capital requirement
        if ($user->wallet && $user->wallet->capital < $rules->capital) {
            $conditionsMet = false;
        }


        // Check if the KYC request was created more than one month ago
        $kycRequestDate = $user->kycRequest ? $user->kycRequest->created_at : null;
        if (!$kycRequestDate || $kycRequestDate->diffInDays(now()) < 30) {
            $conditionsMet = false;
        }

        // Ensure the 'advertiser' role exists for the 'web' guard
        $role = \Spatie\Permission\Models\Role::where('name', 'advertiser')->where('guard_name', 'web')->first();
        if (!$role) {
            $role = \Spatie\Permission\Models\Role::create(['name' => 'advertiser', 'guard_name' => 'web']);
        }

        if ($conditionsMet) {
            // Check if the user already has the 'advertiser' role
            if (!$user->hasRole('advertiser', 'web')) {
                // User is transitioning to a new role

                // Remove all existing roles of the user
                $user->syncRoles([]);

                // Assign the 'advertiser' role
                $user->assignRole('advertiser');

                // Set the user as inactive during the role transition
                DB::transaction(function () use ($user) {
                    // $user->active = false;
                    // $user->save();

                    // if ($user->kycRequest) {
                    //     $user->kycRequest->is_signed = false;
                    //     $user->kycRequest->status = 'processing';
                    //     $user->kycRequest->save();
                    // }

                    // Optional: Log the role transition and deactivation
                    Log::info("User ID {$user->id} has been assigned the 'advertiser' role and deactivated for review.");
                });

            }
        }

        return $conditionsMet;
    }
}

if (!function_exists('checkAdvertiserRules')) {
    function checkAdvertiserRules(\App\Models\Investor $advertiser): bool
    {
        Log::info('Starting checkAdvertiserRules function.');

        // Fetch the affiliate stage rules
        Log::info('Fetching affiliate stage rules.');
        $rules = DB::table('affiliate_stages')->where('role_id', $advertiser->user->roles->first()->id)->first();

        if (!$rules) {
            Log::warning('No affiliate stage rules found.');
            return false;
        }

        Log::info('Affiliate stage rules found.', ['rules' => $rules]);

        $user = $advertiser->user;

        if (!$user) {
            Log::warning('Advertiser has no associated user.');
            return false;
        }

        Log::info('Advertiser user found.', ['user_id' => $user->id]);

        $conditionsMet = true;

        // Check if the advertiser meets the minimum capital requirement
        if ($user->wallet && $user->wallet->capital < $rules->capital) {
            Log::warning('Advertiser does not meet the minimum capital requirement.', [
                'wallet_capital' => $user->wallet->capital,
                'required_capital' => $rules->capital,
            ]);
            $conditionsMet = false;
        } else {
            Log::info('Advertiser meets the minimum capital requirement.');
        }

        // Check if the user has enough referrals
        $referralCount = count($user->referrals);
        if ($referralCount < $rules->team_size) {
            Log::warning('Advertiser does not have enough referrals.', [
                'current_referrals' => $referralCount,
                'required_referrals' => $rules->team_size,
            ]);
            $conditionsMet = false;
        } else {
            Log::info('Advertiser has enough referrals.', ['referral_count' => $referralCount]);
        }

        // Check if the referrals have the 'advertiser' role
        foreach ($user->referrals as $referral) {
            if (!$referral->hasRole('advertiser', 'web')) {
                Log::warning('Referral does not have the advertiser role.', ['referral_id' => $referral->id]);
                $conditionsMet = false;
                break;
            }
        }

        if ($conditionsMet) {
            Log::info('All referrals have the advertiser role.');
        }

        // Check if the KYC request was created more than one month ago
        $kycRequestDate = $user->kycRequest ? $user->kycRequest->created_at : null;
        if (!$kycRequestDate || $kycRequestDate->diffInDays(now()) < 30) {
            Log::warning('KYC request is either missing or not older than 30 days.', [
                'kyc_request_date' => $kycRequestDate,
            ]);
            $conditionsMet = false;
        } else {
            Log::info('KYC request is older than 30 days.', ['kyc_request_date' => $kycRequestDate]);
        }

        // Ensure the 'advertiser' role exists for the 'web' guard
        $role = \Spatie\Permission\Models\Role::where('name', 'manager')->where('guard_name', 'web')->first();
        if (!$role) {
            Log::info('Manager role not found. Creating the role.');
            $role = \Spatie\Permission\Models\Role::create(['name' => 'manager', 'guard_name' => 'web']);
        }

        if ($conditionsMet) {
            Log::info('All conditions met for the advertiser.');
            // Uncomment this block if needed
            if (!$user->hasRole('manager', 'web')) {
                Log::info('User does not have the manager role. Assigning new role.');
                DB::transaction(function () use ($user) {
                    // $user->active = false;
                    // $user->save();

                    // if ($user->kycRequest) {
                    //     $user->kycRequest->is_signed = false;
                    //     $user->kycRequest->status = 'processing';
                    //     $user->kycRequest->save();
                    // }

                    Log::info("User ID {$user->id} has been assigned the 'manager' role and deactivated for review.");
                });
            }
        } else {
            Log::info('Not all conditions were met for the advertiser.');
        }

        return $conditionsMet;
    }
}


// check if the advertiser can take commision of referrals

if (!function_exists('checkAdvertiserComissionRules')) {
    function checkAdvertiserComissionRules(\App\Models\Investor $advertiser): bool
    {
        // Fetch the affiliate stage rules
        $rules = DB::table('affiliate_stages')
            ->where('role_id', $advertiser->user->roles->first()->id)
            ->first();

        if (!$rules) {
            return false;
        }

        $user = $advertiser->user;

        if (!$user || !$user->hasRole('advertiser', 'web')) {
            return false;
        }

        // Get referrals eligible for commission (no existing commission transaction)
        $referrals = $user->referrals()
            ->whereHas('roles', function ($query) {
                $query->where('name', 'advertiser')->where('guard_name', 'web');
            })
            ->whereDate('created_at', '>=', now()->startOfMonth())
            ->get();

        if ($referrals->isEmpty()) {
            return false;
        }

        $commissionPercentage = $user->investor->percentage || $rules->commission_percentage;
        \Log::info("comssions check" . $commissionPercentage);
        foreach ($referrals as $referral) {
            // Check if a commission transaction already exists for this referral
            $existingCommission = \App\Models\Transaction::where('user_id', $user->id)
                ->where('type', 'commission')
                ->where('related_user_id', $referral->id)
                ->exists();

            if ($existingCommission) {
                continue; // Skip if commission already awarded
            }

            if ($referral->wallet && $referral->wallet->capital > 0) {
                $commission = ($commissionPercentage / 100) * $referral->wallet->capital;

                // Add commission to advertiser's pending profit
                $user->wallet->pending_profit += $commission;
                $user->wallet->save();

                // Record the commission in transactions to prevent duplicates
                \App\Models\Transaction::create([
                    'user_id' => $user->id,
                    'type' => 'commission',
                    'amount' => $commission,
                    'related_user_id' => $referral->id,
                    'description' => 'Commission for referral ID ' . $referral->id,
                ]);
            }
        }

        return true;
    }
}




if (!function_exists('checkManagerRules')) {
    function checkManagerRules(\App\Models\Investor $investor): bool
    {

        Log::info('Starting checkmanagerRules function.');
        // Fetch the affiliate stage rules
        $rules = DB::table('affiliate_stages')->where('role_id', $investor->user->roles->first()->id)->first();

        if (!$rules) {
            return false;
        }

        $user = $investor->user;

        if (!$user) {
            return false;
        }

        $conditionsMet = true;

        // Check if the investor meets the minimum capital requirement
        if ($user->wallet && $user->wallet->capital < $rules->capital) {
            $conditionsMet = false;
        }

        // Check if the user has enough referrals
        if (count($user->referrals) < $rules->team_size) {
            $conditionsMet = false;
        }

        // Check if the referrals have the 'investor' role
        foreach ($user->referrals as $referral) {
            if (!$referral->hasRole('investor', 'web')) {
                $conditionsMet = false;
                break;
            }
        }

        // Check if the user has enough referrals within the specified time frame
        $recentReferrals = $user->referrals->filter(function ($referral) use ($rules) {
            return $referral->created_at >= now()->subMonths($rules->people_per_six_months);
        });
        if ($recentReferrals->count() < $rules->team_size) {
            $conditionsMet = false;
        }

        // Check if the KYC request was created more than one month ago
        $kycRequestDate = $user->kycRequest ? $user->kycRequest->created_at : null;
        if (!$kycRequestDate || $kycRequestDate->diffInDays(now()) < 30) {
            $conditionsMet = false;
        }



        // we have to send notificaition to the employee to know the list of the eligible users
        return $conditionsMet;
    }
}


if (!function_exists('checkManagerComissionRules')) {
    function checkManagerComissionRules(\App\Models\Investor $manager): bool
    {
        // Fetch the affiliate stage rules
        $rules = DB::table('affiliate_stages')->where('role_id', $manager->user->roles->first()->id)->first();

        if (!$rules) {
            return false;
        }

        $user = $manager->user;

        if (!$user || !$user->hasRole('manager', 'web')) {
            return false;
        }


        $currentDate = now();


        if ($currentDate->day < 25) {
            return false;
        }

        // Ensure user has referrals
        if ($user->referrals->isEmpty()) {
            return false;
        }

        // Calculate commission for referrals
        $commissionPercentage = $rules->commission_percentage;

        foreach ($user->referrals as $referral) {
            if ($referral->hasRole('advertiser', 'web')) {
                if ($referral->wallet && $referral->wallet->capital > 0) {
                    $commission = ($commissionPercentage / 100) * $referral->wallet->capital;

                    // Add commission to manager's pending profit
                    $user->wallet->pending_profit += $commission;
                    $user->wallet->save();
                }
            }
        }

        return true;
    }
}



if (!function_exists('calculateAffiliateProfits')) {
    function calculateAffiliateProfits(\App\Models\Investor $affiliate)
    {
        Log::info('Starting calculateAffiliateProfits function.', ['affiliate_id' => $affiliate->id]);

        // Get the user's role
        $user = $affiliate->user;
        if (!$user || !$user->roles->first()) {
            Log::warning('User does not exist or has no roles.', ['affiliate_id' => $affiliate->id]);
            return false;
        }

        $role = $user->roles->first()->name;
        Log::info("Detected role: {$role}", ['user_id' => $user->id]);

        // Fetch affiliate stage rules
        Log::info('Fetching affiliate stage rules.');
        $rules = DB::table('affiliate_stages')
            ->where('role_id', $user->roles->first()->id)
            ->first();

        if (!$rules) {
            Log::warning('No affiliate stage rules found.');
            return false;
        }



        // Get referrals based on role
        $validReferralRoles = [
            'investor' => ['investor'],
            'advertiser' => ['advertiser'],
            'manager' => ['advertiser'],
            'manager_adv' => ['advertiser', 'manager']
        ];

        $referrals = $user->referrals()
            ->whereHas('roles', function ($query) use ($validReferralRoles, $role) {
                $query->whereIn('name', $validReferralRoles[$role])
                    ->where('guard_name', 'web');
            })
            ->whereDate('created_at', '>=', now()->startOfMonth())
            ->get();

        if ($referrals->isEmpty()) {
            Log::info('No eligible referrals found for commission.');
            return false;
        }

        $commissionPercentage = $affiliate->percentage ?? $rules->commission_percentage;
        Log::info("Commission percentage: {$commissionPercentage}%");

        foreach ($referrals as $referral) {
            
            // Skip if commission already awarded for this referral
            $existingCommission = \App\Models\Transaction::where('user_id', $user->id)
                ->where('type', 'commission')
                ->where('related_user_id', $referral->id)
                ->exists();

            if ($existingCommission) {
                Log::info("Commission already awarded for referral ID: {$referral->id}");
                continue;
            }

            if ($referral->wallet && $referral->wallet->capital > 0) {
                $commission = ($commissionPercentage / 100) * $referral->wallet->capital;

                // Add commission to pending profit
                DB::transaction(function () use ($user, $commission, $referral) {
                    $user->wallet->pending_profit += $commission;
                    $user->wallet->save();

                    // Record commission transaction
                    \App\Models\Transaction::create([
                        'user_id' => $user->id,
                        'type' => 'commission',
                        'amount' => $commission,
                        'related_user_id' => $referral->id,
                        'description' => "Commission for referral ID {$referral->id} (Role: {$referral->roles->first()->name})",
                    ]);

                    Log::info("Commission of {$commission} recorded for referral ID: {$referral->id}");
                });
            }
        }

        Log::info("Profit calculation completed for role: {$role}.");
        return true;
    }
}



if (!function_exists('insertKyc')) {
    function insertKyc($userId)
    {
        // Check if a record exists for the given user ID
        $record = DB::table('kyc_requests')->where('user_id', $userId)->first();

        // If no record exists, insert a new one with default values for required fields
        if (!$record) {
            DB::table('kyc_requests')->insert([
                'user_id' => $userId,
                'document_type' => 'id_card', // Default document type, adjust as needed
                'selfie_path' => 'default_selfie_path.jpg', // Provide a placeholder path
                'front_photo_path' => null, // Optional field
                'back_photo_path' => null, // Optional field
                'passport_photo_path' => null, // Optional field
                'additional_info' => null, // Optional field
                'status' => 'pending', // Default status
                'created_at' => now(), // Timestamp
                'updated_at' => now(), // Timestamp
            ]);

            // Fetch the newly inserted record
            $record = DB::table('kyc_requests')->where('user_id', $userId)->first();
        }

        // Return the 'status' or any other field you need
        return $record->status;
    }
}
if (!function_exists('movePendingToCapital')) {
    function movePendingToCapital($amount)
    {
        // Validate amount
        if (!is_numeric($amount) || $amount <= 0) {
            throw new Exception("Invalid amount: must be a positive number.");
        }

        DB::transaction(function () use ($amount) {
            $wallet = DB::table('wallets')->where('user_id', Auth::user()->id)->first();

            if ($wallet) {
                $pendingCapital = $wallet->pending_capital;


                if ($pendingCapital >= $amount) {
                    // Decrease pending_capital and increase capital
                    DB::table('wallets')->where('user_id', Auth::user()->id)->update([
                        'pending_capital' => DB::raw("pending_capital - $amount"),
                        'capital' => DB::raw("capital + $amount"),
                    ]);
                } else {
                    return back()->withErrors("Insufficient pending capital to move the requested amount.");
                }
            } else {
                return back()->withErrors("Wallet not found");
            }
        });
    }
}






if (!function_exists('sendEmail')) {
    /**
     * Send an email using the InvestorMail Mailable class with a specified template.
     *
     * @param string $email Recipient's email address
     * @param string $templateName Name of the email template (e.g., 'kyc_pending')
     * @param array $data Data to pass to the email template
     * @return bool True on success, false on failure
     */
    function sendEmail(string $email, string $templateName, array $data = []): bool
    {
        try {
            // Instantiate the InvestorMail with template name and data
            $mailable = new \App\Mail\InvestorMail($templateName, $data);

            // Send the email
            Mail::to($email)->send($mailable);

            // Log the successful email sending

            return true;
        } catch (Exception $e) {
            // Log the error details
            Log::error("Failed to send email to {$email} using template '{$templateName}': " . $e->getMessage());

            return false;
        }
    }
}



if (!function_exists('incentives')) {
    function incentives(Investor $investor)
    {
        $today = Carbon::today();
        $currentYear = $today->year;

        // Get incentives for today (monthly or yearly)
        $getCurrent = Incentive::where(function ($query) use ($today) {
            $query->where('bonus_type', '!=', 'yearly')
                ->where('from_date', '<=', $today)
                ->where('to_date', '>=', $today);
        })
            ->orWhere(function ($query) use ($currentYear) {
                $query->where('bonus_type', 'yearly')
                    ->whereYear('created_at', $currentYear);
            })
            ->get();

        // Check if user role matches the required affiliate role
        foreach ($getCurrent as $incentive) {
            $incentiveName = $incentive->name ?? 'Unknown Incentive';
            $roleName = optional($incentive->affiliateStage->role)->name; // Get role name from affiliateStage

            if ($investor->user->hasRole($roleName)) {
                Log::info("✅ Investor {$investor->user->id} ({$investor->user->getRoleNames()->implode(', ')}) qualifies for incentive '{$incentiveName}' (Required Role: {$roleName})");

                // Check if the investor is already associated with this incentive
                $existingIncentiveInvestor = IncentiveInvestor::where('incentive_id', $incentive->id)
                    ->where('investor_id', $investor->id)
                    ->first();

                if (!$existingIncentiveInvestor) {
                    // Investor is eligible but not associated yet, so create the new association
                    IncentiveInvestor::create([
                        'incentive_id' => $incentive->id,
                        'investor_id' => $investor->id,
                    ]);

                    Log::info("✅ Investor {$investor->user->id} has been added to IncentiveInvestor table for incentive '{$incentiveName}'");

                    // 1. Calculate the profit (capital - initial investment) and apply the percentage
                    $capital = $investor->user->wallet->capital; // e.g., $25,491.00
                    $initialInvestment = $investor->user->wallet->initial_investment; // e.g., $20,000.00
                    $profit = $capital - $initialInvestment; // Calculate profit

                    $percentage = $incentive->percentage / 100; // Convert to decimal (e.g., 3% to 0.03)

                    // Calculate the bonus based on the profit
                    $bonus = $profit * $percentage; // $profit * 0.03

                    // Increment the pending bonus
                    $investor->user->wallet->pending_bonus += $bonus;
                    $investor->user->wallet->save();

                    Log::info("✅ Investor {$investor->user->id} has received a bonus of {$bonus} for incentive '{$incentiveName}'");

                    // 3. Check eligibility for monthly bonus type
                    if ($incentive->bonus_type == 'monthly') {
                        Log::info("✅ Investor {$investor->user->id} is eligible for monthly bonus.");
                    }

                    // 4. Check eligibility for yearly bonus type and apply from enrollment date to 30th October
                    if ($incentive->bonus_type == 'yearly') {
                        $enrollmentDate = $investor->created_at;
                        $october30 = Carbon::createFromDate($currentYear, 10, 30);

                        if ($enrollmentDate <= $october30) {
                            $yearlyBonus = $profit * $incentive->percentage / 100;
                            $investor->user->wallet->pending_bonus += $yearlyBonus;
                            $investor->user->wallet->save();

                            Log::info("✅ Investor {$investor->user->id} has received a yearly bonus of {$yearlyBonus} (calculated from profit) for incentive '{$incentiveName}'");
                        }
                    }
                } else {
                    Log::info("❌ Investor {$investor->user->id} is already linked to incentive '{$incentiveName}' (No action needed)");
                }
            } else {
                Log::warning("❌ Investor {$investor->user->id} ({$investor->user->getRoleNames()->implode(', ')}) does NOT qualify for incentive '{$incentiveName}' (Required Role: {$roleName})");
            }
        }

        return $getCurrent;
    }
}


if (!function_exists('getLatestTransactions')) {
    function getLatestTransactions($user_id)
    {
        return DB::table('transactions')
            ->where('user_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->first();
    }
}




// if (!function_exists('incentives')) {
//     function incentives() {
//         $today = Carbon::today();
//         $currentYear = $today->year;

//         // Get incentives that are NOT yearly (i.e., monthly, quarterly, etc.)
//         $getCurrent = Incentive::where(function ($query) use ($today) {
//                 $query->where('bonus_type', '!=', 'yearly') // Non-yearly bonuses
//                     ->where('from_date', '<=', $today)
//                     ->where('to_date', '>=', $today);
//             })
//             ->orWhere(function ($query) use ($currentYear) {
//                 $query->where('bonus_type', 'yearly')
//                     ->whereYear('created_at', $currentYear); // Checks if the incentive was created in the current year
//             })
//             ->get();
//             Log::info("=================================================");
//         // Logging the found incentives
//         foreach ($getCurrent as $incentive) {
//             Log::info("Incentive Found: ID {$incentive->id}, Bonus Type: {$incentive->bonus_type}");
//         }
//         Log::info("=================================================");
//         return $getCurrent;
//     }
// }


if (!function_exists('validateEmail')) {
    function validateEmail(string $email, Illuminate\Http\Request $request): array
    {
        $ip = $request->ip();
        $userAgent = $request->header('User-Agent', 'Unknown');

        // Rate limiting check
        if (isRateLimited($ip, $email)) {
            logFailedAttempt($email, $ip, $userAgent, 'Rate limit exceeded');
            return [
                'success' => false,
                'message' => 'Too many attempts. Please try again later.',
            ];
        }

        // Check if email is blocked
        $existingLog = LogData::where('email', $email)->first();
        if ($existingLog && $existingLog->is_blocked) {
            return [
                'success' => false,
                'message' => 'This email is blocked due to multiple invalid attempts',
            ];
        }

        // Basic email format validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            logFailedAttempt($email, $ip, $userAgent, 'Invalid email format');
            return [
                'success' => false,
                'message' => 'Invalid email format',
            ];
        }

        $domain = strtolower(substr(strrchr($email, "@"), 1));
        $username = substr($email, 0, strpos($email, '@'));

        $blacklistedDomains = [
            'spam.com',
            'fake.com',
            'trashmail.com',
            'example.com',
        ];

        $disposableDomains = [
            'mailinator.com',
            'tempmail.com',
            '10minutemail.com',
            'guerrillamail.com',
            'sharklasers.com',
        ];

        // Check blacklisted domains
        if (in_array($domain, $blacklistedDomains)) {
            logFailedAttempt($email, $ip, $userAgent, 'Blacklisted domain');
            return [
                'success' => false,
                'message' => 'Domain is blacklisted',
            ];
        }

        // Check disposable domains
        if (in_array($domain, $disposableDomains)) {
            logFailedAttempt($email, $ip, $userAgent, 'Disposable email');
            return [
                'success' => false,
                'message' => 'Disposable emails are not allowed',
            ];
        }

        // Verify MX records
        if (!hasValidMxRecords($domain)) {
            logFailedAttempt($email, $ip, $userAgent, 'Invalid MX records');
            return [
                'success' => false,
                'message' => 'Invalid email domain',
            ];
        }

        // Check for random/temp email patterns
        if (isRandomEmail($username)) {
            logFailedAttempt($email, $ip, $userAgent, 'Random email detected');
            return [
                'success' => false,
                'message' => 'Random emails are not allowed',
            ];
        }

        // Check for common typos in domain
        if ($suggestion = detectEmailTypo($email, $domain)) {
            logFailedAttempt($email, $ip, $userAgent, 'Possible email typo');
            return [
                'success' => false,
                'message' => "Did you mean $suggestion?",
            ];
        }

        // SMTP verification (optional, can be disabled for performance)
        if (!verifySmtp($email)) {
            logFailedAttempt($email, $ip, $userAgent, 'SMTP verification failed');
            return [
                'success' => false,
                'message' => 'Email server not responding',
            ];
        }

        // Check domain reputation
        if (!checkDomainReputation($domain)) {
            logFailedAttempt($email, $ip, $userAgent, 'Poor domain reputation');
            return [
                'success' => false,
                'message' => 'Domain has a poor reputation',
            ];
        }

        return ['success' => true];
    }
}

if (!function_exists('logFailedAttempt')) {
    function logFailedAttempt(string $email, string $ip, string $userAgent, string $reason): void
    {
        $maxAttempts = 3;
        $existingLog = LogData::where('email', $email)->first();

        if ($existingLog) {
            $existingLog->attempt_count++;
            if ($existingLog->attempt_count >= $maxAttempts) {
                $existingLog->is_blocked = true;
            }
            $existingLog->save();
        } else {
            LogData::create([
                'email' => $email,
                'ip_address' => $ip,
                'device' => parseUserAgent($userAgent),
                'location' => getLocation($ip),
                'reason' => $reason,
                'attempt_count' => 1,
                'is_blocked' => false,
            ]);
        }
    }
}

if (!function_exists('isRateLimited')) {
    function isRateLimited(string $ip, string $email): bool
    {
        $key = "email_validation:{$ip}:{$email}";
        $attempts = Cache::get($key, 0);
        $maxAttempts = 3;
        $rateLimitSeconds = 3600; // 1 hour

        if ($attempts >= $maxAttempts) {
            return true;
        }

        Cache::put($key, $attempts + 1, $rateLimitSeconds);
        return false;
    }
}

if (!function_exists('hasValidMxRecords')) {
    function hasValidMxRecords(string $domain): bool
    {
        return checkdnsrr($domain, 'MX') && getmxrr($domain, $mxhosts) && !empty($mxhosts);
    }
}

if (!function_exists('isRandomEmail')) {
    function isRandomEmail(string $username): bool
    {
        // If username is too short, consider it not random
        if (strlen($username) <= 6) {
            return false;
        }

        // Check username characteristics
        $isRandom = false;

        // Count numbers and letters
        $numberCount = preg_match_all('/[0-9]/', $username);
        $letterCount = preg_match_all('/[a-zA-Z]/', $username);
        $totalLength = strlen($username);

        // Looks random if:
        // 1. Too many consecutive numbers (5+)
        if (preg_match('/[0-9]{5,}/', $username)) {
            $isRandom = true;
        }
        // 2. High number concentration (more than 60% numbers)
        elseif ($numberCount > 0 && ($numberCount / $totalLength) > 0.6) {
            $isRandom = true;
        }
        // 3. Long sequence of alternating letters and numbers
        elseif (preg_match('/([a-zA-Z][0-9]){3,}/', $username)) {
            $isRandom = true;
        }
        // 4. Looks like a UUID or hash fragment
        elseif (preg_match('/^[a-f0-9]{8,}$/i', $username)) {
            $isRandom = true;
        }

        return $isRandom;
    }
}

if (!function_exists('detectEmailTypo')) {
    function detectEmailTypo(string $email, string $domain): ?string
    {
        $commonDomains = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com'];
        foreach ($commonDomains as $validDomain) {
            if (levenshtein($domain, $validDomain) <= 2 && $domain !== $validDomain) {
                return str_replace($domain, $validDomain, $email);
            }
        }
        return null;
    }
}

if (!function_exists('verifySmtp')) {
    function verifySmtp(string $email): bool
    {
        try {
            list($username, $domain) = explode('@', $email);
            getmxrr($domain, $mxhosts);
            if (empty($mxhosts)) {
                return false;
            }

            $socket = @fsockopen($mxhosts[0], 25, $errno, $errstr, 5);
            if (!$socket) {
                return false;
            }

            fclose($socket);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('checkDomainReputation')) {
    function checkDomainReputation(string $domain): bool
    {
        try {
            $apiKey = config('services.domain_reputation.key'); // Add to config/services.php
            if (!$apiKey) {
                return true; // Skip if no API key
            }

            $response = Http::get("https://domain-reputation.whoisxmlapi.com/api/v1?apiKey={$apiKey}&domainName={$domain}");
            $data = $response->json();

            return ($data['reputationScore'] ?? 100) >= 70; // Adjust threshold
        } catch (\Exception $e) {
            return true; // Fallback to true if API fails
        }
    }
}

if (!function_exists('parseUserAgent')) {
    function parseUserAgent(string $userAgent): string
    {
        $patterns = [
            '/(Firefox|Chrome|Safari|Edge|Opera)/i' => 'Browser',
            '/(Windows|Mac|Linux|Android|iOS)/i' => 'OS',
            '/(Mobile|Tablet)/i' => 'Device',
        ];

        $result = [];
        foreach ($patterns as $pattern => $type) {
            if (preg_match($pattern, $userAgent, $match)) {
                $result[] = $match[1];
            }
        }

        return implode(' ', $result) ?: 'Unknown';
    }
}

if (!function_exists('getLocation')) {
    function getLocation(string $ip): string
    {
        try {
            $response = Http::get("http://ip-api.com/json/{$ip}");
            $data = $response->json();
            return ($data['city'] ?? 'Unknown') . ', ' . ($data['country'] ?? 'Unknown');
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }
}


if (!function_exists('generateMonthlyStatement')) {
    /**
     * Generate monthly statement PDF for an investor
     *
     * @param Investor $investor
     * @return array
     */
    function generateMonthlyStatement(Investor $investor)
    {
        $user = $investor->user;

        if (!$user) {
            return ['error' => 'User not found for investor'];
        }

        $templatePath = public_path('AMWALFLOO.pdf');

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

        if (!file_exists($templatePath)) {
            return ['error' => 'PDF template not found'];
        }

        $pageCount = $mpdf->SetSourceFile($templatePath);

        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        $transactions = Transaction::where('user_id', $investor->user_id)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->get();

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

        $content = "
            <style>
                body { font-family: Cairo; direction: rtl; line-height: 1.5; }
                .content-wrapper { position: absolute; top: 250px; left: 20px; right: 20px; padding: 20px; max-width: 90%; margin-left: auto; margin-right: auto; }
                .header { text-align: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #ddd; }
                .header h2 { font-size: 16pt; margin: 0 0 10px 0; color: #333; }
                .header p { font-size: 11pt; margin: 5px 0; color: #555; }
                .table-container { width: 100%; display: flex; justify-content: center; margin: 20px 0; }
                table { width: 100%; border-collapse: collapse; background-color: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
                th, td { border: 1px solid #ccc; padding: 10px; text-align: center; font-size: 10pt; }
                th { background-color: #f5f5f5; font-weight: bold; color: #333; }
                td { color: #666; }
                .footer { text-align: center; margin-top: 25px; padding-top: 15px; border-top: 1px solid #ddd; font-size: 10pt; color: #777; }
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

        $fileName = "monthly_statement_{$investor->user_id}_" . now()->format('Y_m') . ".pdf";
        $filePath = storage_path("app/public/{$fileName}");

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $mpdf->Output($filePath, 'F');

        return ['file' => $fileName, 'path' => $filePath];
    }
}

if (!function_exists('generateAndSendInvoice')) {
    /**
     * Generate a beautiful HTML invoice and send it via email
     *
     * @param int $userId
     * @param array $transactionData
     * @param string $invoiceNumber
     * @param array|null $items
     * @return bool
     */
    function generateAndSendInvoice(
        int $userId,
        array $transactionData,
        string $invoiceNumber,
        ?array $items = null
    ): bool {
        try {
            // Get user details
            $user = User::find($userId);
            if (!$user) {
                throw new \Exception("User with ID $userId not found.");
            }

            // Prepare invoice data
            $invoiceData = [
                'invoice_number' => $invoiceNumber,
                'date' => now()->format('F d, Y'),
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'address' => $user->address ?? 'Not provided',
                ],
                'transaction' => $transactionData,
                'items' => $items ?? [
                    [
                        'description' => $transactionData['paymentMethod'] ?? 'Payment',
                        'amount' => $transactionData['amount'],
                        'currency' => $transactionData['currency']
                    ]
                ],
                'subtotal' => $transactionData['amount'],
                'tax' => $transactionData['amount'] - $transactionData['amoutWithoutTax'],
                'total' => $transactionData['amount'],
            ];

            // Send email with HTML invoice
            \Mail::send(
                'mail.invoice', // Email template containing the HTML
                $invoiceData,
                function ($message) use ($user, $invoiceNumber) {
                    $message->to($user->email)
                        ->subject("Invoice #{$invoiceNumber} - Payment Confirmation");
                }
            );

            return true;
        } catch (\Exception $e) {
            Log::error('Error generating/sending invoice: ' . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('compressImage')) {
    /**
     * Compress and resize image while maintaining aspect ratio
     *
     * @param string $sourcePath Path to the source image
     * @param string $destinationPath Path where compressed image will be saved
     * @param int $quality Quality percentage (0-100)
     * @param int $maxWidth Maximum width in pixels (optional)
     * @param int $maxHeight Maximum height in pixels (optional)
     * @return bool Returns true on success, false on failure
     */
    function compressImage($sourcePath, $destinationPath, $quality = 75, $maxWidth = 1200, $maxHeight = 1200)
    {
        try {
            // Get image info
            $imageInfo = getimagesize($sourcePath);
            if ($imageInfo === false) {
                return false;
            }

            // Get original dimensions and mime type
            $originalWidth = $imageInfo[0];
            $originalHeight = $imageInfo[1];
            $mime = strtolower($imageInfo['mime']);

            // Calculate new dimensions while maintaining aspect ratio
            $ratio = $originalWidth / $originalHeight;

            if ($maxWidth / $maxHeight > $ratio) {
                $newWidth = $maxHeight * $ratio;
                $newHeight = $maxHeight;
            } else {
                $newWidth = $maxWidth;
                $newHeight = $maxWidth / $ratio;
            }

            // Create source image based on type
            $source = false;
            switch ($mime) {
                case 'image/jpeg':
                case 'image/jpg':
                case 'image/jfif':
                    $source = @imagecreatefromjpeg($sourcePath);
                    break;
                case 'image/png':
                    $source = @imagecreatefrompng($sourcePath);
                    break;
                case 'image/gif':
                    $source = @imagecreatefromgif($sourcePath);
                    break;
                case 'image/webp':
                    $source = @imagecreatefromwebp($sourcePath);
                    break;
                default:
                    // Try EXIF-based JFIF detection as fallback
                    if (exif_imagetype($sourcePath) === IMAGETYPE_JPEG) {
                        $source = @imagecreatefromjpeg($sourcePath);
                    }
                    break;
            }

            if ($source === false) {
                return false;
            }

            // Create new image canvas
            $compressed = imagecreatetruecolor($newWidth, $newHeight);

            // Preserve transparency for PNG and WebP
            if ($mime === 'image/png' || $mime === 'image/webp') {
                imagealphablending($compressed, false);
                imagesavealpha($compressed, true);
                $transparent = imagecolorallocatealpha($compressed, 0, 0, 0, 127);
                imagefill($compressed, 0, 0, $transparent);
            }

            // Resize and compress
            imagecopyresampled(
                $compressed,
                $source,
                0,
                0,
                0,
                0,
                $newWidth,
                $newHeight,
                $originalWidth,
                $originalHeight
            );

            // Ensure destination directory exists
            $destinationDir = dirname($destinationPath);
            if (!file_exists($destinationDir)) {
                mkdir($destinationDir, 0777, true);
            }

            // Save compressed image based on type
            $success = false;
            switch ($mime) {
                case 'image/jpeg':
                case 'image/jpg':
                case 'image/jfif':
                    $success = imagejpeg($compressed, $destinationPath, $quality);
                    break;
                case 'image/png':
                    $pngQuality = round((100 - $quality) / 10); // Convert to 0-9 scale
                    $success = imagepng($compressed, $destinationPath, $pngQuality);
                    break;
                case 'image/gif':
                    $success = imagegif($compressed, $destinationPath);
                    break;
                case 'image/webp':
                    $success = imagewebp($compressed, $destinationPath, $quality);
                    break;
            }

            // Free up memory
            imagedestroy($source);
            imagedestroy($compressed);

            return $success;

        } catch (Exception $e) {
            return false;
        }
    }
}