<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TotalProcessingController;
use App\Models\Transaction;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/transactions', function () {
        return Inertia::render('Transactions',[
            'transactions' => Transaction::query()
                ->where('user_id', auth()->user()->id)
                ->latest('updated_at')
                ->get()
        ]);
    })->name('transactions');

    Route::get('/confirmation', function () {
        return Inertia::render('Confirmation');
    })->name('confirmation');

    Route::get('/processing/generate-checkout', [TotalProcessingController::class,  'generateCheckout']);
    Route::get('/processing/check-status', [TotalProcessingController::class,  'checkStatus']);
});

require __DIR__.'/auth.php';
