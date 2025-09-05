<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\Staff\OrderController;
use App\Http\Controllers\Frontend\Staff\StaffController;
use App\Http\Controllers\Frontend\Staff\InvoiceController;
use App\Http\Controllers\Frontend\Staff\ProductController;
use App\Http\Controllers\Frontend\Staff\CategoryController;
use App\Http\Controllers\Frontend\Staff\DashboardController;
use App\Http\Controllers\Frontend\Staff\Auth\PasswordController;
use App\Http\Controllers\Frontend\Staff\Auth\NewPasswordController;
use App\Http\Controllers\Frontend\Staff\Auth\VerifyEmailController;
use App\Http\Controllers\Frontend\Staff\Auth\RegisteredUserController;
use App\Http\Controllers\Frontend\Staff\Auth\PasswordResetLinkController;
use App\Http\Controllers\Frontend\Staff\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Frontend\Staff\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Frontend\Staff\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Frontend\Staff\Auth\EmailVerificationNotificationController;

Route::middleware('guest:staff')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [\App\Http\Controllers\Frontend\Staff\Auth\AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [\App\Http\Controllers\Frontend\Staff\Auth\AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');
});

Route::middleware('staff')->group(function () {
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

    Route::get('staff/{staff}', [StaffController::class, 'show'])->name('staff.show');

    Route::resource('category', CategoryController::class);
    Route::get('get-category-list', [CategoryController::class, 'getCategoryList']);

    Route::resource('product', ProductController::class);
    Route::get('get-product-list', [ProductController::class, 'getProductList']);
    Route::get('get-add-to-cart-order', [ProductController::class, 'getAddToCartOrder']);
    Route::post('add-to-cart-order-items', [ProductController::class, 'addToCartOrderItems']);

    Route::resource('order', OrderController::class);
    Route::post('order/{order}/confirm', [OrderController::class, 'orderConfirm'])->name('order.confirm');
    Route::post('order/{order}/cancel', [OrderController::class, 'orderCancel'])->name('order.cancel');
    Route::post('order/{order}/generate-invoice', [OrderController::class, 'generateInvoice'])->name('generate-invoice');

    Route::resource('invoice', InvoiceController::class);
    Route::get('invoice/{invoice}/download', [InvoiceController::class, 'downloadInvoice'])->name('invoice.download');
});
