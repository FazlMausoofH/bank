<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MutationCompareController;
use App\Http\Controllers\MutationReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/compare', [MutationCompareController::class, 'index']);




Route::get('/', function () {
    return redirect('login');
});

Route::get('login', [AuthController::class, 'indexLogin']);
Route::post('login/user', [AuthController::class, 'login'])->name('login-user');
Route::get('dashboard', [DashboardController::class, 'index'])->middleware('auth');

Route::controller(MutationReportController::class)->middleware('auth')->group(function () {
    Route::get('mutasi', 'index');
    Route::get('mutasi/search', 'search')->name('search-mutation');
    Route::post('mutasi/create', 'create')->name('create-mutation');
    Route::put('mutasi/edit/{id}', 'update')->name('edit-mutation');
    Route::delete('mutasi/delete/{id}', 'delete')->name('delete-mutation');
});









