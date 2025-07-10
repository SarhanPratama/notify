<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PenjualanRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // \Log::info('StorePembelianRequest rules() dipanggil');
        return [
            'tanggal' => 'required',
            'id_cabang' => 'required|exists:cabang,id',
            'id_sumber_dana' => 'required|exists:sumber_dana,id',
            'metode_pembayaran' => 'required|in:tunai,kasbon',
            'bahanBaku' => 'required|array',
            'bahanBaku.*' => 'required|exists:bahan_baku,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
            'harga' => 'required|array',
            'harga.*' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ];
    }
}
