<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/token', function () {
    // $response = Http::withOptions([
    //     'verify' => false,
    // ])->withHeaders([
    //     'Content-Type' => 'application/json',
    // ])->post('https://accounts.zoho.eu/oauth/v2/token', [
    //     "grant_type" => "authorization_code",
    //     "client_id" => "1000.XJUDNQDL4ZZZ1RK89HEVKD924WM74H",
    //     "client_secret" => "c284d09e36fb0edf39962f6806a6a7cad2361daf4f",
    //     "redirect_uri" => "http://localhost:8000/zohocrm",
    //     "code" => "1000.0175091b15eda8e61ad438571e6350b0.117d5ed6c096449254a4c5524785d72b",
    // ]);

    // dd($response);
});
