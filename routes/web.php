<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TotalProcessing;
use App\Models\Transaction;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/transactions', function () {
    return Inertia::render('Transactions',[
        'transactions' => Transaction::where('user_id', auth()->user()->id)->orderBy('updated_at', 'desc')->get()
    ]);
})->middleware(['auth', 'verified'])->name('transactions');

Route::get('/confirmation', function () {
    return Inertia::render('Confirmation');
})->middleware(['auth', 'verified'])->name('confirmation');

Route::get('/processing/generate-checkout', [TotalProcessing::class,  'generateCheckout'])->middleware(['auth', 'verified']);
Route::get('/processing/check-status', [TotalProcessing::class,  'checkStatus'])->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
