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
            'ten_benh_nhan'         => 'string',
            'ngay_sinh'             => 'date_format:Y-m-d',
            'gioi_tinh_id'          => 'int',
            'nghe_nghiep_id'        => 'string',
            'dan_toc_id'            => 'string',
            'quoc_tich_id'          => 'string',
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
