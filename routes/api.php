<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\AffiliateStageController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\IncentiveController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\LandingPageSectionController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\NewsLetterController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
use App\Http\Controllers\Controller;
use App\Models\Employe;
use App\Models\Investor;
use App\Models\ReferralLink;
use App\Models\User;
use App\Models\Wallet;
use App\Rules\CloudflareTurnstile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });
    Route::get('/user-avatar', function (Request $request) {
        $user = User::find($request->user()->id);
        return response()->json($user->avatar);
    });
});
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);



Route::prefix('website')->group(function () {
    // uploads
    Route::post('/kyc/upload-image', [KycController::class, 'uploadImageToMinio'])->name('kyc.upload.image');

    Route::prefix('kyc')->group(function () {
    Route::prefix('pages')->group(function () {
        Route::get('/{name}', function($name) {
            return response()->json(getPage($name));
        });
    });
    Route::post('step1', [KycController::class, 'submitStepOne'])->name('kyc.step.one.submit');
    Route::post('step2', [KycController::class, 'submitStepTwo'])->name('kyc.step.two.submit');
    Route::post('step3', [KycController::class, 'submitStepThree'])->name('kyc.step.three.submit');
    Route::post('step4', [KycController::class, 'submitStepFour'])->name('kyc.step.four.submit');
});




    Route::prefix('carousels')->group(function () {
        Route::get('/', [CarouselController::class, 'apiIndex'])->name('carousels.api.index');
    });

    Route::prefix('sections')->group(function () {
        Route::get('/', [LandingPageSectionController::class, 'getSections'])->name('sections.api.index');
        Route::get('/section/{uniqueName}', [LandingPageSectionController::class, 'getSectionByUniqueName'])->name('sections.api.show');
    });

    Route::prefix('projects')->group(function () {
        Route::get('/', [ProjectController::class, 'apiIndex'])->name('projects.api.index');
        Route::get('getProjects', [ProjectController::class, 'apiGetProjects'])->name('projects.api.getProjects');
        Route::get('getThreeProjects', [ProjectController::class, 'getThreeProjects'])->name('projects.api.getThreeProjects');
        Route::get('details/{id}', [ProjectController::class, 'getProjectDetails'])->name('projects.api.getProjectDetails');
    });

    Route::prefix('services')->group(function () {
        Route::get('/', [ServiceController::class, 'getServices'])->name('services.api.getServices');
    });

    Route::prefix('socials')->group(function () {
        Route::get('/', [SocialController::class, 'getSocials'])->name('socials.api.getSocials');
    });

    Route::prefix('videos')->group(function () {
        Route::get('/', [VideoController::class, 'getVideos'])->name('videos.api.index');
    });

    Route::prefix('reviews')->group(function () {
        Route::get('/', [ReviewController::class, 'getReviews'])->name('reviews.api.index');
    });

    Route::prefix('settings')->group(function () {
        Route::get('footer', [SettingController::class, 'footer'])->name('settings.api.index');
    });
    Route::prefix('newsletter')->group(function () {
        Route::get('/', [NewsLetterController::class, 'apiIndex'])->name('newsletter.api.index');
    });




});

Route::prefix('/')->middleware('auth:sanctum')->group(function () {
    Route::post('user/password', [PasswordController::class, 'user_update']);

     Route::prefix('transactions')->group(function () {
        Route::get('/', [InvestorController::class, 'transactionHistoryApi'])->name('investor_transaction_history.index');
    });


    Route::prefix('profile')->group(function () {
        Route::post('avatar', [ProfileController::class, 'updateAvatar']);
        Route::post('update', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');
    });




    Route::prefix('carousels')->group(function () {
        Route::get('/', [CarouselController::class, 'apiIndex'])->name('carousels.api.index');
    });
    Route::prefix('services')->group(function () {
        Route::get('/', [ServiceController::class, 'apiIndex'])->name('services.api.index');
    });
    Route::prefix('testimonials')->group(function () {
        Route::get('', [TestimonialController::class, 'apiIndex'])->name('testimonials.api.index');
        Route::get('images', [TestimonialController::class, 'apiImage'])->name('testimonials.api.image.index');
        Route::get('video', [TestimonialController::class, 'apiVideo'])->name('testimonials.api.video.index');
    });
    Route::prefix('areas')->group(function () {
        Route::get('/', [AreaController::class, 'apiIndex'])->name('areas.api.index');
    });
    Route::prefix('features')->group(function () {
        Route::get('/', [FeatureController::class, 'apiIndex'])->name('features.api.index');
    });

    Route::prefix('facilities')->group(function () {
        Route::get('/', [FacilityController::class, 'apiIndex'])->name('facilities.api.index');
    });

    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'apiIndex'])->name('categories.api.index');
    });

    Route::prefix('projects')->group(function () {
        Route::get('/', [ProjectController::class, 'apiIndex'])->name('projects.api.index');
        Route::get('getProjects', [ProjectController::class, 'apiGetProjects'])->name('projects.api.getProjects');
    });
    Route::prefix('videos')->group(function () {
        Route::get('/', [VideoController::class, 'apiIndex'])->name('videos.api.index');
    });

    Route::prefix('contacts')->group(function () {
        Route::get('/', [ContactController::class, 'apiIndex'])->name('contacts.api.index');
    });

    Route::prefix('leads')->group(function () {
        // Route::get('/', [LeadController::class, 'apiIndex'])->name('leads.api.index');
        Route::get('leads/api', [LeadController::class, 'getLeadsData'])->name('leads.api.index');
    });

    Route::prefix('reviews')->group(function () {
        Route::get('/', [ReviewController::class, 'apiIndex'])->name('reviews.api.index');
    });
    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'apiIndex'])->name('posts.api.index');
    });
    Route::prefix('socials')->group(function () {
        Route::get('/api', [SocialController::class, 'apiIndex'])->name('socials.api.index');
    });

    Route::prefix('faqs')->group(function () {
        Route::get('/api', [FaqController::class, 'apiIndex'])->name('faqs.api.index');
    });
    Route::prefix('wallet')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('wallet.api.index');
    });
        Route::prefix('transactions')->group(function () {
        Route::get('/fetch/{userId}', [TransactionController::class, 'fetchUserId'])->name('transactions.fetch.user.api');
    });
    Route::group(['prefix' => 'deals'], function () {
        Route::get('/', [DealController::class, 'index'])->name('deals.index');
        Route::get('/api', [DealController::class, 'apiIndex'])->name('deals.api.index');
        Route::get('/api/roles', [DealController::class, 'roles'])->name('deals.api.roles');
        Route::get('/api/deals/api/roles/by/{roleId}', [DealController::class, 'getUsersByRole'])->name('deals.api.roles.by.id');
        ;
        Route::post('/update/responsible/{deal}', [DealController::class, 'updateResponsible'])->name('deals.api.update.responsible');
        Route::get('/deals/users', [DealController::class, 'fetchUsers'])->name('deals.api.users');
    });
    Route::prefix('signRoles')->group(function () {
        Route::get('/api', [FaqController::class, 'apiIndex'])->name('roles.api.index');
    });
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'apiIndex'])->name('notifications.api.index');

    });

    Route::prefix('pages')->group(function () {
        Route::get('/', [PageController::class, 'apiIndex'])->name('pages.api.index');
    });

    Route::prefix('landing-page')->group(function () {
        Route::get('/', [LandingPageSectionController::class, 'apiIndex'])->name('landing-page.api.index');
    });

    Route::prefix('menus')->group(function () {
        Route::get('/api', [MenuController::class, 'apiIndex'])->name('menus.api.index');
    });
    Route::prefix('brands')->group(function () {
        Route::get('/', [BrandController::class, 'apiIndex'])->name('brands.api.index');
    });
    Route::prefix('tags')->group(function () {
        Route::get('/', [TagController::class, 'apiIndex'])->name('tags.api.index');
    });
    Route::prefix('currencies')->group(function () {
        Route::get('/', [CurrencyController::class, 'getCurrencies'])->name('currencies.api.getCurrencies');
    });
    Route::prefix('achievements')->group(function () {
        Route::get('/', [AchievementController::class, 'apiIndex'])->name('achievements.api.index');
    });
    Route::prefix('types')->group(function () {
        Route::get('/', [TypeController::class, 'apiIndex'])->name('types.api.index');
    });
    Route::prefix('portfolios')->group(function () {
        Route::get('/', [PortfolioController::class, 'apiIndex'])->name('portfolios.api.index');
    });
    Route::prefix('affiliates')->group(function () {
        Route::get('/', [AffiliateStageController::class, 'apiIndex'])->name('affiliates.api.index');
    });
    Route::prefix('incentives')->group(function () {
        Route::get('/', [IncentiveController::class, 'apiIndex'])->name('incentives.api.index');
    });




    //investor api area

     Route::prefix('investor_analytics')->group(function () {
        Route::get('/', [InvestorController::class, 'analytics'])->name('investor_analytics.index');
    });



});

