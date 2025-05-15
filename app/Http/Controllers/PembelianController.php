<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\mutasi;
use App\Models\Supplier;
use App\Models\bahanBaku;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PembelianController extends Controller
{

    // private function generateKodePembelian()
    // {
    //     $date = Carbon::now()->format('Ymd');

    //     $lastPembelian = Pembelian::where('nobukti', 'like', 'PBL-' . $date . '%')->orderBy('nobukti', 'desc')->first();

    //     if ($lastPembelian) {
    //         $lastNumber = (int) substr($lastPembelian->nobukti, -3);
    //         $nextNumber = $lastNumber + 1;
    //     } else {
    //         $nextNumber = 1;
    //     }

    //     return 'PBL-' . $date . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    // }

    public function index(Request $request)
    {
        $title = 'Pembelian';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Pembelian', 'url' => route('pembelian.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        // if ($request->has('tanggal') && !empty($request->tanggal)) {
        //     $tanggal = $request->tanggal;
        // } else {
        //     $tanggal = now()->format('Y-m-d');
        // }

        // $pembelian = Pembelian::with('supplier', 'detailPembelian.bahanBaku.satuan', 'user')
        //     ->whereDate('created_at', $tanggal)
        //     ->get();

        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSampai = $request->input('tanggal_sampai');

        $pembelian = Pembelian::with('supplier', 'detailPembelian.bahanBaku.satuan', 'user');

        // Tambahkan filter hanya jika kedua tanggal ada
        if (!empty($tanggalMulai) && !empty($tanggalSampai)) {
            $pembelian = $pembelian->whereBetween('created_at', [$tanggalMulai, $tanggalSampai]);
        }

        $pembelian = $pembelian->latest()->get();

        $suppliers = Supplier::pluck('nama', 'id');
        // $bahanBaku = bahanBaku::with('satuan')->get();

        return view('pembelian.index', compact('title', 'breadcrumbs', 'pembelian', 'suppliers'));
    }

    public function create()
    {
        $title = 'Tambah Pembelian';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Pembelian', 'url' => route('pembelian.index')],
            ['label' => 'Form Tambah', 'url' => null],
        ];
        $suppliers = Supplier::pluck('nama', 'id');
        $bahanBaku = bahanBaku::with('satuan')->get();

        return view('pembelian.create', compact('title', 'breadcrumbs', 'suppliers', 'bahanBaku'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required',
            'id_supplier' => 'required|exists:supplier,id',
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
            $nobukti = 'B' . now()->format('YmdHis') . rand(1000, 9999);

            // Hitung total
            $total = 0;
            foreach ($request->produk as $index => $idBahanBaku) {
                $total += $request->quantity[$index] * $request->harga[$index];
            }

            // Simpan pembelian
            $pembelian = Pembelian::create([
                'nobukti' => $nobukti,
                'total' => $total,
                'tanggal' => $request->tanggal,
                // 'status' => 'pending',
                'catatan' => $request->catatan,
                'id_supplier' => $request->id_supplier,
                'id_user' => Auth::id(),
            ]);

            // Simpan detail transaksi
            foreach ($request->produk as $index => $idBahanBaku) {
                $mutasi = mutasi::create([
                    'nobukti' => $nobukti,
                    'id_bahan_baku' => $idBahanBaku,
                    'quantity' => $request->quantity[$index],
                    'harga' => $request->harga[$index],
                    'sub_total' => $request->quantity[$index] * $request->harga[$index],
                    'jenis_transaksi' => 'M',
                    'status' => 1
                ]);

                if ($mutasi->status == 1) {
                    $bahanBaku = BahanBaku::find($idBahanBaku);
                    if ($bahanBaku) {
                        $bahanBaku->stok_akhir += $request->quantity[$index];
                        $bahanBaku->save();
                    }
                }
            }

            DB::commit();

            notify()->success('Pembelian berhasil disimpan');
            return redirect()->route('pembelian.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal menyimpan pembelian: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function edit(Request $request, $nobukti)
    {
        $title = 'Pembelian';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Pembelian', 'url' => route('pembelian.index')],
            ['label' => 'Form Edit', 'url' => null],
        ];

        $pembelian = Pembelian::with(['detailPembelian.bahanBaku.satuan', 'supplier'])
        ->where('nobukti', $nobukti)
        ->firstOrFail();

        // dd($pembelian);



        $suppliers = Supplier::pluck('nama', 'id');
        $produk = bahanBaku::with('satuan')->get();

        return view('pembelian.edit', compact('title', 'breadcrumbs', 'pembelian', 'suppliers', 'produk'));
    }

    public function update(Request $request, $nobukti)
    {
        $request->validate([
            'id_supplier' => 'required|exists:supplier,id',
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
            $pembelian = Pembelian::where('nobukti', $nobukti)->firstOrFail();

            // Hitung total baru
            $totalBaru = 0;
            foreach ($request->produk as $index => $idBahanBaku) {
                $totalBaru += $request->quantity[$index] * $request->harga[$index];
            }

            // Cek apakah data pembelian berubah
            $dataPembelianBaru = [
                'id_supplier' => $request->id_supplier,
                'total' => $totalBaru,
                'catatan' => $request->catatan,
            ];

            $dataPembelianLama = [
                'id_supplier' => $pembelian->id_supplier,
                'total' => $pembelian->total,
                'catatan' => $pembelian->catatan,
            ];

            $produkLama = mutasi::where('nobukti', $nobukti)
                ->orderBy('id_bahan_baku')
                ->get()
                ->map(function ($item) {
                    return [
                        'id_bahan_baku' => $item->id_bahan_baku,
                        'quantity' => $item->quantity,
                        'harga' => $item->harga
                    ];
                })->values()->toArray();

            $produkBaru = collect($request->produk)->map(function ($id, $index) use ($request) {
                return [
                    'id_bahan_baku' => $id,
                    'quantity' => $request->quantity[$index],
                    'harga' => $request->harga[$index]
                ];
            })->sortBy('id_bahan_baku')->values()->toArray();

            if (
                $dataPembelianLama == $dataPembelianBaru &&
                $produkLama == $produkBaru
            ) {
                DB::rollBack();
                notify()->info('Tidak ada data yang diupdate');
                return redirect()->back();
            }

            $pembelian->update($dataPembelianBaru);

            $mutasiLama = mutasi::where('nobukti', $nobukti)->get()->keyBy('id_bahan_baku');

            foreach ($produkBaru as $item) {
                $idBahanBaku = $item['id_bahan_baku'];
                $quantityBaru = $item['quantity'];
                $hargaBaru = $item['harga'];
                $subTotal = $quantityBaru * $hargaBaru;

                if ($mutasiLama->has($idBahanBaku)) {
                    $mutasi = $mutasiLama[$idBahanBaku];

                    // Update stok (rollback lama, masukkan baru)
                    if ($mutasi->status == 1) {
                        $bahanBaku = BahanBaku::find($idBahanBaku);
                        if ($bahanBaku) {
                            $bahanBaku->stok_akhir -= $mutasi->quantity;
                            $bahanBaku->stok_akhir += $quantityBaru;
                            $bahanBaku->save();
                        }
                    }

                    $mutasi->update([
                        'quantity' => $quantityBaru,
                        'harga' => $hargaBaru,
                        'sub_total' => $subTotal,
                    ]);
                } else {
                    // Tambah mutasi baru
                    $mutasiBaru = mutasi::create([
                        'nobukti' => $nobukti,
                        'id_bahan_baku' => $idBahanBaku,
                        'quantity' => $quantityBaru,
                        'harga' => $hargaBaru,
                        'sub_total' => $subTotal,
                        'jenis_transaksi' => 'M',
                        'status' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    if ($mutasiBaru->status == 1) {
                        $bahanBaku = BahanBaku::find($idBahanBaku);
                        if ($bahanBaku) {
                            $bahanBaku->stok_akhir += $quantityBaru;
                            $bahanBaku->save();
                        }
                    }
                }
            }

            DB::commit();
            notify()->success('Pembelian berhasil diperbarui');
            return redirect()->route('pembelian.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal memperbarui pembelian: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }


    // use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;
    // public function updateStatus(Request $request, $kode)
    // {
    //     // Validasi input status
    //     $request->validate([
    //         'status' => 'required|in:completed,pending,cancelled'
    //     ]);

    //     // Temukan pembelian berdasarkan kode
    //     $pembelian = Pembelian::with('detailPembelian.bahanBaku')->where('nobukti', $kode)->firstOrFail();
    //     // dd($pembelian->detailPembelian);
    //     // Simpan status lama untuk pengecekan
    //     $oldStatus = $pembelian->status;
    //     $newStatus = $request->status;

    //     // Jika mengubah ke Completed
    //     if ($newStatus === 'completed' && $oldStatus !== 'completed') {
    //         // Validasi detail pembelian
    //         if ($pembelian->detailPembelian->isEmpty()) {
    //             notify()->error('Detail pembelian tidak ditemukan.');
    //             return back();
    //         }

    //         DB::beginTransaction();
    //         try {
    //             // Update stok produk
    //             foreach ($pembelian->detailPembelian as $detail) {
    //                 $produk = $detail->produk ?? bahanBaku::find($detail->id_produk);

    //                 if (!$produk) {
    //                     throw new \Exception("Produk dengan ID {$detail->id_produk} tidak ditemukan");
    //                 }

    //                 $produk->stok += $detail->quantity;
    //                 $produk->save();
    //             }

    //             // Update status pembelian
    //             $pembelian->status = $newStatus;
    //             $pembelian->save();

    //             DB::commit();
    //             notify()->success('Status pembelian berhasil diupdate dan stok berhasil dibertambah.');

    //         } catch (\Exception $e) {
    //             DB::rollBack();
    //             notify()->error('Gagal update status: ' . $e->getMessage());
    //             return back();
    //         }

    //     } else {
    //         // Jika bukan status Completed, cukup update status
    //         $pembelian->status = $newStatus;
    //         $pembelian->save();
    //         notify()->success('Status pembelian berhasil diupdate.');
    //     }

    //     return redirect()->route('pembelian.index');
    // }


    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $pembelian = Pembelian::findOrFail($id);

            // Ambil semua mutasi berdasarkan nomor bukti
            $mutasiList = Mutasi::where('nobukti', $pembelian->nobukti)->get();

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

            // Hapus pembelian
            $pembelian->delete();

            DB::commit();

            notify()->success('Data pembelian berhasil dihapus.');
            return redirect()->route('pembelian.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal menghapus pembelian: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function laporanPembelian(Request $request)
    {
        $title = 'Laporan Pembelian';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Bahan Baku', 'url' => route('bahan-baku.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        // $laporan_pembelian = Pembelian::with('supplier', 'detailPembelian.bahanBaku.satuan', 'user')
        // ->where('')
        // ->get();

        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSampai = $request->input('tanggal_sampai');

        $laporan_pembelian = Mutasi::with(['bahanBaku.satuan', 'pembelian.supplier'])
            ->where('jenis_transaksi', 'M')
            ->where('status', 1);

        if (!empty($tanggalMulai) && !empty($tanggalSampai)) {
            $laporan_pembelian->whereBetween('created_at', [$tanggalMulai, $tanggalSampai]);
        }

        $laporan_pembelian = $laporan_pembelian->latest()->get();

        // dd($laporan_pembelian);

        return view('pembelian.laporan-pembelian', compact('title', 'breadcrumbs', 'laporan_pembelian'));
    }

    public function exportPDF(Request $request)
    {
        $title = 'Laporan Pembelian';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Bahan Baku', 'url' => route('bahan-baku.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSampai = $request->input('tanggal_sampai');

        $laporan_pembelian = Mutasi::with(['bahanBaku', 'pembelian.supplier'])
            ->where('jenis_transaksi', 'M')
            ->where('status', 1);

            if (!empty($tanggalMulai) && !empty($tanggalSampai)) {
                $laporan_pembelian->whereBetween('created_at', [$tanggalMulai, $tanggalSampai]);
                $periode = 'Periode: ' .
                    \Carbon\Carbon::parse($tanggalMulai)->translatedFormat('d M Y') .
                    ' - ' .
                    \Carbon\Carbon::parse($tanggalSampai)->translatedFormat('d M Y');
            } else {
                $periode = 'Semua Periode';
            }

        $laporan_pembelian = $laporan_pembelian->latest()->get();
        // dd($laporan_pembelian);
        $pdf = Pdf::loadView('pembelian.pembelian-pdf', [
            'laporan' => $laporan_pembelian,
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
            'periode' => $periode,

        ])->setPaper('A4', 'landscape');


        return $pdf->stream('Laporan_Pembelian.pdf');
    }
}
