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
            'ho_va_ten'             => 'string',
            'ngay_sinh'             => 'date_format:Y-m-d',
            'gioi_tinh_id'          => 'int',
            'nghe_nghiep_id'        => 'int',
            'dan_toc_id'            => 'int',
            'quoc_tich_id'          => 'int',
            'email_benh_nhan'       => 'email',
            'dien_thoai_benh_nhan'  => 'string',
            'dia_chi_lien_he'       => 'string',
            'noi_lam_viec'          => 'string',
            'loai_vien_phi'         => 'int',
            'doi_tuong_benh_nhan'   => 'int',
            'ms_bhyt'               => 'string',
            'ma_cskcbbd'            => 'string',
            'tu_ngay'               => 'date_format:Y-m-d',
            'den_ngay'              => 'date_format:Y-m-d'
        ];
    }
}
