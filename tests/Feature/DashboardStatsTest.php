<?php

namespace Tests\Feature;

use App\Models\KategoriKeuangan;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\ViewSaldo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DashboardStatsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // Seed Roles
        Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'keuangan', 'guard_name' => 'web']);
    }

    /** @test */
    public function dashboard_shows_correct_financial_summaries()
    {
        // 1. Setup Categories using raw attributes to avoid redundancy if factory missing
        $catPemasukan = KategoriKeuangan::create([
            'nama' => 'Penjualan Tunai',
            'jenis' => 'pemasukan',
            'kode' => 'INC01'
        ]);

        $catPengeluaran = KategoriKeuangan::create([
            'nama' => 'Biaya Operasional',
            'jenis' => 'pengeluaran',
            'kode' => 'EXP01'
        ]);

        // 2. Create Transactions
        // Pemasukan: 1,000,000
        Transaksi::create([
            'nobukti' => 'TRX-INC-001',
            'tanggal' => now(),
            'jumlah' => 1000000,
            'id_kategori_keuangan' => $catPemasukan->id,
            'posisi_kas' => 'Tunai',
            'status' => 1,
            'deskripsi' => 'Test Income'
        ]);

        // Pengeluaran: 300,000
        Transaksi::create([
            'nobukti' => 'TRX-EXP-001',
            'tanggal' => now(),
            'jumlah' => 300000,
            'id_kategori_keuangan' => $catPengeluaran->id,
            'posisi_kas' => 'Tunai',
            'status' => 1,
            'deskripsi' => 'Test Expense'
        ]);

        // Inactive Transaction (should be ignored)
        Transaksi::create([
            'nobukti' => 'TRX-INC-002',
            'tanggal' => now(),
            'jumlah' => 500000,
            'id_kategori_keuangan' => $catPemasukan->id,
            'posisi_kas' => 'Tunai',
            'status' => 0, // Pending/Draft
            'deskripsi' => 'Pending Income'
        ]);

        // 3. Verify ViewSaldo Calculation directly first
        // Note: SQLite View behavior depends on how migration created it. 
        // If ViewSaldo relies on 'view_saldo_dana' view:
        $saldo = ViewSaldo::first();

        // If migration failed to create view in SQLite, this might be null or error.
        // But assuming migration worked:
        $this->assertNotNull($saldo, 'ViewSaldo should return a record');

        // Pemasukan = 1,000,000
        $this->assertEquals(1000000, $saldo->total_pemasukan);
        // Pengeluaran = 300,000
        $this->assertEquals(300000, $saldo->total_pengeluaran);
        // Saldo = 700,000
        $this->assertEquals(700000, $saldo->saldo_current);

        // 4. Check Owner Dashboard
        $owner = User::factory()->create();
        $owner->assignRole('owner');

        $response = $this->actingAs($owner)->get(route('dashboard.owner'));

        $response->assertStatus(200);

        // Assert numbers are present formatted
        // 1.000.000
        $response->assertSee('1.000.000');
        // 300.000
        $response->assertSee('300.000');
        // 700.000
        $response->assertSee('700.000');
    }
}
