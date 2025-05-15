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
            $penjualan = $penjualan->whereBetween('tanggal', [$tanggalMulai, $tanggalSampai]);
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
            'tanggal' => 'required',
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
                'tanggal' => $request->tanggal,
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
        // dd($request);
        $request->validate([
            'tanggal' => 'required',
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

            // Hitung total baru
            $totalBaru = 0;
            foreach ($request->produk as $index => $idBahanBaku) {
                $totalBaru += $request->quantity[$index] * $request->harga[$index];
            }

            $dataPenjualanBaru = [
                'tanggal' => $request->tanggal,
                'id_cabang' => $request->id_cabang,
                'total' => $totalBaru,
                'catatan' => $request->catatan,
            ];

            $dataPenjualanLama = [
                'tanggal' => $penjualan->tanggal,
                'id_cabang' => $penjualan->id_cabang,
                'total' => $penjualan->total,
                'catatan' => $penjualan->catatan,
            ];

            // Ambil mutasi lama dan susun datanya untuk perbandingan
            $mutasiLama = Mutasi::where('nobukti', $nobukti)
                ->orderBy('id_bahan_baku')
                ->get()
                ->map(function ($item) {
                    return [
                        'id_bahan_baku' => $item->id_bahan_baku,
                        'quantity' => $item->quantity,
                        'harga' => $item->harga
                    ];
                })->values()->toArray();

            // Susun input produk dari request
            $produkBaru = collect($request->produk)->map(function ($id, $index) use ($request) {
                return [
                    'id_bahan_baku' => $id,
                    'quantity' => $request->quantity[$index],
                    'harga' => $request->harga[$index]
                ];
            })->sortBy('id_bahan_baku')->values()->toArray();

            // Cek apakah tidak ada data yang berubah
            if ($dataPenjualanBaru == $dataPenjualanLama && $mutasiLama == $produkBaru) {
                DB::rollBack();
                notify()->info('Tidak ada data yang diupdate');
                return redirect()->back();
            }

            // Lanjut update penjualan
            $penjualan->update($dataPenjualanBaru);

            // Ambil mutasi lama untuk update
            $mutasiLama = Mutasi::where('nobukti', $nobukti)->get()->keyBy('id_bahan_baku');

            foreach ($produkBaru as $item) {
                $idBahanBaku = $item['id_bahan_baku'];
                $quantityBaru = $item['quantity'];
                $hargaBaru = $item['harga'];
                $subTotal = $quantityBaru * $hargaBaru;

                if ($mutasiLama->has($idBahanBaku)) {
                    $mutasi = $mutasiLama[$idBahanBaku];

                    // Update stok: rollback lama → kurangi baru
                    if ($mutasi->status == 1) {
                        $bahanBaku = BahanBaku::find($idBahanBaku);
                        if ($bahanBaku) {
                            $bahanBaku->stok_akhir += $mutasi->quantity; // rollback stok lama
                            $bahanBaku->stok_akhir -= $quantityBaru;     // kurangi stok baru
                            $bahanBaku->save();
                        }
                    }

                    // Update mutasi
                    $mutasi->update([
                        'quantity' => $quantityBaru,
                        'harga' => $hargaBaru,
                        'sub_total' => $subTotal,
                        'updated_at' => now(),
                    ]);
                } else {
                    // Mutasi belum ada, buat baru
                    $mutasiBaru = Mutasi::create([
                        'nobukti' => $nobukti,
                        'id_bahan_baku' => $idBahanBaku,
                        'quantity' => $quantityBaru,
                        'harga' => $hargaBaru,
                        'sub_total' => $subTotal,
                        'jenis_transaksi' => 'K',
                        'status' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    if ($mutasiBaru->status == 1) {
                        $bahanBaku = BahanBaku::find($idBahanBaku);
                        if ($bahanBaku) {
                            $bahanBaku->stok_akhir -= $quantityBaru;
                            $bahanBaku->save();
                        }
                    }
                }
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

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $penjualan = Penjualan::findOrFail($id);

            // Ambil semua mutasi berdasarkan nomor bukti
            $mutasiList = Mutasi::where('nobukti', $penjualan->nobukti)->get();

            foreach ($mutasiList as $mutasi) {
                // Jika status 1, rollback stok bahan baku
                if ($mutasi->status == 1) {
                    $bahanBaku = BahanBaku::find($mutasi->id_bahan_baku);
                    if ($bahanBaku) {
                        $bahanBaku->stok_akhir -= $mutasi->quantity;
                        $bahanBaku->save();
                    }
                }

                // Hapus mutasi
                $mutasi->delete();
            }

            // Hapus penjualan
            $penjualan->delete();

            DB::commit();

            notify()->success('Data penjualan berhasil dihapus.');
            return redirect()->route('penjualan.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal menghapus penjualan: ' . $e->getMessage());
            return redirect()->back();
        }
    }



    // public function showStruk($id)
    // {
    //     $penjualan = Penjualan::with('cabang', 'mutasi.bahanBaku.satuan')->findOrFail($id);
    //     return view('penjualan.struk', compact('penjualan'));
    // }

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
