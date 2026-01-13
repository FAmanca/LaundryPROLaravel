<?php

use App\Mail\LaundryStatusMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\OrderAdminController;
use App\Http\Controllers\ParfumeAdminController;
use App\Http\Controllers\CustomerAdminController;
use App\Http\Controllers\ServicesAdminController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\EmailReminderController;
use App\Http\Controllers\TransactionAdminController;

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

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/search', [LandingController::class, 'search'])->name('landing');

Route::group(['prefix' => 'auth'], function () {
    Route::get('/login', [AuthController::class, 'renderLoginForm'])->name('login');
    Route::post('/login-action', [AuthController::class, 'login'])->name('admin.login');
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth','as' => 'admin.'], function () {
    Route::group(['prefix' => 'dashboard','as' => 'dashboard.'], function () {
        Route::get('/', [DashboardAdminController::class, 'dashboard'])->name('index');
        Route::get('/export/excel', [DashboardAdminController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [DashboardAdminController::class, 'exportPdf'])->name('export.pdf');
    });



    Route::group(['prefix' => 'orders','as' => 'orders.'], function () {
        Route::get('/', [OrderAdminController::class, 'order'])->name('index');
        Route::get('/{transaction_id}/payment', [OrderAdminController::class, 'showPaymentGateway'])->name('payment');
        Route::post('/store', [OrderAdminController::class, 'storeOrder'])->name('store');
        Route::put('/update/{order}', [OrderAdminController::class, 'updateOrder'])->name('update');
        Route::delete('/store/{order}', [OrderAdminController::class, 'deleteOrder'])->name('delete');
    });

    Route::group(['prefix' => 'parfumes','as' => 'parfumes.'], function () {
        Route::get('/', [ParfumeAdminController::class, 'parfume'])->name('index');
        Route::post('/store', [ParfumeAdminController::class, 'storeParfume'])->name('store');
        Route::put('/update/{parfume}', [ParfumeAdminController::class, 'updateParfume'])->name('update');
        Route::delete('/delete/{parfume}', [ParfumeAdminController::class, 'deleteParfume'])->name('delete');

        Route::post('/import', [ParfumeAdminController::class, 'importParfume'])->name('import');
        Route::get('/export', [ParfumeAdminController::class, 'exportParfume'])->name('export');
    });

    Route::group(['prefix' => 'customers','as' => 'customers.'], function () {
        Route::get('/', [CustomerAdminController::class, 'customers'])->name('index');
        Route::post('/store', [CustomerAdminController::class, 'storeCustomer'])->name('store');
        Route::put('/update/{customer}', [CustomerAdminController::class, 'updateCustomer'])->name('update');
        Route::delete('/delete/{customer}', [CustomerAdminController::class, 'deleteCustomer'])->name('delete');

        Route::get('/search', [CustomerAdminController::class, 'search'])->name('search');
        Route::post('/import', [CustomerAdminController::class, 'importCustomer'])->name('import');
        Route::get('/export', [CustomerAdminController::class, 'exportCustomer'])->name('export');
    });

    Route::group(['prefix' => 'services','as' => 'services.'], function () {
        Route::get('/', [ServicesAdminController::class, 'services'])->name('index');
        Route::post('/store', [ServicesAdminController::class, 'storeService'])->name('store');
        Route::put('/update/{service}', [ServicesAdminController::class, 'updateService'])->name('update');
        Route::delete('/delete/{service}', [ServicesAdminController::class, 'deleteService'])->name('delete');

        Route::post('/import', [ServicesAdminController::class, 'importService'])->name('import');
        Route::get('/export', [ServicesAdminController::class, 'exportService'])->name('export');
    });

    Route::group(['prefix' => 'transactions','as' => 'transactions.'], function () {
        Route::get('/', [TransactionAdminController::class, 'transactions'])->name('index');
        Route::post('/{order}/retry', [OrderAdminController::class, 'retryPayment'])->name('retry');

        Route::post('/send-email/{transaction}', [EmailReminderController::class, 'sendReminder'])->name('send-email');
        Route::post('/send-bulk-email', [EmailReminderController::class, 'bulkSendEmail'])->name('sendBulk-email');


        Route::get('/search', [TransactionAdminController::class, 'search'])->name('search');

    });

});

// Route::get('/test-email', function () {
//     Mail::to('user@example.com')->send(
//         new LaundryStatusMail('Rina', 'LDR-045', 'Selesai')
//     );

//     return 'Email reminder dikirim!';
// });
