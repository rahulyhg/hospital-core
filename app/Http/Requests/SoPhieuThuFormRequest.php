<?php

namespace App\Http\Requests;

class SoPhieuThuFormRequest extends ApiFormRequest
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
            'ma_so'                 => 'string|required',
            'loai_so'               => 'int|required',
            'trang_thai'            => 'int|required',
            'nguoi_lap'             => 'int|nullable',
            'hinh_thuc_thanh_toan'  => 'int|nullable',
        ];
    }
}