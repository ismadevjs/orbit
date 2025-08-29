<?php


use App\Http\Controllers\ContactController;
use App\Http\Controllers\FrontEndController;
use App\Http\Controllers\NewsLetterController;
use App\Http\Controllers\PopupController;
use App\Models\Investor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return redirect()->route('backend.index');
// });
Route::prefix('/')->middleware(['maintenance', 'set_locale'])->group(function () {
   Route::get('/', [FrontEndController::class, 'index'])->name("frontend.index");
   Route::prefix('/')->group(function () {
       Route::post('newsletter', [NewsLetterController::class, 'store'])->name("newsletter.store");
   });

   Route::prefix('about')->group(function () {
       Route::get('/', [FrontEndController::class, 'about'])->name("frontend.about");
   });

   Route::prefix('become')->group(function () {
    Route::get('/', [FrontEndController::class, 'become'])->name("frontend.become");
});


   Route::prefix('services')->group(function () {
       Route::get('/', [FrontEndController::class, 'services'])->name("frontend.services");
   });

   Route::prefix('page')->group(function () {
       Route::get('/{slug}', [FrontEndController::class, 'page'])->name("frontend.page");
   });

   Route::prefix('members')->group(function () {
       Route::get('/', [FrontEndController::class, 'members'])->name("frontend.members");
       Route::get('/detail/{id}', [FrontEndController::class, 'membersId'])->name("frontend.members.id");
       Route::post('post', [ContactController::class, 'store'])->name('frontend.contacts.store');
   });


   Route::prefix('projects')->group(function () {
       Route::get('/', [FrontEndController::class, 'projects'])->name("frontend.projects");
       Route::get('/search', [FrontEndController::class, 'search'])->name("frontend.projects.search");
       Route::get('/detail/{id}', [FrontEndController::class, 'projectsId'])->name("frontend.projects.id");
   });



   Route::prefix('contact')->group(function () {
       Route::get('/', [FrontEndController::class, 'contact'])->name("frontend.contact");
       Route::post('/', [ContactController::class, 'store'])->name('frontend.contacts.post.front');
   });

   Route::prefix('blog')->group(function () {
       Route::get('/', [FrontEndController::class, 'filter'])->name("frontend.blog");
       Route::get('/filter', [FrontEndController::class, 'filter'])->name("frontend.blog.filter");
       Route::get('post/{id}', [FrontEndController::class, 'posts'])->name("frontend.blog.posts");
   });

   Route::get('thankyou', [FrontEndController::class, 'thankyou'])->name("frontend.thankyou");


   Route::get('/popups/active', [PopupController::class, 'fetchActive'])
                ->name('popups.fetch.active');


   Route::get('testing', function () {
    // Fetch users with the "Investor" role
    $users = User::role('Investor')->get();

    foreach ($users as $user) {
        $investor = Investor::firstOrCreate(
            ['user_id' => $user->id], // Condition to check if the record exists
            [
                'employe_id' => 3,
                'reference' => 'direct',
                'invest_date' => Carbon::now(),
            ]
        );

        // Log only if a new investor was inserted
        if ($investor->wasRecentlyCreated) {
            Log::info("New Investor Inserted", [
                'user_id' => $investor->user_id,
                'reference' => $investor->reference,
                'invest_date' => $investor->invest_date,
            ]);
        }
    }

    return response()->json(['message' => 'Investors inserted if not exists']);
})->name('testing');
});
