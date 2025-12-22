<?php

use App\Models\Penjualan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PiutangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KategoriKeuanganController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\AksesRoleController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\OutletOrderController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\UpprovePembelianController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::prefix('admin/')->middleware(['auth', 'verified'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/{id}', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/foto/{id}', [ProfileController::class, 'updateFoto'])->name('profile.foto');
    Route::delete('/profile/{id}', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard')->middleware('role:owner|gudang|keuangan');

    Route::get('/dashboard/admin-gudang', [DashboardController::class, 'adminGudang'])
        ->name('dashboard.admin-gudang')
        ->middleware('role:gudang');

    Route::get('/dashboard/manajer-keuangan', [DashboardController::class, 'stafKeuangan'])
        ->name('dashboard.manajer-keuangan')
        ->middleware('role:keuangan');

    Route::get('/dashboard/owner', [DashboardController::class, 'owner'])
        ->name('dashboard.owner')
        ->middleware('role:owner');

    // pengaturan user dan role
    Route::resource('users', UsersController::class)->middleware('permission:users');
    Route::get('akses-role', [AksesRoleController::class, 'index'])->name('akses-role.index')->middleware('permission:akses-role');
    Route::post('akses-role/{akses_role}', [AksesRoleController::class, 'update'])->name('akses-role.update')->middleware('permission:akses-role');

    // route data master
    Route::resource('kategori', KategoriController::class)->except(['show', 'edit', 'create'])->middleware('permission:kategori');
    Route::resource('kategori-keuangan', KategoriKeuanganController::class)->except(['show', 'edit', 'create'])->middleware('permission:kas');
    Route::resource('bahan-baku', BahanBakuController::class)->except(['show', 'create', 'edit'])->middleware('permission:bahan-baku');
    Route::resource('supplier', SupplierController::class)->except(['show', 'create', 'edit'])->middleware('permission:supplier');
    Route::resource('outlet', OutletController::class)->except(['create', 'edit'])->middleware('permission:outlet');
    Route::post('/outlet/{id}/barcode/generate', [OutletController::class, 'generateBarcode'])->name('outlet.barcode.generate')->middleware('permission:outlet');
    Route::get('/outlet/{id}/barcode/download', [OutletController::class, 'downloadBarcode'])->name('outlet.barcode.download')->middleware('permission:outlet');
    Route::post('/outlet/{id}/barcode/regenerate', [OutletController::class, 'regenerateBarcode'])->name('outlet.barcode.regenerate')->middleware('permission:outlet');
    Route::post('/outlet/{id}/barcode/toggle', [OutletController::class, 'toggleBarcodeStatus'])->name('outlet.barcode.toggle')->middleware('permission:outlet');

    //route gudang
    Route::resource('pembelian', PembelianController::class)->middleware('permission:pembelian');
    Route::delete('pembelian/{nobukti}/cancel', [PembelianController::class, 'cancel'])->name('pembelian.cancel')->middleware('permission:pembelian');
    Route::put('pembelian/restore/{id}', [PembelianController::class, 'restore'])->name('pembelian.restore')->middleware('permission:pembelian');

    // Route session untuk create pembelian
    Route::post('pembelian/cart/add', [PembelianController::class, 'addToCartCreate'])->name('pembelian.cart.create.add')->middleware('permission:pembelian');
    Route::delete('pembelian/cart/{index}', [PembelianController::class, 'removeFromCartCreate'])->name('pembelian.cart.create.remove')->middleware('permission:pembelian');
    Route::delete('pembelian/cart/clear', [PembelianController::class, 'clearCartCreate'])->name('pembelian.cart.create.clear')->middleware('permission:pembelian');

    // Route session untuk edit pembelian
    Route::post('pembelian/{nobukti}/cart/add', [PembelianController::class, 'addToCart'])->name('pembelian.cart.add')->middleware('permission:pembelian');
    Route::delete('pembelian/{nobukti}/cart/{index}', [PembelianController::class, 'removeFromCart'])->name('pembelian.cart.remove')->middleware('permission:pembelian');
    Route::delete('pembelian/{nobukti}/cart', [PembelianController::class, 'clearCart'])->name('pembelian.cart.clear')->middleware('permission:pembelian');
    // Route::delete('/pembelian/{id}/force', [PembelianController::class, 'forceDelete'])->name('pembelian.forceDelete')->middleware('permission:pembelian');
    Route::resource('penjualan', PenjualanController::class)->middleware('permission:penjualan');
    Route::put('penjualan/restore/{nobukti}', [PenjualanController::class, 'restore'])->name('penjualan.restore')->middleware('permission:penjualan');

    // Route session untuk edit penjualan
    Route::post('penjualan/{nobukti}/cart/add', [PenjualanController::class, 'addToCart'])->name('penjualan.cart.add')->middleware('permission:penjualan');
    Route::delete('penjualan/{nobukti}/cart/{index}', [PenjualanController::class, 'removeFromCart'])->name('penjualan.cart.remove')->middleware('permission:penjualan');
    Route::delete('penjualan/{nobukti}/cart', [PenjualanController::class, 'clearCart'])->name('penjualan.cart.clear')->middleware('permission:penjualan');

    // Route::delete('/penjualan/{id}/force', [PenjualanController::class, 'forceDelete'])->name('penjualan.forceDelete')->middleware('permission:penjualan');
    Route::get('pesanan-admin', [AdminOrderController::class, 'index'])->name('admin.pesanan.index')->middleware('role:gudang|keuangan|owner');
    Route::get('pesanan-admin/{id}', [AdminOrderController::class, 'show'])->name('admin.pesanan.show')->middleware('role:gudang|keuangan|owner');
    Route::post('pesanan-admin/{id}/approve', [AdminOrderController::class, 'approve'])->name('admin.pesanan.approve')->middleware('role:gudang|keuangan|owner');
    Route::post('pesanan-admin/{id}/reject', [AdminOrderController::class, 'reject'])->name('admin.pesanan.reject')->middleware('role:gudang|keuangan|owner');

    // route keuangan
    Route::resource('pemasukan', PemasukanController::class)->middleware('permission:pemasukan');
    Route::post('pemasukan/{id}/approve-penjualan', [PemasukanController::class, 'approvePenjualan'])->name('pemasukan.approve-penjualan')->middleware('permission:pemasukan');
    Route::post('pemasukan/{id}/reject-penjualan', [PemasukanController::class, 'rejectPenjualan'])->name('pemasukan.reject-penjualan')->middleware('permission:pemasukan');

    Route::resource('pengeluaran', PengeluaranController::class)->middleware('permission:pengeluaran');
    Route::post('pengeluaran/{id}/approve-pembelian', [PengeluaranController::class, 'approvePembelian'])->name('pengeluaran.approve-pembelian')->middleware('permission:pengeluaran');
    Route::post('pengeluaran/{id}/reject-pembelian', [PengeluaranController::class, 'rejectPembelian'])->name('pengeluaran.reject-pembelian')->middleware('permission:pengeluaran');

    Route::resource('piutang', PiutangController::class)->middleware('permission:piutang');
    Route::post('piutang/{nobukti}/bayar', [PiutangController::class, 'bayar'])->name('piutang.bayar')->middleware('permission:piutang');
    Route::get('piutang/{nobukti}/print', [PiutangController::class, 'printInvoice'])->name('piutang.print')->middleware('permission:piutang');
    Route::resource('transaksi', TransaksiController::class)->middleware('permission:transaksi');

    // Route::get('laporan-pembelian', [PembelianController::class, 'laporanPembelian'])->name('laporan-pembelian');
    // Route::get('/laporan-pembelian/pdf', [PembelianController::class, 'exportPDF'])->name('laporan-pembelian.pdf');

    // Route::get('laporan-penjualan', [PenjualanController::class, 'laporanPenjualan'])->name('laporan-penjualan');
    // Route::get('/laporan-penjualan/pdf', [PenjualanController::class, 'exportPDF'])->name('laporan-penjualan.pdf');
    
    //Route laporan
    Route::get('laporan/stok', [LaporanController::class, 'laporanStok'])->name('laporan-stok')->middleware('permission:laporan');
    Route::get('/laporan-stok/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan-stok.exportExcel')->middleware('permission:laporan');

    Route::get('/laporan/kartu-stok', [LaporanController::class, 'laporanKartuStok'])->name('laporan.kartu-stok')->middleware('permission:laporan');
    Route::get('/laporan/kartu-stok/export', [LaporanController::class, 'exportKartuStok'])->name('laporan.kartu-stok.export')->middleware('permission:laporan');

    Route::get('/laporan/rekap-transaksi', [LaporanController::class, 'laporanRekapTransaksi'])
        ->name('laporan.rekap-transaksi')->middleware('permission:laporan');
    Route::get('/laporan/rekap-transaksi/export', [LaporanController::class, 'exportRekapTransaksi'])
        ->name('laporan.rekap-transaksi.export')->middleware('permission:laporan');


    // Route::get('/laporan/barang-masuk/cetak', [LaporanController::class, 'cetakPDF'])->name('laporan.barang-masuk.pdf');
});

// Outlet Routes (No Login Required)
Route::group(['prefix' => 'outlet'], function () {
    Route::get('/{token}/belanja', [OutletOrderController::class, 'belanja'])->name('outlet.belanja');
    Route::post('/{token}/belanja', [OutletOrderController::class, 'storeOrder'])->name('outlet.order.store');

    // Session cart routes for outlet belanja
    Route::post('/{token}/cart/add', [OutletOrderController::class, 'addToCart'])->name('outlet.cart.add');
    Route::delete('/{token}/cart/{index}', [OutletOrderController::class, 'removeFromCart'])->name('outlet.cart.remove');
    Route::delete('/{token}/cart', [OutletOrderController::class, 'clearCart'])->name('outlet.cart.clear');
    Route::post('/{token}/cart/update/{index}', [OutletOrderController::class, 'updateCartItem'])->name('outlet.cart.update');

    Route::get('/{token}/pesanan/', [OutletOrderController::class, 'pesanan'])->name('outlet.pesanan');
    Route::get('/{token}/kasbon/', [OutletOrderController::class, 'kasbon'])->name('outlet.kasbon');
    Route::get('/{token}/kasbon/{piutang}', [OutletOrderController::class, 'kasbonDetail'])->name('outlet.kasbon.detail');
    Route::get('/{token}/kasbon/{id}/download', [OutletOrderController::class, 'downloadInvoice'])->name('outlet.kasbon.download');
    Route::get('/{token}/pesanan/{orderId}', [OutletOrderController::class, 'detailPesanan'])->name('outlet.pesanan.detail');
});
