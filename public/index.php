<?php
// Set upload temp directory
ini_set('upload_tmp_dir', __DIR__ . '/../temp');
ini_set('upload_max_filesize', '10M');
ini_set('post_max_size', '10M');


use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));





// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__ . '/../storage/framework/Maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__ . '/../bootstrap/app.php')
    ->handleRequest(Request::capture());
