<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\mutasi;
use App\Models\Supplier;
use App\Models\BahanBaku;
use App\Models\Pembelian;
use App\Models\SumberDana;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\PembelianService;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StorePembelianRequest;

class PembelianController extends Controller
{


    public function index(Request $request)
    {
        $title = 'Pembelian';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Pembelian', 'url' => route('pembelian.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSampai = $request->input('tanggal_sampai');

        $pembelian = Pembelian::with('supplier', 'mutasi.bahanBaku.satuan', 'user');

        if (!empty($tanggalMulai) && !empty($tanggalSampai)) {
            $pembelian = $pembelian->whereBetween('created_at', [$tanggalMulai, $tanggalSampai]);
        }

        $pembelian = $pembelian->latest()->withTrashed()->get();
        // dd($pembelian);

        return view('pembelian.index', compact('title', 'breadcrumbs', 'pembelian'));
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
        $sumberDana = SumberDana::pluck('nama', 'id');
        $bahanBaku = BahanBaku::with('satuan')->get();

        return view('pembelian.create', compact('title', 'breadcrumbs', 'suppliers', 'sumberDana', 'bahanBaku'));
    }

    public function store(StorePembelianRequest $request, PembelianService $pembelianService)
    {
        try {
            $validated = $request->validated();
            $pembelianService->tambah($validated);
        } catch (\Exception $e) {
            notify()->error('Gagal menyimpan pembelian: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
        notify()->success('Pembelian berhasil disimpan');
        return redirect()->route('pembelian.index');
    }

    public function show($nobukti, PembelianService $pembelianService)
    {

        $title = 'Detail Pembelian';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Pembelian', 'url' => route('pembelian.index')],
            ['label' => 'Detail', 'url' => null],
        ];

        $detailPembelian = $pembelianService->getPembelianDetails($nobukti);
        // dd($detailPembelian);
        return view('pembelian.show', compact('title', 'breadcrumbs', 'detailPembelian'));
    }

    public function edit(Request $request, $nobukti, PembelianService $pembelianService)
    {
        $title = 'Pembelian';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Pembelian', 'url' => route('pembelian.index')],
            ['label' => 'Form Edit', 'url' => null],
        ];

        $detailPembelian = $pembelianService->getPembelianDetails($nobukti);
        // dd($detailPembelian);

        $sumberDana = SumberDana::pluck('nama', 'id');
        $suppliers = Supplier::pluck('nama', 'id');
        $produk = BahanBaku::with('satuan')->get();

        return view('pembelian.edit', compact('title', 'breadcrumbs', 'detailPembelian', 'suppliers', 'sumberDana', 'produk'));
    }

    public function update(StorePembelianRequest $request, $nobukti, PembelianService $pembelianService)
    {

        try {
            $validated = $request->validated();
            // dd($validated);
            $pembelianService->updatePembelian($nobukti, $validated);
            notify()->success('Pembelian berhasil diperbarui');
            return redirect()->route('pembelian.index');
        } catch (\Exception $e) {
            notify()->error('Gagal memperbarui pembelian: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $pembelian = Pembelian::with('mutasi', 'transaksi.SumberDana')->findOrFail($id);
            $transaksi = $pembelian->transaksi->first();

            if ($transaksi && $transaksi->sumberDana) {
                $sumberDana = $transaksi->sumberDana;
                $sumberDana->saldo_current += $transaksi->jumlah;
                $sumberDana->save();
            }

            foreach ($pembelian->mutasi as $mutasi) {
                if ($mutasi->status == 1) {
                    $mutasi->delete();
                }
            }

            $transaksi = $pembelian->transaksi->first();
            $transaksi->status = 0;
            $transaksi->delete();

            $pembelian->status = 0;
            $pembelian->save();
            $pembelian->delete();

            DB::commit();

            notify()->success('Data pembelian berhasil dihapus sementara.');
            return redirect()->route('pembelian.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal menghapus pembelian: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();

        try {
            $pembelian = Pembelian::withTrashed()
                ->with([
                    'mutasi' => fn($q) => $q->withTrashed(),
                    'transaksi' => fn($q) => $q->withTrashed()->with('sumberDana')
                ])
                ->where('nobukti', $id)
                ->firstOrFail();

            $pembelian->status = 1;
            $pembelian->restore();

            // Restore mutasi
            foreach ($pembelian->mutasi as $mutasi) {
                $mutasi->restore();
            }

            $transaksi = $pembelian->transaksi->first();
            if ($transaksi) {
                $transaksi->status = 1;
                $transaksi->restore();
                if ($transaksi->sumberDana) {
                    $sumberDana = $transaksi->sumberDana;
                    $sumberDana->saldo_current -= $transaksi->jumlah;
                    $sumberDana->save();
                }
            }

            DB::commit();
            notify()->success('Data pembelian berhasil dipulihkan dan saldo diperbarui.');
            return redirect()->route('pembelian.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal memulihkan pembelian: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function forceDelete($id)
    {
        DB::beginTransaction();

        try {
            $pembelian = Pembelian::withTrashed()
                ->with([
                    'mutasi' => fn($q) => $q->withTrashed(),
                    'transaksi' => fn($q) => $q->withTrashed()->with('sumberDana')
                ])
                ->findOrFail($id);

            $transaksi = $pembelian->transaksi->first();

            if ($transaksi && $transaksi->sumberDana) {
                $sumberDana = $transaksi->sumberDana;

                if (is_null($transaksi->deleted_at)) {
                    $sumberDana->saldo_current += $transaksi->jumlah;
                    $sumberDana->save();
                }
            }

            foreach ($pembelian->mutasi as $mutasi) {
                $mutasi->forceDelete();
            }

            if ($transaksi) {
                $transaksi->forceDelete();
            }

            $pembelian->forceDelete();

            DB::commit();

            notify()->success('Data pembelian berhasil dihapus permanen.');
            return redirect()->route('pembelian.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal menghapus permanen: ' . $e->getMessage());
            return redirect()->back();
        }
    }



    public function laporanPembelian(Request $request)
    {
        $title = 'Laporan Pembelian';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            // ['label' => 'Laporan Pembelian', 'url' => route('laporan-pembelian')],
            ['label' => 'Laporan Pembelian', 'url' => null],
        ];

        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSampai = $request->input('tanggal_sampai');

        $laporan_pembelian = collect();
        $showTable = false;

        if (!empty($tanggalMulai) && !empty($tanggalSampai)) {
            $tanggalSampaiFormatted = Carbon::parse($tanggalSampai)->endOfDay()->toDateTimeString();

            $laporan_pembelian = Mutasi::with(['bahanBaku.satuan', 'pembelian.supplier'])
                ->where('jenis_transaksi', 'M')
                ->where('status', 1)
                ->whereBetween('created_at', [$tanggalMulai, $tanggalSampaiFormatted])
                ->latest()
                ->get();
            $showTable = true;
        }

        return view('pembelian.laporan-pembelian', compact(
            'title',
            'breadcrumbs',
            'laporan_pembelian',
            'showTable',
            'tanggalMulai',
            'tanggalSampai'
        ));
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
