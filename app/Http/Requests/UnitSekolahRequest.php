<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnitSekolahRequest extends FormRequest
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
        return [
            'nama' => 'required|string|max:255',
            'jenjang' => 'required|in:TK,KB,SD,SMP,SMA,Pondok',
            'alamat' => 'required|string',
            'desa' => 'required|string',
            'kecamatan' => 'required|string',
            'kota' => 'required|string',
            'provinsi' => 'required|string',
            'kode_pos' => 'required|string',
            'status' => 'required|in:aktif,non-aktif',
            'tanggal_berdiri' => 'nullable|date',
            'kepala_sekolah_id' => 'nullable|string',
            'whatsapp' => 'required|string',
            'telepon' => 'required|string',
            'email' => 'nullable|email',
            'logo' => 'nullable|image|mimes:jpeg,webp,png,jpg,gif,svg|max:2048',
        ];
    }
}
