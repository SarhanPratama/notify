<?php

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

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard')->middleware('role:admin');



    Route::get('/role', [RoleController::class, 'index'])->name('role.index');
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
    Route::post('/users', [UsersController::class, 'store'])->name('users.store');
    Route::get('/users/create', [UsersController::class, 'create'])->name('users.create');

    Route::get('/cabang', [CabangController::class, 'index'])->name('cabang.index');
    Route::POST('/cabang', [CabangController::class, 'store'])->name('cabang.store');
    Route::get('/cabang/{id}/edit', [CabangController::class, 'edit'])->name('cabang.edit');
    Route::put('/cabang/{id}', [CabangController::class, 'update'])->name('cabang.update');
    Route::delete('/cabang/{id}', [CabangController::class, 'destroy'])->name('cabang.destroy');
});
