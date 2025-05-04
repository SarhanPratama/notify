<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\mutasi;
use App\Models\bahanBaku;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
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

    public function create()
    {
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

            $penjualan = Penjualan::create([
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

    public function edit($nobukti)
    {
        $title = 'Penjualan';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Penjualan', 'url' => route('penjualan.index')],
            ['label' => 'Form Edit', 'url' => null],
        ];

        $penjualan = Penjualan::with(['mutasi.bahanBaku.satuan', 'cabang'])
        ->where('nobukti', $nobukti)
        ->firstOrFail();

        $cabang = Cabang::pluck('nama', 'id');
        $produk = BahanBaku::with('satuan')->get();

        return view('penjualan.edit', compact('title', 'breadcrumbs', 'penjualan', 'cabang', 'produk'));
    }


    public function update(Request $request, $nobukti)
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
            $penjualan = Penjualan::where('nobukti', $nobukti)->firstOrFail();

            // Hitung ulang total baru
            $total = 0;
            foreach ($request->produk as $index => $idBahanBaku) {
                $total += $request->quantity[$index] * $request->harga[$index];
            }

            // Update data utama penjualan
            $penjualan->update([
                'total' => $total,
                'catatan' => $request->catatan,
                'id_cabang' => $request->id_cabang,
            ]);

            // Hapus semua mutasi lama berdasarkan nobukti
            $mutasiLama = Mutasi::where('nobukti', $nobukti)->get();
            foreach ($mutasiLama as $mutasi) {
                // Rollback stok jika perlu
                // $bahanBaku = BahanBaku::find($mutasi->id_bahan_baku);
                // if ($bahanBaku && $mutasi->status == 1) {
                //     $bahanBaku->stok_akhir += $mutasi->quantity;
                //     $bahanBaku->save();
                // }

                $mutasi->delete();
            }

            // Simpan mutasi baru
            foreach ($request->produk as $index => $idBahanBaku) {
                $quantity = $request->quantity[$index];
                $harga = $request->harga[$index];

                $mutasiBaru = Mutasi::create([
                    'nobukti' => $nobukti,
                    'id_bahan_baku' => $idBahanBaku,
                    'quantity' => $quantity,
                    'harga' => $harga,
                    'sub_total' => $quantity * $harga,
                    'jenis_transaksi' => 'K', // Penjualan = keluar
                    'status' => 1
                ]);

                // Jika ingin update stok:
                // $bahanBaku = BahanBaku::find($idBahanBaku);
                // if ($bahanBaku) {
                //     $bahanBaku->stok_akhir -= $quantity;
                //     $bahanBaku->save();
                // }
            }

            DB::commit();
            notify()->success('Data Penjualan berhasil diperbarui');
            return redirect()->route('penjualan.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal memperbarui data Penjualan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function showStruk($id)
    {
        $penjualan = Penjualan::with('cabang', 'mutasi.bahanBaku.satuan')->findOrFail($id);
        return view('penjualan.struk', compact('penjualan'));
    }

    public function laporanPenjualan(Request $request) {
        $title = 'Laporan Penjualan';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Laporan Penjualan', 'url' => route('bahan-baku.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        // $laporan_pembelian = Pembelian::with('supplier', 'detailPembelian.bahanBaku.satuan', 'user')
        // ->where('')
        // ->get();

        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSampai = $request->input('tanggal_sampai');

        $laporan_penjualan = Mutasi::with(['bahanBaku.satuan', 'penjualan.cabang'])
            ->where('jenis_transaksi', 'K')
            ->where('status', 1);

        if (!empty($tanggalMulai) && !empty($tanggalSampai)) {
            $laporan_penjualan->whereBetween('created_at', [$tanggalMulai, $tanggalSampai]);
        }

        $laporan_penjualan = $laporan_penjualan->latest()->get();

        // dd($laporan_penjualan);

        return view('penjualan.laporan-penjualan', compact('title', 'breadcrumbs', 'laporan_penjualan'));
    }

    public function exportPDF(Request $request)
    {
        $title = 'Laporan Penjualan';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Penjualan', 'url' => route('penjualan.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSampai = $request->input('tanggal_sampai');

        $laporan_penjualan = Mutasi::with(['bahanBaku.satuan', 'penjualan.cabang'])
            ->where('jenis_transaksi', 'K')
            ->where('status', 1);

            if (!empty($tanggalMulai) && !empty($tanggalSampai)) {
                $laporan_penjualan->whereBetween('created_at', [$tanggalMulai, $tanggalSampai]);
                $periode = 'Periode: ' .
                    \Carbon\Carbon::parse($tanggalMulai)->translatedFormat('d M Y') .
                    ' - ' .
                    \Carbon\Carbon::parse($tanggalSampai)->translatedFormat('d M Y');
            } else {
                $periode = 'Semua Periode';
            }

        $laporan_penjualan = $laporan_penjualan->latest()->get();
        // dd($laporan_penjualan);
        $pdf = Pdf::loadView('penjualan.penjualan-pdf', [
            'laporan' => $laporan_penjualan,
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
            'periode' => $periode,

        ])->setPaper('A4', 'landscape');


        return $pdf->stream('Laporan_Penjualan.pdf');
    }

    public function formPenjualan()
    {

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
