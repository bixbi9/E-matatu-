<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ProfileController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return auth('frontend.manager');
});

Route::get('/register', function () {
    return auth('frontend.register');
});

Route::view('/dashboard', 'adminpanel')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

    // Route::get('login',function(){
    //     return redirect()->intended(route('/dashboard'));
    // }); 
    Route::get('/dashboard', function () {
        return view('dashboard'); // Ensure you have a view named 'dashboard.blade.php'
    })->name('dashboard');
    
    // Route::view('register', 'admin')
    // ->middleware(['auth', 'verified'])
    // ->name('dashboard');  

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::get('/driver', function () {
    return view('frontend.driver');
});

Route::get('/manager', function () {
    return view('frontend.manager');
});

Route::get('/adminpanel', function () {
    return view('adminpanel');
});

Route::get('/dashboard', function () {
    return view('adminpanel');
});

Route::get('/layout', function () {
    return view('frontend.layout');
});

Route::get('/crew', function () {
    return view('frontend.crew');
});

Route::get('/index', function () {
    return view('frontend.index');
});

Route::get('/inspection', function () {
    return view('frontend.inspection');
});

Route::get('/incident', function () {
    return view('frontend.incident');
});

Route::get('/profile', function () {
    return view('frontend.profile');
});

Route::get('logout', '\App\Http\Controllers\Auth\AuthenticatedSessionController@destroy');
// Route::get('/dashboard');

