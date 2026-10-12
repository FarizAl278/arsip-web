<?php

use App\Http\Controllers\GoogleDriveController;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('drive')->group(function () {
    Route::get('/test', fn() => view('drive-test'));
    Route::get('/files', [GoogleDriveController::class, 'getFiles']);
    Route::post('/upload', [GoogleDriveController::class, 'uploadFile']);
});

Route::get('/google/auth', function () {
    $client = new Client();
    $client->setClientId(env('GOOGLE_CLIENT_ID'));
    $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
    $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
    $client->setScopes(Drive::DRIVE);
    $client->setAccessType('offline');
    $client->setPrompt('consent');

    return redirect($client->createAuthUrl());
});

Route::get('/google/callback', function(Request $request) {
    $client = new Client();
    $client->setClientId(env('GOOGLE_CLIENT_ID'));
    $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
    $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));

    $token = $client->fetchAccessTokenWithAuthCode($request->get('code'));

    return response()->json($token);
});