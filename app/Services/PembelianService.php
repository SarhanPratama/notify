<?php

namespace App\Services;

use App\Models\mutasi;
use App\Models\Pembelian;
use App\Models\SumberDana;
use Illuminate\Support\Facades\DB;

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
            $nobukti = 'B' . now()->format('YmdHis') . rand(1000, 9999);

            // Hitung total
            $total = 0;
            foreach ($data['bahanBaku'] as $index => $idBahanBaku) {
                $total += $data['quantity'][$index] * $data['harga'][$index];
            }

            $pembelian = Pembelian::create([
                'nobukti' => $nobukti,
                'total' => $total,
                'tanggal' => $data['tanggal'],
                'status' => 1,
                'catatan' => $data['catatan'] ?? null,
                'id_supplier' => $data['id_supplier'] ?? null,
            ]);

            // \Log::info('Pembelian berhasil dibuat', $pembelian->toArray());

            foreach ($data['bahanBaku'] as $index => $idBahanBaku) {
                // \Log::info('Insert mutasi', $index);
                $pembelian->mutasi()->create([
                    'id_bahan_baku' => $idBahanBaku,
                    'quantity' => $data['quantity'][$index],
                    'harga' => $data['harga'][$index],
                    'sub_total' => $data['quantity'][$index] * $data['harga'][$index],
                    'jenis_transaksi' => 'M',
                    'status' => 1,
                ]);
            }


            $sumberDana = SumberDana::find($data['id_sumber_dana']);

            $pembelian->Transaksi()->create([
                'id_sumber_dana' => $sumberDana->id,
                'tanggal' => $pembelian->tanggal,
                'tipe' => 'credit',
                'jumlah' => $total,
                'deskripsi' => 'Pembelian bahan baku #' . $pembelian->nobukti,
            ]);

            $sumberDana->decrement('saldo_current', $total);

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
        // dd($nobukti, Pembelian::all()->pluck('nobukti'));

       $pembelian = Pembelian::withTrashed()->with('mutasi', 'transaksi')->where('nobukti', $nobukti)->firstOrFail();

        // dd($pembelian);
        $oldTotal = $pembelian->total;
        $oldSumberDanaId = optional($pembelian->transaksi->first())->id_sumber_dana;
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
        $existingMutasi = $pembelian->mutasi->keyBy('id_bahan_baku'); // Asumsikan tidak ada duplikat bahan baku

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
                    'status' => 1,
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
                    'status' => 1,
                ]);
                $processedIds[] = $newMutasi->id;
            }
        }

        // Soft delete mutasi yang tidak digunakan lagi
        $pembelian->mutasi()->whereNotIn('id', $processedIds)->forceDelete();

        // Update transaksi
        $transaksi = $pembelian->transaksi->first();

        // Kembalikan saldo lama
        if ($transaksi && $oldSumberDanaId) {
            SumberDana::where('id', $oldSumberDanaId)->increment('saldo_current', $oldTotal);
        }

        $sumberDana = SumberDana::findOrFail($data['id_sumber_dana']);

        if ($transaksi) {
            $transaksi->update([
                'id_sumber_dana' => $sumberDana->id,
                'tanggal' => $pembelian->tanggal,
                'jumlah' => $total,
                'deskripsi' => 'Update Pembelian bahan baku #' . $pembelian->nobukti,
            ]);
        } else {
            $pembelian->transaksi()->create([
                'id_sumber_dana' => $sumberDana->id,
                'tanggal' => $pembelian->tanggal,
                'tipe' => 'credit',
                'jumlah' => $total,
                'deskripsi' => 'Pembelian bahan baku #' . $pembelian->nobukti,
            ]);
        }

        // Kurangi saldo sumber dana baru
        $sumberDana->decrement('saldo_current', $total);

        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}


}
