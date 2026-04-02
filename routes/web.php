<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\InspectorController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

// Dashboard — real data
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile',        [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',      [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',     [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Domain resource routes (auth required)
Route::middleware('auth')->group(function () {
    Route::resource('drivers',      DriverController::class);
    Route::resource('vehicles',     VehicleController::class);
    Route::resource('managers',     ManagerController::class);
    Route::resource('inspectors',   InspectorController::class);
    Route::resource('inspections',  InspectionController::class);
    Route::resource('maintenances', MaintenanceController::class);
    Route::resource('insurances',   InsuranceController::class);
    Route::resource('roles',        RoleController::class);
    Route::resource('routes',       RouteController::class);
});

// Frontend section views
Route::middleware('auth')->group(function () {
    Route::get('/crew',        [DashboardController::class, 'crew'])->name('crew');
    Route::post('/crew/assign', [DashboardController::class, 'assignCrewRoute'])->name('crew.assign');
    Route::get('/inspection',  [DashboardController::class, 'inspections'])->name('inspection');
    Route::get('/matatus',     [DashboardController::class, 'matatus'])->name('matatus');
    Route::post('/matatus/assign', [DashboardController::class, 'assignMatatu'])->name('matatus.assign');
    Route::get('/manager',     [DashboardController::class, 'matatus'])->name('manager');
    Route::get('/maintenance', [DashboardController::class, 'maintenance'])->name('maintenance');
    Route::get('/insurance',   [DashboardController::class, 'insurance'])->name('insurance');
    Route::get('/routes',      [DashboardController::class, 'routeMap'])->name('routes');
    Route::post('/insurance',  [DashboardController::class, 'storeInsurance'])->name('insurance.store');
    Route::get('/adminpanel',  [DashboardController::class, 'index'])->name('adminpanel');
});

require __DIR__.'/auth.php';
