<?php

namespace App\Http\Requests;

class UpdateHsbaFormRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ho_va_ten'             => 'required|string',
            'ngay_sinh'             => 'required|date_format:Y-m-d',
            'gioi_tinh_id'          => 'required|int',
            'nghe_nghiep_id'        => 'required|int',
            'dan_toc_id'            => 'required|int',
            'quoc_tich_id'          => 'required|int',
            'email_benh_nhan'       => 'nullable|email',
            'dien_thoai_benh_nhan'  => 'nullable|string',
            'dia_chi_lien_he'       => 'nullable|string',
            'noi_lam_viec'          => 'nullable|string',
            'loai_vien_phi'         => 'nullable|int',
            'doi_tuong_benh_nhan'   => 'nullable|int',
            'ms_bhyt'               => 'nullable|string',
            'ma_cskcbbd'            => 'nullable|string',
            'tu_ngay'               => 'nullable|date_format:Y-m-d',
            'den_ngay'              => 'nullable|date_format:Y-m-d'
        ];
    }
}
