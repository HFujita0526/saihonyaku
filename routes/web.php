<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $arrayNext = json_decode(Storage::get('translation/logs/log_Next.json'), true);
    $arrayTranslation = json_decode(Storage::get('translation/json/' . $arrayNext['date'] . '/' . $arrayNext['date'] . '-000.json'), true);
    $originText = nl2br(e($arrayTranslation[0]['text_ja']));
    return view('saihonyaku', compact('originText'));
});
