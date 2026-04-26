<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Outlet;
use App\Models\BahanBaku;
use App\Models\Supplier;
use App\Models\KategoriKeuangan;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\Piutang;
use App\Models\mutasi;
use App\Models\Transaksi;
use Illuminate\Support\Arr;

class DemoDataSeeder extends Seeder
{
    protected $stockLevels = [];

    public function run()
    {
        DB::transaction(function () {
            $this->createMasterDataIfNotExists();
            $this->initializeStockLevels();

            // Periode: November 2025 - Januari 2026
            $startDate = Carbon::create(2025, 11, 1);
            $endDate = Carbon::create(2026, 1, 31);

            $currentDate = $startDate->copy();

            while ($currentDate <= $endDate) {
                $this->command->info("Simulating date: " . $currentDate->format('Y-m-d'));
                $this->simulateDay($currentDate);
                $currentDate->addDay();
            }
        });
    }

    private function initializeStockLevels()
    {
        $bahanBaku = BahanBaku::all();
        foreach ($bahanBaku as $item) {
            $this->stockLevels[$item->id] = $item->stok_awal ?? 0;
        }
    }

    private function simulateDay(Carbon $date)
    {
        $suppliers = Supplier::all();
        $outlets = Outlet::all();
        $bahanBakuList = BahanBaku::all();

        if ($suppliers->isEmpty() || $bahanBakuList->isEmpty() || $outlets->isEmpty())
            return;

        // 1. Chance to Purchase (Restock)
        if (rand(1, 100) <= 25) {
            $this->createPurchase($date, $suppliers, $bahanBakuList);
        }

        // 2. Check for Low Stock & Force Restock
        foreach ($bahanBakuList as $bb) {
            if (($this->stockLevels[$bb->id] ?? 0) < 10) {
                $this->createPurchase($date, $suppliers, $bahanBakuList, $bb->id);
            }
        }

        // 3. Chance to Sell
        $salesCount = rand(0, 2);
        for ($i = 0; $i < $salesCount; $i++) {
            $this->createSale($date, $outlets, $bahanBakuList);
        }

        // 4. Manual Income/Expense
        if (rand(1, 100) <= 15)
            $this->createManualExpense($date);
        if (rand(1, 100) <= 5)
            $this->createManualIncome($date);
    }

    private function createSale(Carbon $date, $outlets, $bahanBakuList)
    {
        $outlet = $outlets->random();
        // Using existing nobukti logic (assuming model doesn't override or we use model one)
        // If Model generates it, we might need to rely on that. But Seeder usually sets it manually for control.
        $nobukti = 'PO-' . $date->format('Ymd') . '-' . rand(1000, 9999);

        $statusGudang = 'approved';

        // 10% chance pending gudang
        if (rand(1, 100) > 90)
            $statusGudang = 'pending';

        // Status Keuangan follows Gudang approval
        // If Gudang approved, Keuangan reviews.
        $statusKeuangan = ($statusGudang === 'approved') ? 'approved' : 'pending';

        // Small chance Keuangan rejects even if Gudang Approved
        if ($statusGudang === 'approved' && rand(1, 100) > 95)
            $statusKeuangan = 'rejected';

        // IMPORTANT: In this system, ALL approved sales go to Piutang first.
        // There is no "Direct Cash" that bypasses Piutang.
        // Status Pembayaran tracks the PIUTANG status.
        $statusPembayaran = ($statusKeuangan === 'approved') ? 'piutang' : 'piutang'; // Default piutang

        // Select items
        $possibleItems = $bahanBakuList->filter(function ($item) {
            return ($this->stockLevels[$item->id] ?? 0) > 5;
        });

        if ($possibleItems->isEmpty())
            return;

        $itemsToSell = $possibleItems->random(min($possibleItems->count(), rand(1, 5)));
        $totalPenjualan = 0;
        $soldItemsData = [];

        foreach ($itemsToSell as $item) {
            $maxQty = $this->stockLevels[$item->id];
            $qty = rand(1, min(15, $maxQty));
            if ($qty > $maxQty)
                $qty = $maxQty;
            if ($qty <= 0)
                continue;

            $price = $item->harga * 2.5;
            $subtotal = $qty * $price;
            $totalPenjualan += $subtotal;

            $soldItemsData[] = [
                'item' => $item,
                'qty' => $qty,
                'price' => $price,
                'subtotal' => $subtotal
            ];
        }

        if (empty($soldItemsData))
            return;

        $penjualan = Penjualan::create([
            'nobukti' => $nobukti,
            'id_outlet' => $outlet->id,
            'tanggal' => $date,
            'total' => $totalPenjualan,
            'status_gudang' => $statusGudang,
            'status_keuangan' => $statusKeuangan,
            'status_pembayaran' => $statusPembayaran, // Initially piutang if approved
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        foreach ($soldItemsData as $data) {
            $mutasiStatus = ($statusGudang === 'approved') ? 1 : 0;
            Mutasi::create([
                'id_bahan_baku' => $data['item']->id,
                'nobukti' => $nobukti,
                'jenis_transaksi' => 'K',
                'quantity' => $data['qty'],
                'harga' => $data['price'],
                'sub_total' => $data['subtotal'],
                'status' => $mutasiStatus,
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            if ($statusGudang === 'approved') {
                $this->stockLevels[$data['item']->id] -= $data['qty'];
            }
        }

        // CORE LOGIC: If Keuangan Approved -> Create Piutang
        if ($statusKeuangan === 'approved') {
            $jatuhTempo = $date->copy()->addDays(14);
            $piutang = Piutang::create([
                'nobukti' => $nobukti, // Relates to Penjualan
                'jumlah_piutang' => $totalPenjualan,
                'sisa_piutang' => $totalPenjualan,
                'jatuh_tempo' => $jatuhTempo,
                'status' => 'belum_lunas',
                'created_at' => $date,
                'updated_at' => $date
            ]);

            // Now, simulate PAYMENT logic
            // Scenario A: "Tunai" (Outlet pays immediately)
            // Scenario B: "Kredit" (Outlet pays later)

            $isPaidImmediately = rand(1, 100) <= 40; // 40% Cash/Direct Transfer
            $isCicilan = !$isPaidImmediately;

            if ($isPaidImmediately) {
                // Immediate Full Payment (Same Day)
                $this->createPayment($date, $piutang, $totalPenjualan, 'Pelunasan Tunai', $outlet);
                $penjualan->update(['status_pembayaran' => 'lunas']);

            } else {
                // Credit Payment (Future)
                // 50% chance it gets paid within our simulation window
                if (rand(1, 100) <= 50) {
                    $payDate = $date->copy()->addDays(rand(1, 20)); // Pay later
                    // Pay full or partial?
                    $payAmount = (rand(1, 100) > 30) ? $totalPenjualan : ($totalPenjualan / 2);

                    $this->createPayment($payDate, $piutang, $payAmount, 'Pembayaran Kredit', $outlet);

                    if ($piutang->fresh()->sisa_piutang <= 0) {
                        $penjualan->update(['status_pembayaran' => 'lunas']);
                    }
                }
            }
        }
    }

    private function createPayment($date, $piutang, $amount, $keterangan, $outlet)
    {
        // Decrement Piutang Logic
        $piutang->decrement('sisa_piutang', $amount);
        if ($piutang->sisa_piutang <= 100) { // Tolerance
            $piutang->update(['status' => 'lunas', 'sisa_piutang' => 0]);
        }

        // 1. Create PiutangPembayaran Record
        $pembayaran = $piutang->pembayaran()->create([
            'nobukti' => $piutang->nobukti,
            'tanggal' => $date,
            'jumlah' => $amount,
            'keterangan' => $keterangan,
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        // 2. Create Transaksi (Cash Flow Analysis)
        // LINKED TO PiutangPembayaran!
        $cat = KategoriKeuangan::where('nama', 'Penjualan BB')->first();

        $pembayaran->transaksi()->create([
            'nobukti' => $piutang->nobukti, // Uses Penjualan/Piutang NoBukti for reference
            'tanggal' => $date,
            'jumlah' => $amount,
            'id_kategori_keuangan' => $cat->id,
            'posisi_kas' => 'Tunai',
            'deskripsi' => 'Pembayaran piutang outlet ' . ($outlet->nama ?? '') . ' - ' . ($outlet->penanggung_jawab ?? ''),
            'status' => 1,
            'created_at' => $date,
            'updated_at' => $date,
        ]);
    }

    private function createManualExpense(Carbon $date)
    {
        $cat = KategoriKeuangan::where('jenis', 'pengeluaran')
        ->where('nama', '!=', 'Pembelian BB')
            ->first();
        if (!$cat)
            return;

        // Controller uses: OUT-Ymd-His (we simulate His with random)
        $nobukti = 'OUT-' . $date->format('Ymd-His') . rand(10, 99);
        $amount = rand(50000, 500000);

        Transaksi::create([
            'nobukti' => $nobukti,
            'tanggal' => $date,
            'jumlah' => $amount,
            // 'tipe' => 'debit', // Implicit by category
            'posisi_kas' => Arr::random(['Tunai', 'Bank BSI']),
            'transaksiable_id' => null,
            'transaksiable_type' => null,
            'deskripsi' => 'Biaya Operasional Harian',
            'id_kategori_keuangan' => $cat->id,
            'status' => 1,
            'created_at' => $date,
            'updated_at' => $date,
        ]);
    }

    private function createManualIncome(Carbon $date)
    {
        $cat = KategoriKeuangan::where('jenis', 'pemasukan')
            ->where('nama', '!=', 'Penjualan BB')
            ->first();
        if (!$cat)
            return;

        // Controller uses: IN-Ymd-His
        $nobukti = 'IN-' . $date->format('Ymd-His') . rand(10, 99);
        $amount = rand(100000, 1000000);

        Transaksi::create([
            'nobukti' => $nobukti,
            'tanggal' => $date,
            'jumlah' => $amount,
            // 'tipe' => 'kredit', // Implicit by category
            'posisi_kas' => Arr::random(['Tunai', 'Bank BSI']),
            'transaksiable_id' => null,
            'transaksiable_type' => null,
            'deskripsi' => 'Pemasukan Lain-lain',
            'id_kategori_keuangan' => $cat->id,
            'status' => 1,
            'created_at' => $date,
            'updated_at' => $date,
        ]);
    }

    private function createPurchase(Carbon $date, $suppliers, $bahanBakuList, $forceItemId = null)
    {
        $supplier = $suppliers->random();
        $nobukti = 'PB-' . $date->format('Ymd') . '-' . rand(1000, 9999);

        $status = Arr::random(['approved', 'approved', 'approved', 'approved', 'pending']);

        if ($forceItemId) {
            $items = $bahanBakuList->where('id', $forceItemId);
            $status = 'approved';
        } else {
            $items = $bahanBakuList->random(rand(2, 4));
        }

        $totalPembelian = 0;

        $pembelian = Pembelian::create([
            'nobukti' => $nobukti,
            'id_supplier' => $supplier->id,
            'tanggal' => $date,
            'total' => 0,
            'status' => $status,
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        foreach ($items as $item) {
            $qty = rand(30, 50);
            $price = $item->harga;
            $subtotal = $qty * $price;
            $totalPembelian += $subtotal;

            $mutasiStatus = ($status === 'approved') ? 1 : 0;

            Mutasi::create([
                'id_bahan_baku' => $item->id,
                'nobukti' => $nobukti,
                'jenis_transaksi' => 'M',
                'quantity' => $qty,
                'harga' => $price,
                'sub_total' => $subtotal,
                'status' => $mutasiStatus,
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            if ($status === 'approved') {
                if (!isset($this->stockLevels[$item->id]))
                    $this->stockLevels[$item->id] = 0;
                $this->stockLevels[$item->id] += $qty;
            }
        }

        $pembelian->update(['total' => $totalPembelian]);

        if ($status === 'approved') {
            $cat = KategoriKeuangan::where('nama', 'Pembelian BB')->first();
            $pembelian->transaksi()->create([
                'nobukti' => $nobukti,
                'tanggal' => $date,
                'jumlah' => $totalPembelian,
                'id_kategori_keuangan' => $cat->id,
                'posisi_kas' => 'Tunai',
                'deskripsi' => 'Pembelian Stok ' . $supplier->nama,
                'status' => 1,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }

    private function createMasterDataIfNotExists()
    {
        KategoriKeuangan::firstOrCreate(['nama' => 'Pembelian BB'], ['jenis' => 'pengeluaran']);
        KategoriKeuangan::firstOrCreate(['nama' => 'Penjualan BB'], ['jenis' => 'pemasukan']);
        KategoriKeuangan::firstOrCreate(['nama' => 'Biaya Operasional'], ['jenis' => 'pengeluaran']);
        KategoriKeuangan::firstOrCreate(['nama' => 'Pemasukan Lain-lain'], ['jenis' => 'pemasukan']);
    }
}
