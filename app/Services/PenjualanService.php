<?php

namespace App\Services;

use App\Models\BahanBaku;
use App\Models\Penjualan;
use App\Models\SumberDana;
use Illuminate\Support\Facades\DB;

class PenjualanService
{
    public function getPenjualanDetails($nobukti)
    {
        return Penjualan::with(['mutasi.bahanBaku.satuan', 'cabang', 'transaksi'])
            ->where('nobukti', $nobukti)
            ->firstOrFail();
    }

    public function tambah(array $data)
    {
        DB::beginTransaction();
        try {
            $nobukti = 'J' . now()->format('YmdHis') . rand(1000, 9999);
            $total = $this->calculateTotal($data);

            $penjualan = Penjualan::create([
                'tanggal' => $data['tanggal'],
                'nobukti' => $nobukti,
                'total' => $total,
                'catatan' => $data['catatan'],
                'id_cabang' => $data['id_cabang'],
                'metode_pembayaran' => $data['metode_pembayaran'],
                // 'status' => $data['metode_pembayaran'] === 'tunai' ? 'lunas' : 'piutang',
            ]);

            $this->syncMutasi($penjualan, $data);
            $this->tambahTransaksi($penjualan, $data, $total);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updatePenjualan($nobukti, array $data)
    {
        DB::beginTransaction();
        try {
            $penjualan = Penjualan::with('mutasi', 'transaksi', 'piutang')->where('nobukti', $nobukti)->firstOrFail();
            $oldTotal = $penjualan->total;
            $oldSumberDanaId = optional($penjualan->transaksi->first())->id_sumber_dana;

            $total = $this->calculateTotal($data);

            $penjualan->update([
                'total' => $total,
                'tanggal' => $data['tanggal'],
                'catatan' => $data['catatan'] ?? null,
                'id_cabang' => $data['id_cabang'] ?? null,
                'metode_pembayaran' => $data['metode_pembayaran'],
                // 'status' => $data['metode_pembayaran'] === 'tunai' ? 'lunas' : 'piutang',
            ]);

            $this->syncMutasi($penjualan, $data);

            $sumberDanaBaru = SumberDana::find($data['id_sumber_dana']);
            $oldSumberDana = $oldSumberDanaId ? SumberDana::find($oldSumberDanaId) : null;

            $this->updateTransaksi($penjualan, $oldSumberDana, $sumberDanaBaru, $oldTotal, $total, $data['metode_pembayaran']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function calculateTotal(array $data): float
    {
        $total = 0;
        foreach ($data['bahanBaku'] as $i => $id) {
            $total += $data['quantity'][$i] * $data['harga'][$i];
        }
        return $total;
    }

    private function syncMutasi(Penjualan $penjualan, array $data): void
    {
        $existingMutasi = $penjualan->mutasi->keyBy('id_bahan_baku');
        $processedIds = [];

        $stokBahan = DB::table('view_stok')
            ->whereIn('id_bahan_baku', $data['bahanBaku'])
            ->pluck('stok_akhir', 'id_bahan_baku');

        foreach ($data['bahanBaku'] as $i => $idBahanBaku) {
            $qty = $data['quantity'][$i];
            $harga = $data['harga'][$i];
            $subTotal = $qty * $harga;

            $stokSaatIni = $stokBahan[$idBahanBaku] ?? 0;
            if ($stokSaatIni < $qty) {
                $nama = BahanBaku::find($idBahanBaku)?->nama ?? "ID: $idBahanBaku";
                throw new \Exception("Stok tidak mencukupi untuk {$nama}. Sisa stok: {$stokSaatIni}, diminta: {$qty}");
            }

            if ($existingMutasi->has($idBahanBaku)) {
                $mutasi = $existingMutasi[$idBahanBaku];
                $mutasi->update([
                    'quantity' => $qty,
                    'harga' => $harga,
                    'sub_total' => $subTotal,
                    'jenis_transaksi' => 'K',
                    'status' => 1,
                ]);
                $processedIds[] = $mutasi->id;
            } else {
                $new = $penjualan->mutasi()->create([
                    'id_bahan_baku' => $idBahanBaku,
                    'quantity' => $qty,
                    'harga' => $harga,
                    'sub_total' => $subTotal,
                    'jenis_transaksi' => 'K',
                    'status' => 1,
                ]);
                $processedIds[] = $new->id;
            }
        }

        $penjualan->mutasi()->whereNotIn('id', $processedIds)->forceDelete();
    }

    private function tambahTransaksi(Penjualan $penjualan, array $data, float $jumlah): void
    {
        if ($data['metode_pembayaran'] === 'tunai') {
            $sumberDana = SumberDana::findOrFail($data['id_sumber_dana']);
            $penjualan->transaksi()->create([
                'id_sumber_dana' => $sumberDana->id,
                'tanggal' => $penjualan->tanggal,
                'tipe' => 'debit',
                'jumlah' => $jumlah,
                'deskripsi' => 'Penjualan bahan baku #' . $penjualan->nobukti,
            ]);
            $sumberDana->increment('saldo_current', $jumlah);
        } else {
            $penjualan->piutang()->create([
                'nobukti' => $penjualan->nobukti,
                'jumlah_piutang' => $jumlah,
                'jatuh_tempo' => now()->addDays(2),
                'status' => 'belum_lunas',
            ]);
        }
    }

    private function updateTransaksi(
        Penjualan $penjualan,
        ?SumberDana $oldSumberDana,
        ?SumberDana $newSumberDana,
        float $oldTotal,
        float $newTotal,
        string $metodePembayaran
    ): void {

        // dd('$newTotal');
        $transaksi = $penjualan->transaksi->first();
        $piutang = $penjualan->piutang;

        if ($metodePembayaran === 'tunai') {
            if (!$newSumberDana) {
                throw new \Exception("Sumber dana diperlukan untuk metode pembayaran tunai.");
            }

            if ($piutang) {
                $piutang->delete();
            }

            if ($oldSumberDana && $transaksi) {
                $oldSumberDana->decrement('saldo_current', $oldTotal);
            }

            if ($transaksi) {
                $transaksi->update([
                    'id_sumber_dana' => $newSumberDana->id,
                    'tanggal' => $penjualan->tanggal,
                    'jumlah' => $newTotal,
                    'deskripsi' => 'Update penjualan bahan baku #' . $penjualan->nobukti,
                ]);
            } else {
                $penjualan->transaksi()->create([
                    'id_sumber_dana' => $newSumberDana->id,
                    'tanggal' => $penjualan->tanggal,
                    'tipe' => 'debit',
                    'jumlah' => $newTotal,
                    'deskripsi' => 'Penjualan bahan baku #' . $penjualan->nobukti,
                ]);
            }

            $newSumberDana->increment('saldo_current', $newTotal);

        } elseif ($metodePembayaran === 'kasbon') {
            if ($transaksi) {
                if ($oldSumberDana) {
                    $oldSumberDana->decrement('saldo_current', $oldTotal);
                }
                $transaksi->delete();
            }

            if ($piutang) {
                $piutang->update([
                    'tanggal' => $penjualan->tanggal,
                    'jumlah_piutang' => $newTotal,
                    'jatuh_tempo' => now()->addDays(2),
                    'keterangan' => 'Update kasbon dari penjualan #' . $penjualan->nobukti,
                    'status' => 'belum_lunas'
                ]);
            } else {
                $penjualan->piutang()->create([
                    'tanggal' => $penjualan->tanggal,
                    'jumlah_piutang' => $newTotal,
                    'jatuh_tempo' => now()->addDays(2),
                    'keterangan' => 'Kasbon dari penjualan #' . $penjualan->nobukti,
                    'status' => 'belum_lunas'
                ]);
            }
        } else {
            throw new \InvalidArgumentException("Metode pembayaran tidak valid: {$metodePembayaran}");
        }
    }
}
