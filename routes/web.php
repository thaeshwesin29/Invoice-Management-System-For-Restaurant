<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Backend\Admin\OrderController;
use App\Http\Controllers\Backend\Admin\StaffController;
use App\Http\Controllers\Backend\Admin\InvoiceController;
use App\Http\Controllers\Backend\Admin\ProductController;
use App\Http\Controllers\Backend\Admin\CategoryController;
use App\Http\Controllers\Backend\Admin\AdminUserController;
use App\Http\Controllers\Backend\Admin\DashboardController;
use App\Http\Controllers\Backend\Admin\Auth\PasswordController;
use App\Http\Controllers\Backend\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Backend\Admin\Auth\VerifyEmailController;
use App\Http\Controllers\Backend\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\Backend\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Backend\Admin\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Backend\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Backend\Admin\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Backend\Admin\Auth\EmailVerificationNotificationController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('backend.index');
//     // return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__.'/auth.php';


Route::prefix('/admin')->name('admin.')->group(function () {

    Route::middleware('guest:web')->group(function () {
        Route::get('register', [RegisteredUserController::class, 'create'])
                    ->name('register');

        Route::post('register', [RegisteredUserController::class, 'store']);

        Route::get('login', [\App\Http\Controllers\Backend\Admin\Auth\AuthenticatedSessionController::class, 'create'])
                    ->name('login');

        Route::post('login', [\App\Http\Controllers\Backend\Admin\Auth\AuthenticatedSessionController::class, 'store']);

        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                    ->name('password.request');

        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                    ->name('password.email');

        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                    ->name('password.reset');

        Route::post('reset-password', [NewPasswordController::class, 'store'])
                    ->name('password.store');
    });

    Route::middleware('auth')->group(function () {
        Route::get('verify-email', EmailVerificationPromptController::class)
                    ->name('verification.notice');

        Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                    ->middleware(['signed', 'throttle:6,1'])
                    ->name('verification.verify');

        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                    ->middleware('throttle:6,1')
                    ->name('verification.send');

        Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                    ->name('password.confirm');

        Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

        Route::put('password', [PasswordController::class, 'update'])->name('password.update');

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                    ->name('logout');

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/get-order-chart-data', [DashboardController::class, 'getOrderChartData'])->name('get-order-chart-data');

        Route::resource('admin-user', AdminUserController::class);
        Route::get('admin-user/{admin_user}/change-password', [AdminUserController::class, 'changePassword'])->name('admin-user.change-password');
        Route::patch('admin-user/{admin_user}/update-password', [AdminUserController::class, 'updatePassword'])->name('admin-user.update-password');

        Route::resource('staff', StaffController::class);
        Route::get('staff/{staff}/change-password', [StaffController::class, 'changePassword'])->name('staff.change-password');
        Route::patch('staff/{staff}/update-password', [StaffController::class, 'updatePassword'])->name('staff.update-password');

        Route::resource('category', CategoryController::class);

        Route::resource('product', ProductController::class);

        Route::resource('order', OrderController::class);
        Route::get('order/add-to-cart/form', [OrderController::class, 'addToCartForm'])->name('order.add-to-cart.form');
        Route::get('order/get-product-list/data', [OrderController::class, 'getProductList']);
        Route::get('order/get-add-to-cart-order/data', [OrderController::class, 'getAddToCartOrder']);
        Route::post('order/add-to-cart-order-items', [OrderController::class, 'addToCartOrderItems']);
        Route::post('order/{order}/confirm', [OrderController::class, 'orderConfirm'])->name('order.confirm');
        Route::post('order/{order}/cancel', [OrderController::class, 'orderCancel'])->name('order.cancel');
        Route::post('order/{order}/generate-invoice', [OrderController::class, 'generateInvoice'])->name('order.generate-invoice');

        Route::resource('invoice', InvoiceController::class);
        Route::get('invoice/{invoice}/download', [InvoiceController::class, 'downloadInvoice'])->name('invoice.download');
    });
});

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'mm'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('language.switch');
