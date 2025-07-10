<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePembelianRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // \Log::info('StorePembelianRequest rules() dipanggil');
        return [
            'tanggal' => 'required|date',
            'id_supplier' => 'nullable|exists:supplier,id',
            'id_sumber_dana' => 'required|exists:sumber_dana,id',
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
