<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\ResepController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\AksesRoleController;
use App\Http\Controllers\bahanBakuController;
use App\Http\Controllers\cashFlowController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PermissionController;
use App\Models\Penjualan;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('profil', function () {
    return view('profile.index', compact('title', 'breadcrumbs'));
});

// Route::get('/admin', function () {
//     // notify()->success('Welcome to Laravel Notify ⚡️');
//     // smilify('success', 'You are successfully reconnected');
//     return view('dashboard.index');
// });

Route::prefix('admin/')->middleware(['auth', 'verified'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/{id}', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/foto/{id}', [ProfileController::class, 'updateFoto'])->name('profile.foto');
    Route::delete('/profile/{id}', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard')->middleware('role:admin|karyawan|keuangan');

    Route::get('/role', [RoleController::class, 'index'])->name('role.index')->middleware('role:admin|karyawan');
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

    Route::get('bahan-baku', [bahanBakuController::class, 'index'])->name('bahan-baku.index');
    Route::post('bahan-baku', [bahanBakuController::class, 'store'])->name('bahan-baku.store');
    Route::get('bahan-baku/{id}/edit', [bahanBakuController::class, 'edit'])->name('bahan-baku.edit');
    Route::put('bahan-baku/{id}', [bahanBakuController::class, 'update'])->name('bahan-baku.update');
    Route::delete('bahan-baku/{id}', [bahanBakuController::class, 'destroy'])->name('bahan-baku.destroy');

    Route::post('/satuan', [RoleController::class, 'store'])->name('satuan.store');

    Route::get('/produk', [ProductsController::class, 'index'])->name('produk.index');
    Route::get('/produk/create', [ProductsController::class, 'create'])->name('produk.create');
    Route::post('/produk', [ProductsController::class, 'store'])->name('produk.store');
    Route::get('/produk/{id}', [ProductsController::class, 'show'])->name('produk.show');
    Route::get('/produk/{id}/edit', [ProductsController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{id}', [ProductsController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{id}', [ProductsController::class, 'destroy'])->name('produk.destroy');

    Route::post('/merek', [ProductsController::class, 'merekStore'])->name('merek.store');
    Route::post('/kategori', [ProductsController::class, 'kategoriStore'])->name('kategori.store');

    Route::get('/resep', [ResepController::class, 'index'])->name('resep.index');
    Route::post('/resep', [ResepController::class, 'store'])->name('resep.store');
    Route::put('/resep/{id}', [ResepController::class, 'update'])->name('resep.update');
    Route::delete('/resep/{id}', [ResepController::class, 'destroy'])->name('resep.destroy');

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

    Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
    Route::post('/supplier', [SupplierController::class, 'store'])->name('supplier.store');
    Route::put('/supplier/{id}', [SupplierController::class, 'update'])->name('supplier.update');
    Route::delete('/supplier/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');

    Route::get('pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
    Route::get('pembelian/create', [PembelianController::class, 'create'])->name('pembelian.create');
    Route::post('pembelian', [PembelianController::class, 'store'])->name('pembelian.store');
    Route::get('pembelian/{nobukti}/edit', [PembelianController::class, 'edit'])->name('pembelian.edit');
    Route::put('pembelian/{id}', [PembelianController::class, 'update'])->name('pembelian.update');
    // Route::put('pembelian/status/{kode}', [PembelianController::class, 'updateStatus'])->name('pembelian.updateStatus');
    Route::delete('pembelian/{id}', [PembelianController::class, 'destroy'])->name('pembelian.destroy');
    Route::get('laporan-pembelian', [PembelianController::class, 'laporanPembelian'])->name('laporan-pembelian');
    Route::get('/laporan-pembelian/pdf', [PembelianController::class, 'exportPDF'])->name('laporan-pembelian.pdf');

    Route::get('penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('penjualan/create', [PenjualanController::class, 'create'])->name('penjualan.create');
    Route::post('penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');
    Route::get('penjualan/{nobukti}/edit', [PenjualanController::class, 'edit'])->name('penjualan.edit');
    Route::get('laporan-penjualan', [PenjualanController::class, 'laporanPenjualan'])->name('laporan-penjualan');
    Route::get('/laporan-penjualan/pdf', [PenjualanController::class, 'exportPDF'])->name('laporan-penjualan.pdf');
    // Route::get('/penjualan/{id}/struk',  [PenjualanController::class, 'showStruk'])->name('penjualan.struk');

    Route::get('/kas', [cashFlowController::class, 'index'])->name('kas.index');
    Route::get('/kas/create', [cashFlowController::class, 'create'])->name('kas.create');
    Route::post('/kas', [cashFlowController::class, 'store'])->name('kas.store');

    Route::get('/discount', [DiscountController::class, 'index'])->name('discount.index');

    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');

    Route::get('laporan-stok', [LaporanController::class, 'laporanStok'])->name('laporan-stok');
    Route::get('/laporan-stok/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan-stok.exportPdf');
    Route::get('/laporan-stok/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan-stok.exportExcel');

    Route::get('/laporan/barang-masuk/cetak', [LaporanController::class, 'cetakPDF'])->name('laporan.barang-masuk.pdf');

    Route::get('/notifications/mark-as-read', function () {
        DB::table('notifications')->update(['is_read' => true]);
        return redirect()->back();
    })->name('notifications.markAsRead');
});

Route::get('/absen/', [MitraController::class, 'index'])->name('absen.index');

Route::get('/form-penjualan/', [PenjualanController::class, 'formPenjualan'])->name('form-penjualan');

Route::get('/mitra/create', [MitraController::class, 'create'])->name('mitra.create');
Route::post('/mitra/', [MitraController::class, 'store'])->name('mitra.store');
