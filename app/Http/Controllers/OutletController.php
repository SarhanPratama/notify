<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use chillerlan\QRCode\QRCode;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use chillerlan\QRCode\QROptions;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;

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
            ['label' => 'Outlet', 'url' => route('outlet.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];

        return view('outlet.admin.index', compact('outlet', 'breadcrumbs', 'title'));
    }


    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'kode' => 'required',
            'nama' => 'required|string',
            'penanggung_jawab' => 'required|string',
            'telepon' => 'required|string',
            'alamat' => 'required|string',
            'lokasi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $fotoPath = $foto->storeAs('uploads/outlet', $fotoName, 'public');
        }

        Outlet::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'penanggung_jawab' => $request->penanggung_jawab,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'lokasi' => $request->lokasi,
            'foto' => $fotoPath
        ]);
        notify()->success('Data berhasil di input');
        return redirect()->back();
    }
     public function show($id)
    {
        $outlet = Outlet::findOrFail($id);

        // Generate QR Code if token exists
        $qrCode = '';
        if ($outlet->barcode_token) {
            $options = new QROptions([
                'version'    => 7,
                'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                'eccLevel'   => QRCode::ECC_M,
            ]);

            $qrcode = new QRCode($options);
            $imageData = $qrcode->render($outlet->barcode_url);
            $qrCode = 'data:image/png;base64,' . base64_encode($imageData);
            // Check if it's base64 encoded and decode it
            if (base64_decode($imageData, true) !== false && base64_encode(base64_decode($imageData)) === $imageData) {
                $qrCode = base64_decode($imageData);
            } else {
                $qrCode = $imageData;
            }
        }

        $title = 'QR Code Outlet - ' . $outlet->nama;
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Outlet', 'url' => route('outlet.index')],
            ['label' => 'QR Code', 'url' => null],
        ];

        return view('outlet.admin.show', compact('outlet', 'qrCode', 'title', 'breadcrumbs'));
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
            'kode' => 'required',
            'nama' => 'required|string|max:255',
            'penanggung_jawab' => 'required|string|max:255',
            'telepon' => 'required|string',
            'alamat' => 'required|string',
            'lokasi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $outlet = Outlet::findOrFail($id);


        $fotoPath = $outlet->foto;
        if ($request->hasFile('foto')) {
            if ($outlet->foto && file_exists(storage_path('app/public/' . $outlet->foto))) {
                unlink(storage_path('app/public/' . $outlet->foto));
            }

            $foto = $request->file('foto');
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $fotoPath = $foto->storeAs('uploads/cabang', $fotoName, 'public');
        }

        $outlet->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'penanggung_jawab' => $request->penanggung_jawab,
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
     * Generate new barcode token
     */
    public function generateBarcode($id)
    {
        try {
            $outlet = Outlet::findOrFail($id);
            $outlet->generateBarcodeToken();

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
        // Ambil outlet
        $outlet = Outlet::findOrFail($id);

        // Pastikan ada token
        if (empty($outlet->barcode_token)) {
            $outlet->barcode_token = Str::random(40);
            $outlet->save();
        }

        // --------------- Konfigurasi posisi & ukuran ---------------
        $canvasW = 1748;
        $canvasH = 2480;

        $qrSize = 800;                  // px
        $qrX = intval(($canvasW - $qrSize) / 2); // 424
        $qrY = 848;                     // atas QR; sesuaikan jika perlu

        $namaY = $qrY + $qrSize + 342;   // 1508 - posisi nama outlet di bawah QR
        $namaFontSize = 80;
        // ----------------------------------------------------------

        // URL yang diencode (sesuaikan route)
        $qrUrl = route('outlet.belanja', ['token' => $outlet->barcode_token]);

        // 1) generate QR raw PNG (chillerlan)
        $options = new QROptions([
            'version'    => QRCode::VERSION_AUTO, // Auto-detect version based on content length
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'   => QRCode::ECC_H, // H supaya aman jika mau overlay logo
            'scale'      => 8,
            'imageBase64' => false,
        ]);
        $qrcode = new QRCode($options);
        $qrPng = $qrcode->render($qrUrl);

        // 2) load template canvas
        $templatePath = public_path('assets/img/template-qr.png');
        if (!file_exists($templatePath)) {
            abort(500, 'Template not found: ' . $templatePath);
        }
        $manager = new ImageManager(Driver::class);
        $canvas = $manager->read($templatePath);

        // 3) load QR sebagai image Intervention lalu resize ke qrSize
        $qrImg = $manager->read($qrPng)->resize($qrSize, $qrSize);

        // 4) optional: insert small brand logo ke tengah QR (jika mau)
        // Uncomment jika ingin menaruh logo di tengah QR
        $brandPath = public_path('assets/img/logo/icon2.png');
        if (file_exists($brandPath)) {
            $logo = $manager->read($brandPath)->resize(140, 140, function($constraint){
                $constraint->aspectRatio();
            });
            $qrImg->place($logo, 'center');
        }


        // 5) insert QR ke canvas di posisi (qrX, qrY)
        $canvas->place($qrImg, 'top-left', $qrX, $qrY);

        // 6) tulis Nama Outlet (dynamic) — uppercase untuk konsistensi
        $outletName = strtoupper($outlet->nama ?? 'OUTLET');
        $canvas->text($outletName, $canvasW / 2, $namaY, function ($font) use ($namaFontSize) {
            $font->file(public_path('assets/font/Poppins-Bold.ttf'));
            $font->size($namaFontSize);
            $font->color('#FFFFFF');
            $font->align('center');
            $font->valign('top');
        });

        // 7) Simpan & download
        $filename = 'qrcode_seroo_' . Str::slug($outlet->kode ?? $outlet->id) . '.png';
        $savePath = storage_path('app/public/' . $filename);
        $canvas->save($savePath, 90);

        return response()->download($savePath)->deleteFileAfterSend(true);
    }

    /**
     * Regenerate barcode token
     */
    public function regenerateBarcode($id)
    {
        try {
            $cabang = Outlet::findOrFail($id);
            $cabang->regenerateBarcodeToken();

            notify()->success('QR Code berhasil diregenerasi!');
            return redirect()->back();
        } catch (\Exception $e) {
            notify()->error('Gagal regenerate QR Code: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Toggle barcode status
     */
    public function toggleBarcodeStatus(Request $request, $id)
    {
        try {
            $cabang = Outlet::findOrFail($id);

            // Toggle status
            $cabang->barcode_active = !$cabang->barcode_active;
            $cabang->save();

            $status = $cabang->barcode_active ? 'diaktifkan' : 'dinonaktifkan';
            $message = "Barcode berhasil {$status} untuk outlet {$cabang->nama}";

            // Jika ada alasan, bisa disimpan ke log atau field lain jika diperlukan
            if ($request->filled('alasan')) {
                // Log alasan jika diperlukan
                // Log::info("Barcode status changed for outlet {$cabang->nama}: {$request->alasan}");
            }

            notify()->success($message);
            return redirect()->back();
        } catch (\Exception $e) {
            notify()->error('Gagal mengubah status barcode: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
