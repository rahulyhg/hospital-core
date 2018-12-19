<?php

namespace App\Http\Requests;

class SoThuNganFormRequest extends ApiFormRequest
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
            'ma_so'                 => 'string|nullable',
            'mode'                  => 'int|nullable',
            'da_khoa'               => 'int|nullable',
            'loai_so'               => 'int|nullable',
            'nguoi_lap'             => 'int|nullable',
            'tong_so_phieu_thu'     => 'int|nullable',
            'so_phieu_su_dung'      => 'int|nullable',
            'so_phieu_from'         => 'int|nullable',
            'so_phieu_to'           => 'int|nullable',
            'ghi_chu'               => 'string',
            'hinh_thuc_thanh_toan'  => 'int|nullable',
        ];
    }
}