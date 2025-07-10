<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Cabang;
use App\Models\Mutasi;
use App\Models\BahanBaku;
use App\Models\Penjualan;
use App\Models\SumberDana;
use Illuminate\Http\Request;
use App\Models\CustomerInteraction;
use Illuminate\Support\Facades\Http;

class WhatsAppWebhookController extends Controller
{
    public function verifyWebhook(Request $request)
    {
        $verify_token = 'sarhan123';

        Log::info('Verification Attempt', [
            'query' => $request->query(),
            'headers' => $request->headers->all()
        ]);

        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        if ($mode === 'subscribe' && $token === $verify_token) {
            return response($challenge, 200)
                ->header('Content-Type', 'text/plain');
        }

        return response('Verification failed', 403);
    }

    public function handleIncoming(Request $request)
    {
        // Simpan payload mentah untuk debugging
        $rawPayload = $request->getContent();
        Log::info('WhatsApp Webhook Received', ['raw_payload' => $rawPayload]);

        try {
            // Dekode payload JSON
            $payload = json_decode($rawPayload, true);

            // Validasi struktur payload
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Invalid JSON payload', ['error' => json_last_error_msg()]);
                return response()->json(['status' => 'invalid json'], 400);
            }

            // Validasi struktur dasar payload
            if (!isset($payload['entry'][0]['changes'][0]['value']['messages'][0])) {
                Log::error('Invalid payload structure', ['payload' => $payload]);
                return response()->json(['status' => 'invalid payload structure'], 400);
            }

            $message = $payload['entry'][0]['changes'][0]['value']['messages'][0];
            $phoneNumber = $message['from'] ?? null;
            $body = $message['text']['body'] ?? '';

            Log::info('Processed message', [
                'phone' => $phoneNumber,
                'body' => $body
            ]);

            if (!$phoneNumber || empty($body)) {
                Log::warning('Missing phone or body content');
                return response()->json(['status' => 'ignored'], 200);
            }

            // Normalisasi nomor untuk database
            $cleanNumber = str_replace('@c.us', '', $phoneNumber);
            $normalizedPhone = $this->normalizePhone($cleanNumber);

            // Cek apakah ini interaksi pertama
            $isFirstInteraction = $this->checkFirstInteraction($normalizedPhone);

            // Handle pesan HALO dan DAFTAR
            $command = strtoupper(trim($body));

            if ($isFirstInteraction || $command === 'HALO' || in_array($command, ['DAFTAR', 'LIST', 'STOK', 'MENU'])) {
                Log::info('Command detected', ['command' => $command]);

                // Tambahkan penundaan untuk memastikan urutan pesan
                sleep(1);
                $this->sendBahanList($phoneNumber);

                return response()->json(['status' => 'success']);
            }

            // Proses pemesanan biasa
            $cabang = Cabang::where('telepon', $normalizedPhone)->first();

            if (!$cabang) {
                Log::warning('Outlet not found', ['phone' => $normalizedPhone]);
                $this->sendWhatsAppReply($phoneNumber, "❌ Outlet tidak terdaftar");
                return response()->json(['status' => 'error'], 400);
            }

            $items = $this->parseMessage($body);
            Log::info('Parsed items', ['items' => $items]);

            $penjualan = Penjualan::create([
                'tanggal' => now(),
                'catatan' => 'Pemesanan via WhatsApp',
                'id_cabang' => $cabang->id,
            ]);

            $total = 0;

            foreach ($items as $item) {
                $bahan = BahanBaku::where('nama', $item['nama'])->first();

                if (!$bahan) {
                    throw new \Exception("Bahan baku {$item['nama']} tidak ditemukan");
                }

                $subtotal = $bahan->harga * $item['qty'];
                $total += $subtotal;

                $penjualan->mutasi()->create([
                    'id_bahan_baku' => $bahan->id,
                    'quantity' => $item['qty'],
                    'harga' => $bahan->harga,
                    'sub_total' => $subtotal,
                    'jenis_transaksi' => 'K',
                    'status' => '0',
                ]);
            }

            $penjualan->update(['total' => $total]);

            $sumberDana = SumberDana::find(1);
            if (!$sumberDana) {
                throw new \Exception("Sumber dana tidak ditemukan");
            }

            $penjualan->transaksi()->create([
                'id_sumber_dana' => $sumberDana->id,
                'tanggal' => $penjualan->tanggal,
                'tipe' => 'debit',
                'jumlah' => $total,
                'deskripsi' => 'Penjualan bahan baku #' . $penjualan->nobukti,
            ]);

            $sumberDana->increment('saldo_current', $total);

            $detailPesanan = "📦 *Rincian Pesanan:*\n";
            foreach ($items as $item) {
                $bahan = BahanBaku::where('nama', $item['nama'])->first();
                if ($bahan) {
                    $subtotal = $bahan->harga * $item['qty'];
                    $harga = number_format($bahan->harga, 0, ',', '.');
                    $sub = number_format($subtotal, 0, ',', '.');
                    $detailPesanan .= "- *{$item['nama']}* ({$item['qty']} {$item['unit']} x Rp {$harga}) = Rp {$sub}\n";
                }
            }

            $this->sendWhatsAppReply(
                $phoneNumber,
                "✅ Pemesanan diterima!\n"
                    . "No. Bukti: {$penjualan->nobukti}\n\n"
                    . $detailPesanan . "\n"
                    . "💰 *Total: Rp " . number_format($total, 0, ',', '.') . "*\n"
                    . "Silakan lakukan pembayaran ke rekening BCA 1234567890 atau langsung bayar ke gudang"
            );

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('HandleIncoming error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    private function checkFirstInteraction(string $phone): bool
    {
        try {
            $interaction = CustomerInteraction::firstOrCreate(
                ['phone_number' => $phone],
                ['is_first_interaction' => true]
            );

            if ($interaction->is_first_interaction) {
                $interaction->update(['is_first_interaction' => false]);
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('CheckFirstInteraction error: ' . $e->getMessage());
            return false;
        }
    }

    private function sendBahanList(string $to)
    {
        Log::info('Sending bahan list to: ' . $to);

        try {
            $bahanList = BahanBaku::with('satuan', 'ViewStok')->get();

            if ($bahanList->isEmpty()) {
                $this->sendWhatsAppReply($to, "📋 Daftar bahan baku kosong.");
                return;
            }

            // Buat header
            $message = "📦 *Daftar Bahan Baku & Stok Saat Ini:*\n\n";
            $counter = 1;

            // Tambahkan semua bahan dalam satu pesan
            foreach ($bahanList as $bahan) {
                $satuan = $bahan->satuan->nama ?? 'pcs';
                $stok = $bahan->ViewStok->stok_akhir ?? 0;
                $harga = number_format($bahan->harga ?? 0, 0, ',', '.');
                $message .= "{$counter}. {$bahan->nama} ({$stok} {$satuan}) - Rp {$harga}/{$satuan}\n";
                $counter++;
            }

            // Tambahkan footer
            $message .= "\n📝 Untuk memesan, gunakan format:\n[nama bahan]: [jumlah]\nContoh:\nTepung: 10\nMinyak: 5\n";

            // Kirim semua dalam satu pesan
            $this->sendWhatsAppReply($to, $message);
        } catch (\Exception $e) {
            Log::error('Error sending bahan list: ' . $e->getMessage());
            $this->sendWhatsAppReply($to, "❌ Gagal mengirim daftar bahan. Silakan coba lagi.");
        }
    }

    private function parseMessage(string $message): array
    {
        $items = [];
        $lines = explode("\n", trim($message));

        foreach ($lines as $line) {
            if (preg_match('/(.+?)\s*:\s*(\d+)(?:\s*([a-zA-Z]+))?/i', $line, $matches)) {
                $items[] = [
                    'nama' => trim($matches[1]),
                    'qty' => (int)$matches[2],
                    'unit' => $matches[3] ?? 'pcs'
                ];
            }
        }

        if (empty($items)) {
            throw new \Exception("Format pesan tidak valid. Gunakan format:\nNama Bahan: Jumlah\nContoh:\nTepung: 10kg\nMinyak: 5lt");
        }

        return $items;
    }

    private function normalizePhone(string $phone): string
    {
        // Hapus semua karakter non-digit
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Jika diawali dengan '62', biarkan; jika diawali '0', ganti dengan '62'; selain itu tambahkan '62'
        if (substr($phone, 0, 2) === '62') {
            return $phone;
        } elseif (substr($phone, 0, 1) === '0') {
            return '62' . substr($phone, 1);
        } else {
            return '62' . $phone;
        }
    }

    private function sendWhatsAppReply(string $to, string $message)
    {
        $phoneNumberId = config('services.meta_wa.phone_number_id');
        $accessToken = config('services.meta_wa.token');

        $url = "https://graph.facebook.com/v18.0/{$phoneNumberId}/messages";

        try {
            Log::info('Sending message via Meta API', [
                'to' => $to,
                'message' => $message
            ]);

            $response = Http::withToken($accessToken)
                ->timeout(15) // Tambahkan timeout
                ->post($url, [
                    'messaging_product' => 'whatsapp',
                    'recipient_type' => 'individual',
                    'to' => $to,
                    'type' => 'text',
                    'text' => [
                        'body' => $message,
                        'preview_url' => false
                    ]
                ]);

            $responseBody = $response->body();
            $responseStatus = $response->status();

            Log::info('Meta API response', [
                'status' => $responseStatus,
                'body' => $responseBody
            ]);

            // Jika error dari Meta
            if ($responseStatus !== 200) {
                Log::error('Meta API error response', [
                    'status' => $responseStatus,
                    'body' => $responseBody
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Meta WhatsApp Error: " . $e->getMessage());
        }
    }
}
