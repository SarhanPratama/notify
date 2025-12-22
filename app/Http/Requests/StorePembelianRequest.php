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
        // Cek apakah ini dari session cart (edit form) atau dari create form
        $isFromCreateForm = $this->has('bahanBaku');

        if ($isFromCreateForm) {
            // Validasi untuk create form (format array)
            return [
                'id_supplier' => 'nullable|exists:supplier,id',
                'bahanBaku' => 'required|array',
                'bahanBaku.*' => 'required|exists:bahan_baku,id',
                'quantity' => 'required|array',
                'quantity.*' => 'required|integer|min:1',
                'harga' => 'required|array',
                'harga.*' => 'required|numeric|min:0',
                'catatan' => 'nullable|string',
            ];
        }

        // Validasi untuk edit form (menggunakan session cart)
        return [
            'id_supplier' => 'nullable|exists:supplier,id',
            'catatan' => 'nullable|string',
        ];
    }
}
