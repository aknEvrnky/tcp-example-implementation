<?php

use App\Http\Controllers\LessonController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/iterate-over-alive-connection', function () {
    $baseUri = "https://example-api.aknevrnky.dev/api/lectures";

    $elapsedTime = [];

    for ($id = 1; $id <= 10; $id++) {
        // initialize cURL
        $ch = curl_init();

        // set options
        curl_setopt($ch, CURLOPT_URL, "$baseUri/$id");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json', // we are expecting json response
            'Connection: keep-alive' // keep connection alive
        ]);

        // handle response, start timer
        $start = microtime(true);
        $response = curl_exec($ch);
        $end = microtime(true);

        // calculate elapsed time
        $elapsedTime["$baseUri/$id"] = sprintf('%.4f ms', ($end - $start) * 1000);
    }

    // return result
    return $elapsedTime;
});

Route::get('/iterate-over-alive-connection-2', function () {
    $baseUri = "https://example-api.aknevrnky.dev/api/lectures";

    $elapsedTime = [];

    // initialize cURL
    $ch = curl_init();

    for ($id = 1; $id <= 10; $id++) {
        // set options
        curl_setopt($ch, CURLOPT_URL, "$baseUri/$id");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json', // we are expecting json response
            'Connection: keep-alive' // keep connection alive
        ]);

        // handle response, start timer
        $start = microtime(true);
        $response = curl_exec($ch);
        $end = microtime(true);

        // calculate elapsed time
        $elapsedTime["$baseUri/$id"] = sprintf('%.4f ms', ($end - $start) * 1000);
    }

    // return result
    return $elapsedTime;
});

Route::get('/execute-with-multi-curl', function () {
    $baseUri = "https://example-api.aknevrnky.dev/api/lectures";

    $elapsedTime = [];

    $mh = curl_multi_init();
    $ch = [];

    for ($id = 1; $id <= 10; $id++) {
        $ch[$id] = curl_init("$baseUri/$id");
        curl_setopt($ch[$id], CURLOPT_POST, 1);
        curl_setopt($ch[$id], CURLOPT_RETURNTRANSFER, true);
        curl_multi_add_handle($mh, $ch[$id]);
    }

    do {
        // execute the handles
        curl_multi_exec($mh, $running);
    } while ($running > 0);

    for ($i = 1; $i <= 10; $i++) {
        curl_multi_remove_handle($mh, $ch[$i]);
        $elapsedTime["$baseUri/$i"] = sprintf('%.4f ms', curl_getinfo($ch[$i], CURLINFO_TOTAL_TIME) * 1000);
        curl_close($ch[$i]);
    }

    curl_multi_close($mh);

    return $elapsedTime;
});

Route::get('lessons', [LessonController::class, 'indexCoroutine']);
