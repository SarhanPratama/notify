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

            // Eager load relations for returned model elsewhere if needed
            $pembelian->load('mutasi.bahanBaku.satuan');

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
        $pembelian = Pembelian::with(['mutasi.bahanBaku.satuan', 'transaksi'])->where('nobukti', $nobukti)->firstOrFail();

        // Cek apakah menggunakan cartItems (dari session) atau format lama
        if (isset($data['cartItems']) && !empty($data['cartItems'])) {
            // Format baru menggunakan session cart
            $cartItems = $data['cartItems'];

            // Hitung total baru dari cart
            $total = collect($cartItems)->sum('sub_total');

            // Update data pembelian utama
            $pembelian->update([
                'total' => $total,
                'catatan' => $data['catatan'] ?? null,
                'id_supplier' => $data['id_supplier'] ?? null,
            ]);

            // Hapus semua mutasi lama
            $pembelian->mutasi()->delete();

            // Buat mutasi baru dari cart
            foreach ($cartItems as $item) {
                $pembelian->mutasi()->create([
                    'id_bahan_baku' => $item['id_bahan_baku'],
                    'quantity' => $item['quantity'],
                    'harga' => $item['harga'],
                    'sub_total' => $item['sub_total'],
                    'jenis_transaksi' => 'M',
                    'nobukti' => $nobukti,
                ]);
            }
        } else {
            // Format lama menggunakan array bahanBaku, quantity, harga
            $total = 0;
            foreach ($data['bahanBaku'] as $index => $idBahanBaku) {
                $total += $data['quantity'][$index] * $data['harga'][$index];
            }

            $pembelian->update([
                'total' => $total,
                'tanggal' => $data['tanggal'] ?? now(),
                'status' => 1,
                'catatan' => $data['catatan'] ?? null,
                'id_supplier' => $data['id_supplier'] ?? null,
            ]);

            $existingMutasi = $pembelian->mutasi->keyBy('id_bahan_baku');
            $processedIds = [];

            foreach ($data['bahanBaku'] as $index => $idBahanBaku) {
                $quantity = $data['quantity'][$index];
                $harga = $data['harga'][$index];
                $subTotal = $quantity * $harga;

                if ($existingMutasi->has($idBahanBaku)) {
                    $mutasi = $existingMutasi[$idBahanBaku];
                    $mutasi->update([
                        'quantity' => $quantity,
                        'harga' => $harga,
                        'sub_total' => $subTotal,
                        'jenis_transaksi' => 'M',
                    ]);
                    $processedIds[] = $mutasi->id;
                } else {
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
        }

        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}


}
