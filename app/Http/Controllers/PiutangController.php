<?php

namespace App\Http\Controllers;

use App\Models\Piutang;
use App\Models\Pemasukan;
use App\Models\Transaksi;
use Mike42\Escpos\Printer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mike42\Escpos\PrintConnectors\UsbPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class PiutangController extends Controller
{
    public function index()
    {
        $title = 'Kasbon';
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'kasbon', 'url' => route('piutang.index')],
            ['label' => 'Tabel Data', 'url' => null],
        ];
        $piutang = Piutang::with('penjualan', 'pembayaran')->orderBy('created_at', 'desc')->get();

        return view('piutang.index', compact('title', 'breadcrumbs', 'piutang'));
    }

    public function show($nobukti)
    {
        $piutang = Piutang::with('penjualan.outlet', 'penjualan.mutasi.bahanBaku.satuan', 'pembayaran.sumberDana')
            ->where('nobukti', $nobukti)
            ->firstOrFail();

        $title = 'Detail Piutang - ' . $piutang->nobukti;
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Piutang', 'url' => route('piutang.index')],
            ['label' => 'Detail', 'url' => null],
        ];

        return view('piutang.show', compact('piutang', 'title', 'breadcrumbs'));
    }

    public function bayar(Request $request, $nobukti)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:0',
            'posisi_kas' => 'required|in:Tunai,Bank BSI',
        ]);

        DB::beginTransaction();

        try {
            $piutang = Piutang::with('penjualan')->where('nobukti', $nobukti)->firstOrFail();

            // Validasi pembayaran tidak melebihi sisa piutang
            if ($request->jumlah > $piutang->sisa_piutang) {
                notify()->error('Pembayaran melebihi sisa piutang.');
                return redirect()->back();
            }

            // Get or create kategori penjualan
            $kategoriPenjualan = \App\Models\KategoriKeuangan::firstOrCreate(
                ['nama' => 'Penjualan BB'],
                ['jenis' => 'pemasukan']
            );

            // Simpan histori pembayaran piutang
            $piutang->pembayaran()->create([
                'nobukti' => $piutang->nobukti,
                'id_piutang' => $piutang->id,
                'tanggal' => now(),
                'jumlah' => $request->jumlah,
                'keterangan' => 'Pembayaran piutang outlet ' . ($piutang->penjualan->outlet->nama ?? ''),
            ]);

            // Update sisa piutang
            $piutang->update([
                'sisa_piutang' => $piutang->sisa_piutang - $request->jumlah
            ]);

            // Catat Pemasukan
            Pemasukan::create([
                'nobukti' => $piutang->nobukti,
                'tanggal' => now(),
                'jumlah' => $request->jumlah,
                'deskripsi' => 'outlet ' . ($piutang->penjualan->outlet->nama ?? '') . ' - ' . ($piutang->penjualan->outlet->penanggung_jawab ?? ''),
                'id_kategori_keuangan' => $kategoriPenjualan->id,
                'posisi_kas' => $request->posisi_kas,
            ]);

            // Catat Transaksi
            Transaksi::create([
                'nobukti' => $piutang->nobukti . '-' . now()->format('YmdHis'),
                'tanggal' => now(),
                'jumlah' => $request->jumlah,
                'deskripsi' => 'outlet ' . ($piutang->penjualan->outlet->nama ?? '') . ' - ' . ($piutang->penjualan->outlet->penanggung_jawab ?? ''),
                'id_kategori_keuangan' => $kategoriPenjualan->id,
                'posisi_kas' => $request->posisi_kas,
                'status' => 1,
            ]);

            // Cek pelunasan
            if ($piutang->sisa_piutang <= 0) {
                $piutang->update(['status' => 'lunas']);
                $piutang->penjualan->update([
                    'status_pembayaran' => 'lunas',
                ]);
            }

            DB::commit();
            notify()->success('Pembayaran berhasil dicatat.');
            return redirect()->route('piutang.index');
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error('Gagal menyimpan pembayaran: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Print invoice for thermal printer
     */
    // public function printInvoice($nobukti)
    // {
    //     try {
    //         $piutang = Piutang::with('penjualan.outlet', 'penjualan.mutasi.bahanBaku.satuan', 'pembayaran.sumberDana')
    //             ->where('nobukti', $nobukti)
    //             ->firstOrFail();

    //         // Get printer connector based on configuration
    //         $connector = $this->getPrinterConnector();

    //         $printer = new Printer($connector);

    //         // Get configuration
    //         $config = config('thermal-printer.invoice');
    //         $paperWidth = $config['paper_width'];

    //         // Print header
    //         $printer->setJustification(Printer::JUSTIFY_CENTER);
    //         $printer->setTextSize(2, 2);
    //         $printer->text($config['store_name'] . "\n");
    //         $printer->setTextSize(1, 1);
    //         $printer->text("INVOICE PIUTANG\n");
    //         $printer->text(str_repeat("-", $paperWidth) . "\n");

    //         // Outlet info
    //         $printer->setJustification(Printer::JUSTIFY_LEFT);
    //         $printer->text("Outlet: " . ($piutang->penjualan->outlet->nama ?? 'N/A') . "\n");
    //         $printer->text("No. Bukti: " . $piutang->nobukti . "\n");
    //         $printer->text("Tanggal: " . $piutang->penjualan->tanggal->format('d/m/Y') . "\n");
    //         $printer->text("Jatuh Tempo: " . $piutang->jatuh_tempo->format('d/m/Y') . "\n");
    //         $printer->text(str_repeat("-", $paperWidth) . "\n");

    //         // Products
    //         $printer->text("PRODUK:\n");
    //         foreach ($piutang->penjualan->mutasi as $item) {
    //             $namaProduk = $item->bahanBaku->nama ?? 'Produk Dihapus';
    //             $qty = $item->quantity . ' ' . ($item->bahanBaku->satuan->nama ?? '');
    //             $harga = 'Rp ' . number_format($item->harga, 0, ',', '.');
    //             $subtotal = 'Rp ' . number_format($item->sub_total, 0, ',', '.');

    //             // Truncate long product names to fit paper width
    //             $namaProduk = strlen($namaProduk) > $paperWidth ? substr($namaProduk, 0, $paperWidth - 3) . '...' : $namaProduk;

    //             $printer->text($namaProduk . "\n");
    //             $printer->text($qty . " x " . $harga . "\n");
    //             $printer->text("= " . $subtotal . "\n");
    //         }

    //         $printer->text(str_repeat("-", $paperWidth) . "\n");

    //         // Totals
    //         $totalDibayar = $piutang->pembayaran->sum('jumlah');
    //         $sisa = $piutang->jumlah_piutang - $totalDibayar;

    //         $printer->setJustification(Printer::JUSTIFY_RIGHT);
    //         $printer->text("Total Piutang: Rp " . number_format($piutang->jumlah_piutang, 0, ',', '.') . "\n");
    //         $printer->text("Sudah Dibayar: Rp " . number_format($totalDibayar, 0, ',', '.') . "\n");
    //         $printer->text("Sisa Piutang: Rp " . number_format($sisa, 0, ',', '.') . "\n");

    //         // Status
    //         $printer->setJustification(Printer::JUSTIFY_CENTER);
    //         $status = $piutang->status === 'lunas' ? 'LUNAS' : 'BELUM LUNAS';
    //         $printer->text("\nStatus: " . $status . "\n");

    //         // Payment history if exists
    //         if ($piutang->pembayaran->count() > 0) {
    //             $printer->text(str_repeat("-", $paperWidth) . "\n");
    //             $printer->text("RIWAYAT PEMBAYARAN:\n");
    //             foreach ($piutang->pembayaran as $pembayaran) {
    //                 $printer->text($pembayaran->tanggal->format('d/m/Y') . "\n");
    //                 $printer->text("Rp " . number_format($pembayaran->jumlah, 0, ',', '.') . "\n");
    //             }
    //         }

    //         // Footer
    //         $printer->text(str_repeat("-", $paperWidth) . "\n");
    //         $printer->setJustification(Printer::JUSTIFY_CENTER);
    //         $printer->text($config['footer_message'] . "\n");
    //         $printer->text(now()->format('d/m/Y H:i:s') . "\n");

    //         // Cut paper
    //         $printer->cut();

    //         // Close printer
    //         $printer->close();

    //         notify()->success('Invoice berhasil dikirim ke printer!');
    //         return redirect()->back();

    //     } catch (\Exception $e) {
    //         notify()->error('Gagal mencetak invoice: ' . $e->getMessage());
    //         return redirect()->back();
    //     }
    // }

    // /**
    //  * Get printer connector based on configuration
    //  */
    // private function getPrinterConnector()
    // {
    //     $defaultConnection = config('thermal-printer.default', 'network');
    //     $connections = config('thermal-printer.connections');

    //     switch ($defaultConnection) {
    //         case 'network':
    //             $config = $connections['network'];
    //             return new NetworkPrintConnector($config['host'], $config['port']);

    //         case 'windows':
    //             $config = $connections['windows'];
    //             return new WindowsPrintConnector($config['printer_name']);

    //         case 'usb':
    //             $config = $connections['usb'];
    //             if (isset($config['vendor_id']) && isset($config['product_id'])) {
    //                 return new UsbPrintConnector($config['vendor_id'], $config['product_id']);
    //             }
    //             // Fall through to file connector

    //         case 'file':
    //         default:
    //             // For testing or when no printer is configured
    //             return new FilePrintConnector("php://stdout");
    //     }
    // }
}
