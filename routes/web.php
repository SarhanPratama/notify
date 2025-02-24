<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\AksesRoleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionController;

Route::get('/', function () {
    // notify()->success('Welcome to Laravel Notify ⚡️');
    // smilify('success', 'You are successfully reconnected');
    return view('welcome');
});

// Route::get('/admin', function () {
//     // notify()->success('Welcome to Laravel Notify ⚡️');
//     // smilify('success', 'You are successfully reconnected');
//     return view('dashboard.index');
// });

Route::prefix('admin/')->middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard')->middleware('role:admin|karyawan');

    Route::get('/role', [RoleController::class, 'index'])->name('role.index')->middleware('role:admin');
    Route::post('/role', [RoleController::class, 'store'])->name('role.store');
    Route::put('/role/{id}', [RoleController::class, 'update'])->name('role.update');
    Route::delete('/role/{id}', [RoleController::class, 'destroy'])->name('role.destroy');


    Route::get('/permission', [PermissionController::class, 'index'])->name('permission.index');
    Route::post('/permission', [PermissionController::class, 'store'])->name('permission.store');
    Route::put('/permission/{id}', [PermissionController::class, 'update'])->name('permission.update');
    Route::delete('/permission/{id}', [PermissionController::class, 'destroy'])->name('permission.destroy');

    Route::get('akses-role', [AksesRoleController::class, 'index'])->name('akses-role.index');
    Route::get('akses-role/create', [AksesRoleController::class, 'create'])->name('akses-role.create');
    Route::post('akses-role/{role}/update', [AksesRoleController::class, 'update'])->name('akses-role.update');

    // Route::post('akses-role', [AksesRoleController::class, 'update'])->name('akses-role.update');
    // Route::get('akses-role/{id}/edit', [AksesRoleController::class, 'edit'])->name('akses-role.edit');


    Route::get('/produk', [ProductsController::class, 'index'])->name('produk.index');

    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UsersController::class, 'create'])->name('users.create');
    Route::post('/users', [UsersController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [UsersController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UsersController::class, 'destroy'])->name('users.destroy');

    Route::get('/cabang', [CabangController::class, 'index'])->name('cabang.index');
    Route::POST('/cabang', [CabangController::class, 'store'])->name('cabang.store');
    Route::get('/cabang/{id}/edit', [CabangController::class, 'edit'])->name('cabang.edit');
    Route::put('/cabang/{id}', [CabangController::class, 'update'])->name('cabang.update');
    Route::delete('/cabang/{id}', [CabangController::class, 'destroy'])->name('cabang.destroy');


Route::get('/notifications/mark-as-read', function () {
    DB::table('notifications')->update(['is_read' => true]);
    return redirect()->back();
})->name('notifications.markAsRead');

});

