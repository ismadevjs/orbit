<?php


use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;


Route::get('maintenance', [SettingController::class, 'maintenance'])->name('maintenance');
Route::get('/ping-scheduler', function () {
    return response()->json([
        'last_user_rules_check' => Cache::get('last_user_rules_check'),
    ]);
});


    require __DIR__ . '/front.php';
    require __DIR__ . '/panel.php';
    require __DIR__ . '/auth.php';
    require __DIR__ . '/mobile.php';

