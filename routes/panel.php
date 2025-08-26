<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\AffiliateStageController;
use App\Http\Controllers\AksamController;
use App\Http\Controllers\AmenityController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\BackendController;
use App\Http\Controllers\BinancePayController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ContractThemeController;
use App\Http\Controllers\ContractTypeController;
use App\Http\Controllers\CryptoPaymentController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FeatureActivationController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\HeadingController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\IncentiveController;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\LandingPageSectionController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\NewsLetterController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PopupController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PricingPlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\ResponsibleController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SignRoleController;
use App\Http\Controllers\SmtpController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\SolanaController;
use App\Http\Controllers\SoundController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\VatAndTaxController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\WalletController;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Route::prefix('panel')->middleware(['auth'])->group(function () { {

        Route::post('cache-clear', [BackendController::class, 'cacheClear'])->name('cache.clear');
        Route::post('/upload-file', [FileUploadController::class, 'upload'])->name('upload.file');
        Route::post('/save-investor-data', [FileUploadController::class, 'saveInvestorData'])->name('save.investor.data');
        Route::get('get-ismail', [SettingController::class, 'logs'])->name('logs.index');

        Route::get('/', [BackendController::class, 'index'])->name("backend.index");

        Route::prefix('carousels')->group(function () {
            Route::get('/', [CarouselController::class, 'index'])->name('carousels.index');
            Route::post('post', [CarouselController::class, 'store'])->name('carousels.store');
            Route::post('update', [CarouselController::class, 'update'])->name('carousels.update');
            Route::post('delete/{carouselId}', [CarouselController::class, 'destroy'])->name('carousels.destroy');
        });

        Route::prefix('services')->group(function () {
            Route::get('/', [ServiceController::class, 'index'])->name('services.index');
            Route::post('post', [ServiceController::class, 'store'])->name('services.store');
            Route::post('update', [ServiceController::class, 'update'])->name('services.update');
            Route::delete('delete/{carouselId}', [ServiceController::class, 'destroy'])->name('services.destroy');
            Route::get('show/{id}', [ServiceController::class, 'show'])->name('services.show');
        });


        Route::prefix('testimonials')->group(function () {
            Route::get('/', [TestimonialController::class, 'index'])->name('testimonials.index');
            Route::post('post', [TestimonialController::class, 'store'])->name('testimonials.store');
            Route::post('update', [TestimonialController::class, 'update'])->name('testimonials.update');
            Route::delete('delete/{testimonialId}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');

            Route::prefix('videos')->group(function () {
                Route::get('/', [TestimonialController::class, 'indexVideo'])->name('testimonials.videos.index');
                Route::post('videoPost', [TestimonialController::class, 'storeVideo'])->name('testimonials.videos.store');
                Route::post('videoUpdate', [TestimonialController::class, 'updateVideo'])->name('testimonials.videos.update');
                Route::delete('videoDelete/{testimonialVideoId}', [TestimonialController::class, 'destroyVideo'])->name('testimonials.videos.destroy');
            });
        });


        //properties

        Route::prefix('properties')->group(function () {
            Route::get('/', [PropertyController::class, 'index'])->name('property.index');
            // Route::post('post', [ServiceController::class, 'store'])->name('services.store');
            // Route::post('update', [ServiceController::class, 'update'])->name('services.update');
            // Route::delete('delete/{carouselId}', [ServiceController::class, 'destroy'])->name('services.destroy');
        });


        Route::prefix('areas')->group(function () {
            Route::get('/', [AreaController::class, 'index'])->name('areas.index');
            Route::post('post', [AreaController::class, 'store'])->name('areas.store');
            Route::post('update/{id}', [AreaController::class, 'update'])->name('areas.update');
            Route::delete('delete/{carouselId}', [AreaController::class, 'destroy'])->name('areas.destroy');
            Route::get('{id}', [AreaController::class, 'show'])->name('areas.show');
        });

        Route::prefix('features')->group(function () {
            Route::get('/', [FeatureController::class, 'index'])->name('features.index');
            Route::post('post', [FeatureController::class, 'store'])->name('features.store');
            Route::post('update/{id}', [FeatureController::class, 'update'])->name('features.update');
            Route::delete('delete/{featureId}', [FeatureController::class, 'destroy'])->name('features.destroy');
            Route::get('/show/{id}', [FeatureController::class, 'show'])->name('features.show');
        });

        Route::prefix('facilities')->group(function () {
            Route::get('/', [FacilityController::class, 'index'])->name('facilities.index');
            Route::post('post', [FacilityController::class, 'store'])->name('facilities.store');
            Route::post('update/{id}', [FacilityController::class, 'update'])->name('facilities.update');
            Route::delete('delete/{facilityId}', [FacilityController::class, 'destroy'])->name('facilities.destroy');
            Route::get('{id}', [FacilityController::class, 'show'])->name('facilities.show');
        });


        Route::prefix('categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
            Route::post('post', [CategoryController::class, 'store'])->name('categories.store');
            Route::post('update/{id}', [CategoryController::class, 'update'])->name('categories.update');
            Route::delete('delete/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
            Route::get('{id}', [CategoryController::class, 'show'])->name('categories.show');
        });

        Route::prefix('projects')->group(function () {
            Route::get('/', [ProjectController::class, 'index'])->name('projects.index');
            Route::get('add', [ProjectController::class, 'add'])->name('projects.add');
            Route::post('post', [ProjectController::class, 'store'])->name('projects.store');
            Route::get('edit/{id}', [ProjectController::class, 'edit'])->name('projects.edit');
            Route::post('update', [ProjectController::class, 'update'])->name('projects.update');
            Route::delete('delete/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');
            Route::get('/show/{id}', [ProjectController::class, 'show'])->name('projects.show');
        });

        Route::prefix('videos')->group(function () {
            Route::get('/', [VideoController::class, 'index'])->name('videos.index');
            Route::post('post', [VideoController::class, 'store'])->name('videos.store');
            Route::post('update/{id}', [VideoController::class, 'update'])->name('videos.update');
            Route::delete('delete/{video}', [VideoController::class, 'destroy'])->name('videos.destroy');
            Route::get('{id}', [VideoController::class, 'show'])->name('videos.show');
        });


        Route::prefix('contacts')->group(function () {
            Route::get('/', [ContactController::class, 'index'])->name('contacts.index');
            // Route::get('/', [ContactController::class, 'post'])->name('frontend.contacts.post');
            // Route::post('update/{id}', [VideoController::class, 'update'])->name('contacts.update');
            // Route::delete('delete/{video}', [VideoController::class, 'destroy'])->name('contacts.destroy');
            // Route::get('{id}', [VideoController::class, 'show'])->name('contacts.show');
        });


        Route::prefix('socials')->group(function () {
            Route::get('/', [SocialController::class, 'index'])->name('socials.index');
            Route::post('/', [SocialController::class, 'store'])->name('socials.store');
            Route::get('show/{id}', [SocialController::class, 'show'])->name('socials.show');
            Route::put('/{id}', [SocialController::class, 'update'])->name('socials.update');
            Route::delete('/{id}', [SocialController::class, 'destroy'])->name('socials.destroy');
        });


        Route::prefix('leads')->group(function () {
            Route::get('/', [LeadController::class, 'index'])->name('leads.index');
            Route::post('post', [LeadController::class, 'store'])->name('leads.store');
            Route::post('storeWebsite', [LeadController::class, 'storeWebsite'])->name('leads.storeWebsite');
            Route::post('update/{id}', [LeadController::class, 'update'])->name('leads.update');
            Route::delete('delete/{featureId}', [LeadController::class, 'destroy'])->name('leads.destroy');
            Route::get('{id}', [LeadController::class, 'show'])->name('leads.show');
            Route::post('leads/{id}/status', [LeadController::class, 'updateStatus'])->name('leads.updateStatus');
            Route::get('show/{id}', [LeadController::class, 'show'])->name('leads.show');
        });


        Route::prefix('reviews')->group(function () {
            Route::get('/', [ReviewController::class, 'index'])->name('reviews.index');
            Route::post('post', [ReviewController::class, 'store'])->name('reviews.store');
            Route::post('update/{id}', [ReviewController::class, 'update'])->name('reviews.update');
            Route::delete('delete/{featureId}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
            Route::get('/show/{id}', [ReviewController::class, 'show'])->name('reviews.show');
        });

        Route::prefix('posts')->group(function () {
            Route::get('/', [PostController::class, 'index'])->name('posts.index');
            Route::post('post', [PostController::class, 'store'])->name('posts.store');
            Route::post('update/{id}', [PostController::class, 'update'])->name('posts.update');
            Route::delete('delete/{postId}', [PostController::class, 'destroy'])->name('posts.destroy');
            Route::get('/show/{id}', [PostController::class, 'show'])->name('posts.show');
        });

        Route::prefix('faqs')->group(function () {
            Route::get('/', [FaqController::class, 'index'])->name('faqs.index');
            Route::post('post', [FaqController::class, 'store'])->name('faqs.store');
            Route::post('update/{id}', [FaqController::class, 'update'])->name('faqs.update');
            Route::delete('delete/{id}', [FaqController::class, 'destroy'])->name('faqs.destroy');
            Route::get('/show/{id}', [FaqController::class, 'show'])->name('faqs.show');
        });


        Route::prefix('deals')->group(function () {
            Route::get('/', [DealController::class, 'index'])->name('deals.index');
        });

        //       // roles and permissions
        // Route::resource('roles', RoleController::class);
        // Route::resource('users', UserController::class);
        Route::get('permissions_init', [PermissionController::class, "permissions_init"])->name('stuff.permissions.permissions_init');
        Route::prefix('staff')->group(function () {
            Route::get('permission', [PermissionController::class, "stuffPermission"])->name('stuff.permissions');
            Route::get('permission-add', [PermissionController::class, "stuffPermissionAdd"])->name('stuff.permissions.add');
            Route::post('permission-post', [PermissionController::class, "stuffPermissionPost"])->name('stuff.permissions.post');
            Route::get('permission-edit/{id}', [PermissionController::class, "stuffPermissionEdit"])->name('stuff.permissions.edit');
            Route::post('permission-update', [PermissionController::class, "stuffPermissionUpdate"])->name('stuff.permissions.update');
            Route::delete('permission-delete/{id}', [PermissionController::class, "delete"])->name('stuff.permissions.delete');

            Route::get('roles', [PermissionController::class, "roles"])->name('stuff.roles');
            Route::post('roles-add', [PermissionController::class, "rolesAdd"])->name('stuff.roles.add');
            Route::post('roles-update', [PermissionController::class, "rolesUpdate"])->name('stuff.roles.update');
            Route::post('roles-delete', [PermissionController::class, "dropStuff"])->name('stuff.roles.delete');

            // added
            Route::get('stuff/permissions/data', [PermissionController::class, 'getStuffPermissionsData'])->name('stuff.permissions.data');
            Route::get('/stuff/roles/data', [PermissionController::class, 'getData'])->name('stuff.roles.data');
        });

        Route::prefix('profile')->group(function () {
            Route::get('/', [ProfileController::class, 'index'])->name('profile.show');
            Route::post('update', [ProfileController::class, 'update'])->name('profile.update');
            Route::post('change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');
        });


        Route::prefix('roleSign')->group(function () {
            Route::get('/', [SignRoleController::class, 'index'])->name('roles.index');
            Route::get('/show/{id}', [SignRoleController::class, 'show'])->name('roles.show');
            Route::post('/', [SignRoleController::class, 'store'])->name('roles.store');
            Route::put('/{id}', [SignRoleController::class, 'update'])->name('roles.update'); // Changed to PUT for updates
            Route::delete('/{id}', [SignRoleController::class, 'destroy'])->name('roles.destroy');
            Route::get('/api', [SignRoleController::class, 'apiIndex'])->name('roles.api.index'); // Adjusted for DataTables
        });


        Route::prefix('notifications')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
            Route::post('mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
            Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
            Route::post('/notification/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
            Route::get('show/{id}', [NotificationController::class, 'show'])->name('notifications.show');
            Route::post('sendNotification', [NotificationController::class, 'sendNotification'])->name('notifications.sendNotification');
        });

        Route::prefix('pages')->group(function () {
            Route::get('/', [PageController::class, 'index'])->name('pages.index');
            Route::post('post', [PageController::class, 'store'])->name('pages.store');
            Route::post('update', [PageController::class, 'update'])->name('pages.update');
            Route::delete('delete/{pageId}', [PageController::class, 'destroy'])->name('pages.destroy');
            Route::get('show/{id}', [PageController::class, 'show'])->name('pages.show');
        });

        Route::prefix('landing-page')->group(function () {
            Route::get('/', [LandingPageSectionController::class, 'index'])->name('landing-page.index');
            Route::get('show/{id}', [LandingPageSectionController::class, 'show'])->name('landing-page.show');
            Route::post('store', [LandingPageSectionController::class, 'store'])->name('landing-page.store');
            Route::post('update/{id}', [LandingPageSectionController::class, 'update'])->name('landing-page.update');
            Route::delete('delete/{id}', [LandingPageSectionController::class, 'destroy'])->name('landing-page.destroy');
            Route::get('create', [LandingPageSectionController::class, 'create'])->name('landing-page.create');
        });


        Route::prefix('menus')->group(function () {
            Route::get('/', [MenuController::class, 'index'])->name('menus.index');
            Route::post('/', [MenuController::class, 'store'])->name('menus.store');
            Route::get('/show/{id}', [MenuController::class, 'show'])->name('menus.show');
            Route::post('/update/{id}', [MenuController::class, 'update'])->name('menus.update');
            Route::delete('/destroy/{id}', [MenuController::class, 'destroy'])->name('menus.destroy');
        });
        Route::prefix('search')->group(function () {
            Route::post('/', [BackendController::class, 'search'])->name('backend.search');
        });

        Route::prefix('settings')->group(function () {


            Route::prefix('responsibles')->group(function () {
                Route::get('/', [ResponsibleController::class, 'index'])->name('responsibles.index');
                Route::post('post', [ResponsibleController::class, 'store'])->name('responsibles.store');
                Route::post('/responsibles/{id}', [ResponsibleController::class, 'update'])->name('responsibles.update');
                // Route::post('update', [ResponsibleController::class, 'update'])->name('responsibles.update');
                Route::delete('delete/{id}', [ResponsibleController::class, 'destroy'])->name('responsibles.destroy');
            });

            Route::get('header', [SettingController::class, 'header'])->name('settings.header');
            Route::get('settings', [SettingController::class, 'settings'])->name('settings.show');
            Route::prefix('update')->group(function () {
                Route::post('logo', [SettingController::class, 'updateLogo'])->name('settings.update.logo');
                Route::post('logo-white', [SettingController::class, 'updateLogoWhite'])->name('settings.update.logo_white');
                Route::post('favicon', [SettingController::class, 'updateFavicon'])->name('settings.update.favicon');
                Route::post('site-info', [SettingController::class, 'updateSiteInfo'])->name('settings.update.site_info');
                Route::post('contact-info', [SettingController::class, 'updateContactInfo'])->name('settings.update.contact_info');
                Route::post('map', [SettingController::class, 'updateMap'])->name('settings.update.map');
                Route::post('footer', [SettingController::class, 'updateFooter'])->name('settings.update.footer');
                Route::post('apps', [SettingController::class, 'updateApps'])->name('settings.update.apps');
            });


            Route::prefix('footer')->group(function () {
                Route::get('/', [SettingController::class, 'footer'])->name('settings.footer');

                // Footer Info
                Route::get('info', [SettingController::class, 'editInfo'])->name('footer.info.edit');
                Route::post('info/update', [SettingController::class, 'updateInfo'])->name('footer.info.update');

                // About Widget
                Route::get('about', [SettingController::class, 'editAbout'])->name('footer.about.edit');
                Route::post('about/update', [SettingController::class, 'updateAbout'])->name('footer.about.update');

                // Contact Info Widget
                Route::get('contact', [SettingController::class, 'editContact'])->name('footer.contact.edit');
                Route::post('contact/update', [SettingController::class, 'updateContact'])->name('footer.contact.update');

                // Link Widget
                Route::get('links', [SettingController::class, 'editLinks'])->name('footer.links.edit');
                Route::post('links/update', [SettingController::class, 'updateLinks'])->name('footer.links.update');

                // Footer Bottom (Copyright)
                Route::get('copyright', [SettingController::class, 'editCopyright'])->name('footer.copyright.edit');
                Route::post('copyright/update', [SettingController::class, 'updateCopyright'])->name('footer.copyright.update');

                // Social Links Widget
                Route::get('social', [SettingController::class, 'editSocial'])->name('footer.social.edit');
                Route::post('social/update', [SettingController::class, 'updateSocial'])->name('footer.social.update');

                // Payment Methods Widget
                Route::get('payment', [SettingController::class, 'editPayment'])->name('footer.payment.edit');
                Route::post('payment/update', [SettingController::class, 'updatePayment'])->name('footer.payment.update');
            });

            Route::get('/business-settings/update', [SettingController::class, 'update_header'])->name('business_settings.update');
            Route::get('server-status', [SettingController::class, 'serverStatus'])->name('settings.serverStatus');
        });

        Route::prefix('uploaded_files')->group(function () {
            Route::get('/{directory?}', [\App\Http\Controllers\UploadedFile::class, 'index'])->name('uploaded_files.index');
            Route::post('/{directory}/{file}', [\App\Http\Controllers\UploadedFile::class, 'destroy'])->name('uploaded_files.destroy');
        });

        Route::prefix('brands')->group(function () {
            Route::get('/', [BrandController::class, 'index'])->name('brands.index');
            Route::post('post', [BrandController::class, 'store'])->name('brands.store');
            Route::post('update/{id}', [BrandController::class, 'update'])->name('brands.update');
            Route::delete('delete/{id}', [BrandController::class, 'destroy'])->name('brands.destroy');
            Route::get('{id}', [BrandController::class, 'show'])->name('brands.show');
        });
        Route::prefix('colors')->group(function () {
            Route::get('/', [ColorController::class, 'index'])->name('colors.index');
            Route::post('post', [ColorController::class, 'store'])->name('colors.store');
            Route::post('update/{id}', [ColorController::class, 'update'])->name('colors.update');
            Route::delete('delete/{id}', [ColorController::class, 'destroy'])->name('colors.destroy');
            Route::get('get-data/{id}', [ColorController::class, 'show'])->name('colors.show');
            Route::get('/api/index', [ColorController::class, 'apiIndex'])->name('colors.api.index');
        });

        Route::prefix('tags')->group(function () {
            Route::get('/', [TagController::class, 'index'])->name('tags.index');
            Route::post('post', [TagController::class, 'store'])->name('tags.store');
            Route::post('update/{id}', [TagController::class, 'update'])->name('tags.update');
            Route::delete('delete/{tagId}', [TagController::class, 'destroy'])->name('tags.destroy');
            Route::get('/show/{id}', [TagController::class, 'show'])->name('tags.show');
        });



        Route::prefix('popups')->middleware(['auth', 'can:browse popups'])->group(function () {
            Route::get('/', [PopupController::class, 'index'])->name('popups.index');
            Route::post('/', [PopupController::class, 'store'])->name('popups.store');
            Route::post('/update/{id}', [PopupController::class, 'update'])->name('popups.update');
            Route::delete('/delete/{id}', [PopupController::class, 'destroy'])->name('popups.destroy');
            Route::get('/show/{id}', [PopupController::class, 'show'])->name('popups.show');
            Route::get('/api', [PopupController::class, 'apiIndex'])->name('popups.api.index');

        });


        Route::prefix('features_activation')->group(function () {
            Route::get('/', [FeatureActivationController::class, 'index'])->name('features_activation.index');
            Route::post('post', [FeatureActivationController::class, 'store'])->name('features_activation.store');
            Route::post('update/{id}', [FeatureActivationController::class, 'update'])->name('features_activation.update');
            Route::delete('delete/{tagId}', [FeatureActivationController::class, 'destroy'])->name('features_activation.destroy');
            Route::get('/show/{id}', [FeatureActivationController::class, 'show'])->name('features_activation.show');
            Route::post('/settings/toggle', [FeatureActivationController::class, 'toggleMaintenance'])->name('settings.toggle');
        });

        Route::prefix('currencies')->group(function () {
            Route::get('/', [CurrencyController::class, 'index'])->name('currencies.index');
            Route::post('post', [CurrencyController::class, 'store'])->name('currencies.store');
            Route::get('edit/{id}', [CurrencyController::class, 'edit'])->name('currencies.edit');
            Route::post('update', [CurrencyController::class, 'update'])->name('currencies.update');
            Route::delete('delete/{id}', [CurrencyController::class, 'destroy'])->name('currencies.destroy');
            Route::get('data', [CurrencyController::class, 'getCurrencies'])->name('currencies.data');
            Route::post('/currencies/update-side', [CurrencyController::class, 'updateSide'])
                ->name('currencies.update-side');

            Route::post('/currencies/update-status', [CurrencyController::class, 'updateStatus'])
                ->name('currencies.update-status');
        });

        Route::prefix('vat-taxes')->group(function () {
            Route::get('/', [VatAndTaxController::class, 'index'])->name('vat-taxes.index');
            Route::post('/', [VatAndTaxController::class, 'store'])->name('vat_taxes.store');
            Route::post('/update/{id}', [VatAndTaxController::class, 'update'])->name('vat_taxes.update'); // Change to PUT
            Route::delete('/delete/{id}', [VatAndTaxController::class, 'destroy'])->name('vat_taxes.destroy');
            Route::get('/api', [VatAndTaxController::class, 'apiIndex'])->name('vat_taxes.api.index');
            Route::get('/show/{id}', [VatAndTaxController::class, 'show'])->name('vat_taxes.show');
        });

        Route::prefix('smtp')->group(function () {
            Route::get('settings', [SmtpController::class, 'index'])->name('smtp.index');
            Route::post('update', [SmtpController::class, 'update'])->name('smtp.update');
            Route::get('send', [SmtpController::class, 'send'])->name('smtp.send');
            //            Route::post('settings', [SmtpController::class, 'store'])->name('smtp.store');
            //            Route::post('test', [SmtpController::class, 'testEmail'])->name('smtp.test');
        });

        Route::prefix('amenities')->group(function () {
            Route::get('/', [AmenityController::class, 'index'])->name('amenities.index');
            Route::post('/store', [AmenityController::class, 'store'])->name('amenities.store');
            Route::post('/update/{id}', [AmenityController::class, 'update'])->name('amenities.update');
            Route::delete('/delete/{id}', [AmenityController::class, 'destroy'])->name('amenities.destroy');
            Route::get('/api', [AmenityController::class, 'apiIndex'])->name('amenities.api.index');
            Route::get('/show/{id}', [AmenityController::class, 'show'])->name('amenities.show');
        });


        Route::prefix('headings')->group(function () {
            Route::get('/', [HeadingController::class, 'index'])->name('headings.index');
            Route::post('/store', [HeadingController::class, 'store'])->name('headings.store');
            Route::post('/update/{id}', [HeadingController::class, 'update'])->name('headings.update');
            Route::delete('/delete/{id}', [HeadingController::class, 'destroy'])->name('headings.destroy');
            Route::get('/api', [HeadingController::class, 'apiIndex'])->name('headings.api.index');
            Route::get('/show/{id}', [HeadingController::class, 'show'])->name('headings.show');
        });
        Route::prefix('achievements')->group(function () {
            Route::get('/',  [AchievementController::class, 'index'])->name('achievements.index');
            Route::post('post', [AchievementController::class, 'store'])->name('achievements.store');
            Route::post('update/{id}', [AchievementController::class, 'update'])->name('achievements.update');
            Route::delete('delete/{featureId}', [AchievementController::class, 'destroy'])->name('achievements.destroy');
            Route::get('/show/{id}', [AchievementController::class, 'show'])->name('achievements.show');
        });
        Route::prefix('members')->group(function () {
            Route::get('', [MemberController::class, 'index'])->name('members.index');
            Route::post('/store', [MemberController::class, 'store'])->name('members.store');
            Route::post('/{id}/update', [MemberController::class, 'update'])->name('members.update');
            Route::delete('/{id}', [MemberController::class, 'destroy'])->name('members.destroy');
            Route::get('/api/members', [MemberController::class, 'apiIndex'])->name('members.api.index');
            Route::get('/{id}', [MemberController::class, 'show'])->name('members.show');
        });

        Route::prefix('newsletter')->group(function () {
            Route::get('/', [NewsLetterController::class, 'index'])->name('newsletter.index');
            //
        });

        Route::prefix('types')->group(function () {
            Route::get('/', [TypeController::class, 'index'])->name('types.index');
            Route::post('post', [TypeController::class, 'store'])->name('types.store');
            Route::post('update/{id}', [TypeController::class, 'update'])->name('types.update');
            Route::delete('delete/{tagId}', [TypeController::class, 'destroy'])->name('types.destroy');
            Route::get('/show/{id}', [TypeController::class, 'show'])->name('types.show');
        });

        Route::prefix('images')->group(function () {
            Route::get('/', [ImageController::class, 'index'])->name('images.index');
            Route::post('/store', [ImageController::class, 'store'])->name('images.store');
            Route::post('/update/{id}', [ImageController::class, 'update'])->name('images.update');
            Route::delete('/delete/{id}', [ImageController::class, 'destroy'])->name('images.destroy');
            Route::get('/api', [ImageController::class, 'apiIndex'])->name('images.api.index');
            Route::get('/show/{id}', [ImageController::class, 'show'])->name('images.show');
        });

        Route::prefix('portfolios')->group(function () {
            Route::get('/', [PortfolioController::class, 'index'])->name('portfolios.index');
            Route::post('post', [PortfolioController::class, 'store'])->name('portfolios.store');
            Route::post('update/{id}', [PortfolioController::class, 'update'])->name('portfolios.update');
            Route::delete('delete/{postId}', [PortfolioController::class, 'destroy'])->name('portfolios.destroy');
            Route::get('/show/{id}', [PortfolioController::class, 'show'])->name('portfolios.show');
        });

        Route::prefix('affiliates')->group(function () {
            Route::get('/', [AffiliateStageController::class, 'index'])->name('affiliates.index');
            Route::post('post', [AffiliateStageController::class, 'store'])->name('affiliates.store');
            Route::post('update/{id}', [AffiliateStageController::class, 'update'])->name('affiliates.update');
            Route::delete('delete/{tagId}', [AffiliateStageController::class, 'destroy'])->name('affiliates.destroy');
            Route::get('/show/{id}', [AffiliateStageController::class, 'show'])->name('affiliates.show');
        });

        Route::prefix('incentives')->group(function () {
            Route::get('/', [IncentiveController::class, 'index'])->name('incentives.index');
            Route::post('post', [IncentiveController::class, 'store'])->name('incentives.store');
            Route::post('update/{id}', [IncentiveController::class, 'update'])->name('incentives.update');
            Route::delete('delete/{tagId}', [IncentiveController::class, 'destroy'])->name('incentives.destroy');
            Route::get('/show/{id}', [IncentiveController::class, 'show'])->name('incentives.show');
        });

        Route::prefix('contractType')->group(function () {
            Route::get('/', [ContractTypeController::class, 'index'])->name('contractType.index');
            Route::post('post', [ContractTypeController::class, 'store'])->name('contractType.store');
            Route::post('update/{id}', [ContractTypeController::class, 'update'])->name('contractType.update');
            Route::delete('delete/{tagId}', [ContractTypeController::class, 'destroy'])->name('contractType.destroy');
            Route::get('/show/{id}', [ContractTypeController::class, 'show'])->name('contractType.show');
            Route::get('/api', [ContractTypeController::class, 'apiIndex'])->name('contractType.api.index');
        });

        Route::prefix('contracts')->group(function () {
            Route::get('/', [ContractController::class, 'index'])->name('contracts.index');
            Route::post('post', [ContractController::class, 'store'])->name('contracts.store');
            Route::post('update/{id}', [ContractController::class, 'update'])->name('contracts.update');
            Route::delete('delete/{tagId}', [ContractController::class, 'destroy'])->name('contracts.destroy');
            Route::get('/show/{id}', [ContractController::class, 'show'])->name('contracts.show');
            Route::get('/api', [ContractController::class, 'apiIndex'])->name('contracts.api.index');
        });


        Route::prefix('contractThemes')->group(function () {
            Route::get('/', [ContractThemeController::class, 'index'])->name('contract_themes.index');
            Route::post('post', [ContractThemeController::class, 'store'])->name('contract_themes.store');
            Route::post('update/{id}', [ContractThemeController::class, 'update'])->name('contract_themes.update');
            Route::delete('delete/{tagId}', [ContractThemeController::class, 'destroy'])->name('contract_themes.destroy');
            Route::get('/show/{id}', [ContractThemeController::class, 'show'])->name('contract_themes.show');
            Route::get('/api', [ContractThemeController::class, 'apiIndex'])->name('contract_themes.api.index');
            Route::post('/activate/{id}', [ContractThemeController::class, 'toggleActive'])->name('contract_themes.active');
        });


        Route::prefix('sounds')->group(function () {
            Route::get('/', [SoundController::class, 'index'])->name('sounds.index');
            Route::post('post', [SoundController::class, 'store'])->name('sounds.store');
            Route::post('update/{id}', [SoundController::class, 'update'])->name('sounds.update');
            Route::delete('delete/{tagId}', [SoundController::class, 'destroy'])->name('sounds.destroy');
            Route::get('/show/{id}', [SoundController::class, 'show'])->name('sounds.show');
            Route::get('/api', [SoundController::class, 'apiIndex'])->name('sounds.api.index');
        });

        Route::prefix('jobs')->group(function () {
            Route::get('/', [JobController::class, 'index'])->name('jobs.index');
            Route::post('post', [JobController::class, 'store'])->name('jobs.store');
            Route::post('update/{id}', [JobController::class, 'update'])->name('jobs.update');
            Route::delete('delete/{tagId}', [JobController::class, 'destroy'])->name('jobs.destroy');
            Route::get('/show/{id}', [JobController::class, 'show'])->name('jobs.show');
            Route::get('/api', [JobController::class, 'apiIndex'])->name('jobs.api.index');
        });

        Route::prefix('employees')->group(function () {
            Route::get('/', [EmployeController::class, 'index'])->name('employees.index');
            Route::post('post', [EmployeController::class, 'store'])->name('employees.store');
            Route::post('update/{id}', [EmployeController::class, 'update'])->name('employees.update');
            Route::delete('delete/{tagId}', [EmployeController::class, 'destroy'])->name('employees.destroy');
            Route::get('/show/{id}', [EmployeController::class, 'show'])->name('employees.show');
            Route::get('/api', [EmployeController::class, 'apiIndex'])->name('employees.api.index');
        });


        Route::prefix('payment_methods')->group(function () {
            Route::get('/', [PaymentMethodController::class, 'index'])->name('payment_methods.index');
            Route::post('post', [PaymentMethodController::class, 'store'])->name('payment_methods.store');
            Route::post('update/{id}', [PaymentMethodController::class, 'update'])->name('payment_methods.update');
            Route::post('delete/{cardId}', [PaymentMethodController::class, 'destroy'])->name('payment_methods.destroy');
            Route::get('/show/{id}', [PaymentMethodController::class, 'show'])->name('payment_methods.show');
            Route::get('/api', [PaymentMethodController::class, 'apiIndex'])->name('payment_methods.api.index');

            // stripe  payments
            Route::get('/checkout', [PaymentMethodController::class, 'checkout'])->name('checkout');
            Route::post('/process-payment', [PaymentMethodController::class, 'processPayment'])->name('process.payment');
            //            Route::get('/transactions', [PaymentMethodController::class, 'transactions']);

            // bank transfert
            Route::get('/bank-checkout', [PaymentMethodController::class, 'bankCheckout'])->name('bank.checkout');
            Route::post('/process-save-bank-transfer', [PaymentMethodController::class, 'saveBankTransfer'])->name('process.saveBankTransfer');
            Route::get('/bank-checkout-withdrawal', [PaymentMethodController::class, 'bankCheckoutWithdrawal'])->name('bank.checkout.withdrawal');
            Route::post('/process-save-bank-transfer-withdrawal', [PaymentMethodController::class, 'saveBankTransferWithdrawal'])->name('process.saveBankTransfer.withdrawal');
            // cash transfert
            Route::get('/cash-checkout', [PaymentMethodController::class, 'cashCheckout'])->name('cash.checkout');
            Route::post('/process-save-cash-transfer', [PaymentMethodController::class, 'saveCashTransfer'])->name('process.saveCashTransfer');
            Route::get('/cash-checkout-withdrawal', [PaymentMethodController::class, 'cashCheckoutWithdrawal'])->name('cash.checkout.withdrawal');
            Route::post('/process-save-cash-transfer-withdrawal', [PaymentMethodController::class, 'saveCashTransferWithdrawal'])->name('process.saveCashTransfer.withdrawal');

            // crypto transfert
            Route::get('/crypto-checkout', [PaymentMethodController::class, 'cryptoCheckout'])->name('crypto.checkout');
            Route::post('/process-save-crypto-transfer', [PaymentMethodController::class, 'saveCryptoTransfer'])->name('process.saveCryptoTransfer');

            Route::get('/crypto-checkout-withdrawal', [PaymentMethodController::class, 'cryptoCheckoutWithdrawal'])->name('crypto.checkout.withdrawal');
            Route::post('/process-save-crypto-transfer-withdrawal', [PaymentMethodController::class, 'saveCryptoTransferWithdrawal'])->name('process.saveCryptoTransfer.withdrawal');


            // crypto pay
            Route::get('/crypto/payment', [CryptoPaymentController::class, 'showPaymentPage'])->name('crypto.payment');
            Route::post('/crypto/verify', [CryptoPaymentController::class, 'verifyTransaction'])->name('crypto.verify');

            Route::post('/generate-qr', [PaymentMethodController::class, 'generateQr'])->name('generate.qr');

            Route::prefix('transactions')->group(function () {
                Route::get('/', [TransactionController::class, 'index'])->name('transactions.index');
                Route::get('/fetch', [TransactionController::class, 'fetch'])->name('transactions.fetch');
                Route::get('/fetch/{userId}', [TransactionController::class, 'fetchUserId'])->name('transactions.fetch.user');
                Route::post('/', [TransactionController::class, 'store'])->name('transactions.store');
                Route::delete('/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
                Route::post('accept', [TransactionController::class, 'accept'])->name('transactions.accept');
                Route::post('declined', [TransactionController::class, 'declined'])->name('transactions.declined');
            });
        });


        Route::prefix('aksams')->group(function () {
            Route::get('/', [AksamController::class, 'index'])->name('aksams.index');
            Route::post('post', [AksamController::class, 'store'])->name('aksams.store');
            Route::post('update/{id}', [AksamController::class, 'update'])->name('aksams.update');
            Route::delete('delete/{tagId}', [AksamController::class, 'destroy'])->name('aksams.destroy');
            Route::get('/show/{id}', [AksamController::class, 'show'])->name('aksams.show');
            Route::get('/api', [AksamController::class, 'apiIndex'])->name('aksams.api.index');
        });



        Route::prefix('investors')->as('investors.')->group(function () {
            Route::post('update_comission', [InvestorController::class, 'update_comission'])->name('update_comission');

            Route::get('/monthly-statement/{investorId}', [InvestorController::class, 'showMonthlyStatement'])->name('monthly.statement');
            Route::get('/generate-monthly-statement/{investorId}', [InvestorController::class, 'generateMonthlyStatement'])->name('generate-monthly-statement');


            Route::get('/', [InvestorController::class, 'index'])->name('index');

            Route::get('/detail/{id}', [InvestorController::class, 'detailId'])->name('details.id');
            Route::get('/edit-contract/{investorId}', [InvestorController::class, 'editContract'])->name('edit.contract');
            Route::post('/send', [InvestorController::class, 'sendContract'])->name('send.contract');

            // view and sign the contracts
            Route::get('/contract/sign/{contractId}', [InvestorController::class, 'viewContract'])->name('contract.view');
            Route::post('/contract/sign/{contractId}', [InvestorController::class, 'saveSignature'])->name('contract.saveSignature');
            // upload from the company
            Route::post('/investors/{investor}/upload-contract', [InvestorController::class, 'uploadSignedContract'])->name('contract.uploadSignedContract');

            Route::post('/contract/change-status', [InvestorController::class, 'changeStatus'])->name('contract.changeStatus');
            Route::post('delete', [InvestorController::class, 'delete'])->name('delete');


            Route::post('/investor/update-capital', [InvestorController::class, 'updateCapital'])->name('updateCapital');

            Route::post('/investor/update-duration', [InvestorController::class, 'updateDuration'])->name('update.duration');

            // managers
            // Route::get('managers_requests', [InvestorController::class, 'managers_requests'])->name('managers_requests.index');
            // Route::get('manager/api', [InvestorController::class, 'managerApiIndex'])->name('managers.api.index');
        });



            // investor panel only that can be seen only by admin and investor itself
            Route::prefix('investor')->as('investor.')->group(function () {
            Route::prefix('kyc')->group(function () {


                //steps

                Route::get('step1', [KycController::class, 'stepOne'])->name('kyc.step.one');
                Route::post('step1', [KycController::class, 'submitStepOne'])->name('kyc.step.one.submit');

                Route::get('step2', [KycController::class, 'stepTwo'])->name('kyc.step.two');
                Route::post('step2', [KycController::class, 'submitStepTwo'])->name('kyc.step.two.submit');


                Route::get('step3', [KycController::class, 'stepThree'])->name('kyc.step.three');
                Route::post('step3', [KycController::class, 'submitStepThree'])->name('kyc.step.three.submit');

                Route::get('step4', [KycController::class, 'stepFour'])->name('kyc.step.four');
                Route::post('step4', [KycController::class, 'submitStepFour'])->name('kyc.step.four.submit');
                // end steps



                Route::get('kyc-verification', [KycController::class, 'kycVerification'])->name('kyc-verification');
                Route::get('steps', [KycController::class, 'steps'])->name('kyc-steps');
                Route::post('/kyc/submit', [KYCController::class, 'submit'])->name('kyc.submit');
                Route::post('/kyc/upload', [KycController::class, 'upload'])->name('kyc.upload');


                Route::get('/admin/kyc', [KYCController::class, 'index'])->name('admin.kyc.index');
                Route::post('/admin/kyc/{id}/approve', [KYCController::class, 'approve'])->name('admin.kyc.approve');
                Route::post('/admin/kyc/{id}/reject', [KYCController::class, 'reject'])->name('admin.kyc.reject');
                Route::post('/admin/kyc/{id}/needtopay', [KYCController::class, 'needtopay'])->name('admin.kyc.needtopay');
                Route::post('/admin/kyc/{id}/processing', [KYCController::class, 'processing'])->name('admin.kyc.processing');
                Route::post('/admin/kyc/{id}/pending', [KYCController::class, 'pending'])->name('admin.kyc.pending');


                Route::post('/investor/kyc/upload-image', [KycController::class, 'uploadImage'])->name('kyc.uploadImage');

                // 2) Route for final form submission after the image is uploaded
                Route::post('/investor/kyc-steps', [KycController::class, 'finalSubmission'])->name('kycSteps');
            });

            Route::prefix('investor_analytics')->group(function () {



            });

            Route::prefix('investor_analytics')->middleware('user_active')->group(function () {
                Route::get('/', [InvestorController::class, 'analytics'])->name('investor_analytics.index');
                Route::post('post', [InvestorController::class, 'store'])->name('investor_analytics.store');
                Route::post('update/{id}', [InvestorController::class, 'update'])->name('investor_analytics.update');
                Route::delete('delete/{tagId}', [InvestorController::class, 'destroy'])->name('investor_analytics.destroy');
                Route::get('/show/{id}', [InvestorController::class, 'show'])->name('investor_analytics.show');
                Route::get('/api', [InvestorController::class, 'apiIndex'])->name('investor_analytics.api.index');
            });

            Route::prefix('investor_deposit')->middleware('user_send_data')->group(function () {
                Route::get('/', [InvestorController::class, 'deposits'])->name('investor_deposit.index');
                Route::post('post', [InvestorController::class, 'store'])->name('investor_deposit.store');
                Route::post('update/{id}', [InvestorController::class, 'update'])->name('investor_deposit.update');
                Route::delete('delete/{tagId}', [InvestorController::class, 'destroy'])->name('investor_deposit.destroy');
                Route::get('/show/{id}', [InvestorController::class, 'show'])->name('investor_deposit.show');
                Route::get('/api', [InvestorController::class, 'apiIndex'])->name('investor_deposit.api.index');
            });


            Route::prefix('investor_withdraw')->middleware('user_active')->group(function () {
                Route::get('/', [InvestorController::class, 'withdrawals'])->name('investor_withdraw.index');
                Route::post('post', [InvestorController::class, 'store'])->name('investor_withdraw.store');
                Route::post('update/{id}', [InvestorController::class, 'update'])->name('investor_withdraw.update');
                Route::delete('delete/{tagId}', [InvestorController::class, 'destroy'])->name('investor_withdraw.destroy');
                Route::get('/show/{id}', [InvestorController::class, 'show'])->name('investor_withdraw.show');
                Route::get('/api', [InvestorController::class, 'apiIndex'])->name('investor_withdraw.api.index');
            });


            Route::prefix('investor_contract')->group(function () {
                Route::get('/', [InvestorController::class, 'contracts'])->name('investor_contract.index');
                Route::post('post', [InvestorController::class, 'store'])->name('investor_contract.store');
                Route::post('update/{id}', [InvestorController::class, 'update'])->name('investor_contract.update');
                Route::delete('delete/{tagId}', [InvestorController::class, 'destroy'])->name('investor_contract.destroy');
                Route::get('/show/{id}', [InvestorController::class, 'show'])->name('investor_contract.show');
                Route::get('/api', [InvestorController::class, 'apiIndex'])->name('investor_contract.api.index');
            });


            Route::prefix('investor_transaction_history')->middleware('user_active')->group(function () {
                Route::get('/', [InvestorController::class, 'transactionHistory'])->name('investor_transaction_history.index');
                Route::post('post', [InvestorController::class, 'store'])->name('investor_transaction_history.store');
                Route::post('update/{id}', [InvestorController::class, 'update'])->name('investor_transaction_history.update');
                Route::delete('delete/{tagId}', [InvestorController::class, 'destroy'])->name('investor_transaction_history.destroy');
                Route::get('/show/{id}', [InvestorController::class, 'show'])->name('investor_transaction_history.show');
                Route::get('/api', [InvestorController::class, 'apiIndex'])->name('investor_transaction_history.api.index');
            });

            Route::prefix('referrals')->middleware('user_active')->group(function () {
                Route::get('/generate', [ReferralController::class, 'generate'])->name('affiliates.generate');
                Route::get('/generate-link', [ReferralController::class, 'generateLink'])->name('affiliates.generate-link');
                Route::get('/my-referrals', [ReferralController::class, 'myReferrals'])->name('affiliates.my-referrals');
            });

            Route::prefix('wallet')->middleware('user_send_data')->group(function () {
                Route::get('/', [WalletController::class, 'index'])->name('wallet.index');
            });
        });

        Route::prefix('plans')->group(function () {
            Route::get('/', [PricingPlanController::class, 'index'])->name('plans.index');
            Route::get('/api', [PricingPlanController::class, 'apiIndex'])->name('plans.api.index');
            Route::post('/store', [PricingPlanController::class, 'store'])->name('plans.store');
            Route::get('/show/{id}', [PricingPlanController::class, 'show'])->name('plans.show');
            Route::post('/update/{id}', [PricingPlanController::class, 'update'])->name('plans.update');
            Route::delete('/delete/{id}', [PricingPlanController::class, 'destroy'])->name('plans.destroy');
        });
    }
});
