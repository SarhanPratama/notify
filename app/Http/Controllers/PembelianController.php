<?php

namespace App\Http\Controllers;

use App\Models\bahanBaku;
use Carbon\Carbon;
use App\Models\Supplier;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use App\Models\DetailPembelian;
use App\Models\products;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

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
        $pembelian = Pembelian::with('supplier', 'detailPembelian.bahanBaku.satuan')->get();
        $suppliers = Supplier::pluck('nama', 'id');
        // $bahanBaku = bahanBaku::with('satuan')->get();

        return view('pembelian.index', compact('title', 'breadcrumbs', 'pembelian', 'suppliers'));
    }

    public function create()
    {
        $title = 'Pembelian';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Pembelian', 'url' => route('pembelian.index')],
            ['label' => 'Form Tambah', 'url' => null],
        ];
        $suppliers = Supplier::pluck('nama', 'id');
        $produk = bahanBaku::with('satuan')->get();

        return view('pembelian.create', compact('title', 'breadcrumbs', 'suppliers', 'produk'));
    }

    public function store(Request $request)
    {
        $request->validate([
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

            // Menghitung total harga pembelian
            $total = 0;
            foreach ($request->produk as $index => $idProduk) {
                $total += $request->quantity[$index] * $request->harga[$index];
            }

            // Membuat entri pembelian
            $pembelian = Pembelian::create([
                'kode' =>  $kodePembelian,
                'total' => $total,
                'status' => 'pending',
                'id_supplier' => $request->id_supplier,
            ]);

            // Menambahkan detail pembelian dan memperbarui stok produk
            foreach ($request->produk as $index => $idProduk) {
                $produk = bahanBaku::findOrFail($idProduk); // Menemukan produk berdasarkan ID

                // Perbarui stok produk (tambahkan quantity pembelian ke stok yang ada)
                $produk->stok += $request->quantity[$index];
                $produk->save();

                // Membuat detail pembelian
                DetailPembelian::create([
                    'id_produk' => $idProduk,
                    'quantity' => $request->quantity[$index],
                    'harga' => $request->harga[$index],
                    'total_harga' => $request->quantity[$index] * $request->harga[$index],
                    'id_pembelian' => $pembelian->id,
                ]);
            }

            // Commit transaksi jika tidak ada error
            DB::commit();

            notify()->success('Data Berhasil ditambahkan');
            return redirect()->route('pembelian.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function edit(Request $request, $id)
    {
        $title = 'Pembelian';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Pembelian', 'url' => route('pembelian.index')],
            ['label' => 'Form Tambah', 'url' => null],
        ];
        $suppliers = Supplier::pluck('nama', 'id');
        $produk = bahanBaku::with('satuan')->get();

        return view('pembelian.create', compact('title', 'breadcrumbs', 'suppliers', 'produk'));
    }


    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;
    public function updateStatus(Request $request, $id)
    {
        $item = Pembelian::findOrFail($id);
        $item->status = $request->input('status');
        $item->save();

        return redirect()->route('pembelian.index');
    }
}
