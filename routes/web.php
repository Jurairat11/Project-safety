<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\NewPasswordController;

Route::get('/', function () {
    return Auth::check()
        ? redirect('/admin')   // ถ้า login แล้ว ไป admin
        : redirect('/login');  // ถ้ายังไม่ login ไป login
});

Route::get('/admin/login', function () {
    return redirect('/login');
});

Route::get('/admin/login', function () { //fake filament auth
    return redirect('/login');
})->name('filament.admin.auth.login');

Route::middleware(['auth'])->group(function () {
    Route::get('/change-password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::patch('/change-password', [PasswordController::class, 'update'])->name('password.update');
});

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');

Route::get('/dashboard/safety', function () {
        $notifications = Auth::user()?->notifications ?? collect();

        return view('dashboards.safety', [
            'notifications' => $notifications,
        ]);
    })->middleware(['auth'])->name('dashboard.safety');


require __DIR__.'/auth.php';
