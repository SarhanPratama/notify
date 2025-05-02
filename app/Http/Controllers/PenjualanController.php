<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\mutasi;
use App\Models\bahanBaku;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
    public function index(Request $request) {
        $title = 'Penjualan';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Pembelian', 'url' => route('penjualan.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

            $tanggalMulai = $request->input('tanggal_mulai');
            $tanggalSampai = $request->input('tanggal_sampai');

            $penjualan = penjualan::with('cabang', 'mutasi.bahanBaku.satuan');

            // Tambahkan filter hanya jika kedua tanggal ada
            if (!empty($tanggalMulai) && !empty($tanggalSampai)) {
                $penjualan = $penjualan->whereBetween('created_at', [$tanggalMulai, $tanggalSampai]);
            }

            $penjualan = $penjualan->latest()->get();

        return view('penjualan.index', compact('title', 'breadcrumbs', 'penjualan'));
    }

    public function create() {
        $title = 'Penjualan';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Penjualan', 'url' => route('penjualan.index')],
            ['label' => 'Form Tambah', 'url' => null],
        ];
        $cabang = Cabang::pluck('nama', 'id');
        $produk = bahanBaku::with('satuan')->get();

        return view('penjualan.create', compact('title', 'breadcrumbs', 'cabang', 'produk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_cabang' => 'required|exists:cabang,id',
            'produk' => 'required|array',
            'produk.*' => 'required|exists:bahan_baku,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
            'harga' => 'required|array',
            'harga.*' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $nobukti = 'J' . now()->format('YmdHis') . rand(1000, 9999);

            $total = 0;
            foreach ($request->produk as $index => $idBahanBaku) {
                $total += $request->quantity[$index] * $request->harga[$index];
            }

            $pembelian = Penjualan::create([
                'nobukti' => $nobukti,
                'total' => $total,
                'status' => 'pending',
                'catatan' => $request->catatan,
                'id_cabang' => $request->id_cabang,
                // 'id_user' => Auth::id(),
            ]);

            foreach ($request->produk as $index => $idBahanBaku) {
                $mutasi = mutasi::create([
                    'nobukti' => $nobukti,
                    'id_bahan_baku' => $idBahanBaku,
                    'quantity' => $request->quantity[$index],
                    'harga' => $request->harga[$index],
                    'sub_total' => $request->quantity[$index] * $request->harga[$index],
                    'jenis_transaksi' => 'K',
                    'status' => 1
                ]);

                // if ($mutasi->status == 1) {
                //     $bahanBaku = BahanBaku::find($idBahanBaku);
                //     if ($bahanBaku) {
                //         $bahanBaku->stok_akhir -= $request->quantity[$index];
                //         $bahanBaku->save();
                //     }
                // }
            }

            DB::commit();

            notify()->success('Data Penjualan berhasil disimpan');
            return redirect()->route('penjualan.index');

        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal menyimpan data Penjualan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function showStruk($id) {
    $penjualan = Penjualan::with('cabang', 'mutasi.bahanBaku.satuan')->findOrFail($id);
    return view('penjualan.struk', compact('penjualan'));
}

public function formPenjualan() {

        // Get all available bahan baku with stock > 0
        $bahanBaku = bahanBaku::with('satuan', 'kategori')
    ->leftJoin('vsaldoakhir2', 'bahan_baku.id', '=', 'vsaldoakhir2.id')
    ->where('vsaldoakhir2.saldoakhir', '>', 0)
    ->get();

    return view('penjualan.form-penjualan', [
        'title' => 'Checkout Bahan Baku',
        'bahanBaku' => $bahanBaku
    ]);
}

}


