<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Supplier;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use App\Models\DetailPembelian;
use App\Models\products;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{

    private function generateKodePembelian()
    {
        // Ambil tanggal hari ini dalam format YYYYMMDD
        $date = Carbon::now()->format('Ymd');

        // Ambil nomor urut terakhir dari database
        $lastPembelian = Pembelian::where('kode', 'like', 'PBL-' . $date . '%')->orderBy('kode', 'desc')->first();

        // Jika ada, increment nomor urut
        if ($lastPembelian) {
            $lastNumber = (int) substr($lastPembelian->kode, -3); // Ambil 3 digit terakhir
            $nextNumber = $lastNumber + 1;
        } else {
            // Jika tidak ada, mulai dari 001
            $nextNumber = 1;
        }

        // Format kode: PBL-YYYYMMDD-XXX
        return 'PBL-' . $date . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    public function index()
    {
        $title = 'Pembelian';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Pembelian', 'url' => route('pembelian.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];
        $pembelian = Pembelian::with('supplier')->get();

        $suppliers = Supplier::pluck('nama', 'id');
        $produk = products::pluck('nama', 'id');
        return view('pembelian.index', compact('title', 'breadcrumbs', 'pembelian', 'suppliers', 'produk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            // 'kode' => 'required|integer|unique:pembelian,kode',
            'id_supplier' => 'required|exists:supplier,id',
            'produk' => 'required|array',
            'produk.*' => 'required|exists:products,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
            'harga' => 'required|array',
            'harga.*' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $kodePembelian = $this->generateKodePembelian();

            $total = 0;
            foreach ($request->produk as $index => $idProduk) {
                $total += $request->quantity[$index] * $request->harga[$index];
            }

            $pembelian = Pembelian::create([
                'kode' =>  $kodePembelian,
                'total' => $total,
                'status' => 'pending',
                'id_supplier' => $request->id_supplier,
            ]);

            foreach ($request->produk as $index => $idProduk) {
                DetailPembelian::create([
                    'id_produk' => $idProduk,
                    'quantity' => $request->quantity[$index],
                    'harga' => $request->harga[$index],
                    'total_harga' => $request->quantity[$index] * $request->harga[$index],
                    'id_pembelian' => $pembelian->id,
                ]);
            }

            DB::commit();
            notify()->success('Data Berhasil ditambahkan');
            return redirect()->route('pembelian.index');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();
            notify()->success('Data Berhasil ditambahkan');
            return redirect()->back();
        }
    }
}
