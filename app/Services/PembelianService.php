<?php

namespace App\Services;

use App\Models\mutasi;
use App\Models\Pembelian;
use App\Models\SumberDana;
use Illuminate\Support\Facades\DB;

use function Symfony\Component\Clock\now;

class PembelianService
{
    public function getPembelianDetails($nobukti)
    {
        return Pembelian::with(['mutasi.bahanBaku.satuan', 'supplier', 'Transaksi'])
            ->where('nobukti', $nobukti)
            ->firstOrFail();
    }

    public function tambah(array $data)
    {
        DB::beginTransaction();

        try {
            $nobukti = 'PB-' . now()->format('Ymd-His') . '-' . rand(1000, 9999);


            // Hitung total
            $total = 0;
            foreach ($data['bahanBaku'] as $index => $idBahanBaku) {
                $total += $data['quantity'][$index] * $data['harga'][$index];
            }

            $pembelian = Pembelian::create([
                'nobukti' => $nobukti,
                'total' => $total,
                'tanggal' => now(),
                'status' => 'pending',
                'catatan' => $data['catatan'] ?? null,
                'id_supplier' => $data['id_supplier'] ?? null,
            ]);

            foreach ($data['bahanBaku'] as $index => $idBahanBaku) {
                $pembelian->mutasi()->create([
                    'id_bahan_baku' => $idBahanBaku,
                    'quantity' => $data['quantity'][$index],
                    'harga' => $data['harga'][$index],
                    'sub_total' => $data['quantity'][$index] * $data['harga'][$index],
                    'jenis_transaksi' => 'M',
                    'nobukti' => $nobukti,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

public function updatePembelian($nobukti, array $data)
{
    DB::beginTransaction();

    try {

       $pembelian = Pembelian::with('mutasi', 'transaksi')->where('nobukti', $nobukti)->firstOrFail();

        // dd($pembelian);
        // $oldTotal = $pembelian->total;
        // $oldSumberDanaId = optional($pembelian->transaksi->first())->id_sumber_dana;
        // dd($oldSumberDanaId);

        // Hitung total baru
        $total = 0;
        foreach ($data['bahanBaku'] as $index => $idBahanBaku) {
            $total += $data['quantity'][$index] * $data['harga'][$index];
        }

        // Update data pembelian utama
        $pembelian->update([
            'total' => $total,
            'tanggal' => $data['tanggal'],
            'status' => 1,
            'catatan' => $data['catatan'] ?? null,
            'id_supplier' => $data['id_supplier'] ?? null,
        ]);

        // Update data mutasi satu per satu
        $existingMutasi = $pembelian->mutasi->keyBy('id_bahan_baku');

        $requestBahanBakus = collect($data['bahanBaku']);
        $processedIds = [];

        foreach ($data['bahanBaku'] as $index => $idBahanBaku) {
            $quantity = $data['quantity'][$index];
            $harga = $data['harga'][$index];
            $subTotal = $quantity * $harga;

            if ($existingMutasi->has($idBahanBaku)) {
                // Update mutasi lama
                $mutasi = $existingMutasi[$idBahanBaku];
                $mutasi->update([
                    'quantity' => $quantity,
                    'harga' => $harga,
                    'sub_total' => $subTotal,
                    'jenis_transaksi' => 'M',
                ]);
                $processedIds[] = $mutasi->id;
            } else {
                // Tambah mutasi baru
                $newMutasi = $pembelian->mutasi()->create([
                    'id_bahan_baku' => $idBahanBaku,
                    'quantity' => $quantity,
                    'harga' => $harga,
                    'sub_total' => $subTotal,
                    'jenis_transaksi' => 'M',
                ]);
                $processedIds[] = $newMutasi->id;
            }
        }

        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}


}
