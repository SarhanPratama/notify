<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class OutletController extends Controller
{
    public function index(Request $request)
    {


        // $search = $request->input('search');
        // $perPage = $request->input('per_page');

        // $cabang = Outlet::when($search, function ($query, $search) {
        //     return $query->where(function ($query) use ($search) {
        //         $query->where('nama', 'LIKE', "%{$search}%")
        //               ->orWhere('alamat', 'LIKE', "%{$search}%");
        //     });
        // })->paginate($perPage);

        $outlet = Outlet::all();

        // dd($outlet);{}

        $title = 'Outlet';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Cabang', 'url' => route('outlet.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        return view('outlet.admin.index', compact('outlet', 'breadcrumbs', 'title'));
    }


    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'kode' => 'required',
            'nama' => 'required',
            'telepon' => 'required|string',
            'alamat' => 'required',
            'lokasi' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $fotoPath = $foto->storeAs('uploads/cabang', $fotoName, 'public');
        }

        Outlet::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'lokasi' => $request->lokasi,
            'foto' => $fotoPath
        ]);
        notify()->success('Data berhasil di input');
        return redirect()->back();
    }

    public function edit($id)
    {
        $title = 'Form Update Cabang';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Tabel', 'url' => route('outlet.index')],
            ['label' => 'Form Update Cabang', 'url' => null],
        ];

        $cabang = Outlet::where('id', $id)->first();

        return view('cabang.update', compact('cabang', 'breadcrumbs', 'title'));
    }

    public function update(Request $request, $id)
    {
        // dd($request);

        $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'required|string',
            'alamat' => 'required',
            'lokasi' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $cabang = Outlet::findOrFail($id);


        $fotoPath = $cabang->foto;
        if ($request->hasFile('foto')) {
            if ($cabang->foto && file_exists(storage_path('app/public/' . $cabang->foto))) {
                unlink(storage_path('app/public/' . $cabang->foto));
            }

            $foto = $request->file('foto');
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $fotoPath = $foto->storeAs('uploads/cabang', $fotoName, 'public');
        }

        $cabang->update([
            'nama' => $request->nama,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'lokasi' => $request->lokasi,
            'foto' => $fotoPath
        ]);

        notify()->success('Data berhasil di update');
        return redirect()->route('outlet.index');
    }

    public function destroy($id)
    {
        try {
            // Cari data cabang yang akan dihapus
            $cabang = Outlet::findOrFail($id);

            // Periksa apakah cabang memiliki foto dan hapus filenya
            if ($cabang->foto) {
                $filePath = storage_path('app/public/' . $cabang->foto);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // Hapus cabang
            $cabang->delete();

            notify()->success('Cabang berhasil dihapus!');
            return redirect()->route('outlet.index');

        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'foreign key constraint fails') !== false) {
                notify()->error('Cabang tidak bisa dihapus karena masih ada data terkait.');
                return redirect()->route('outlet.index');
            }
            notify()->error('Terjadi kesalahan saat menghapus cabang.');
            return redirect()->route('outlet.index');
        }
    }

    /**
     * Show barcode management page
     */
    public function showBarcode($id)
    {
        $cabang = Outlet::findOrFail($id);

        // Generate QR Code if token exists
        $qrCode = '';
        if ($cabang->barcode_token) {
            $options = new QROptions([
                'version'    => 7,
                'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                'eccLevel'   => QRCode::ECC_M,
            ]);

            $qrcode = new QRCode($options);
            $imageData = $qrcode->render($cabang->barcode_url);
            $qrCode = 'data:image/png;base64,' . base64_encode($imageData);
            // Check if it's base64 encoded and decode it
            if (base64_decode($imageData, true) !== false && base64_encode(base64_decode($imageData)) === $imageData) {
                $qrCode = base64_decode($imageData);
            } else {
                $qrCode = $imageData;
            }
        }

        $title = 'QR Code Outlet - ' . $cabang->nama;
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Outlet', 'url' => route('outlet.index')],
            ['label' => 'QR Code', 'url' => null],
        ];

        return view('outlet.admin.barcode', compact('cabang', 'qrCode', 'title', 'breadcrumbs'));
    }

    /**
     * Generate new barcode token
     */
    public function generateBarcode($id)
    {
        try {
            $cabang = Outlet::findOrFail($id);
            $cabang->generateBarcodeToken();

            notify()->success('QR Code berhasil dibuat!');
            return redirect()->back();
        } catch (\Exception $e) {
            notify()->error('Gagal membuat QR Code: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Download barcode as PNG
     */
    public function downloadBarcode($id)
    {
        $cabang = Outlet::findOrFail($id);

        if (!$cabang->barcode_token) {
            $cabang->generateBarcodeToken();
        }

        // Generate QR Code as PNG
        $options = new QROptions([
            'version'    => 5,
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'   => QRCode::ECC_L,
            'scale'      => 8,
            'imageBase64' => false,
        ]);

        $qrcode = new QRCode($options);
        $qrCodePng = $qrcode->render($cabang->barcode_url);

        $filename = 'qrcode-outlet-' . $cabang->kode . '.png';

        return response($qrCodePng)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Regenerate barcode token
     */
    public function regenerateBarcode($id)
    {
        try {
            $cabang = Outlet::findOrFail($id);
            $cabang->regenerateBarcodeToken();

            return response()->json([
                'success' => true,
                'message' => 'QR Code berhasil di-regenerate!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal regenerate QR Code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle barcode status
     */
    public function toggleBarcodeStatus($id)
    {
        try {
            $cabang = Outlet::findOrFail($id);
            $cabang->barcode_active = !$cabang->barcode_active;
            $cabang->save();

            $status = $cabang->barcode_active ? 'diaktifkan' : 'dinonaktifkan';

            return response()->json([
                'success' => true,
                'message' => "QR Code berhasil {$status}!",
                'status' => $cabang->barcode_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status QR Code: ' . $e->getMessage()
            ], 500);
        }
    }
}
