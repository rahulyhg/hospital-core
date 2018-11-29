<?php

namespace App\Http\Requests;

class DanhMucDichVuFormRequest extends ApiFormRequest
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
            'ten_nhom'              => 'required|string',
            'loai_nhom'             => 'required|int',
            'ma'                    => 'required|string',
            'ma_nhom_bhyt'          => 'nullable|string',
            'don_vi_tinh'           => 'nullable|string',
            'trang_thai'            => 'required|int',
            'ten'                   => 'required|string',
            'ten_nhan_dan'          => 'nullable|string',
            'ten_bhyt'              => 'nullable|string',
            'ten_nuoc_ngoai'        => 'nullable|string',
            'gia'                   => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'gia_nhan_dan'          => 'nullable|regex:/^\d*(\.\d{1,2})?$/',
            'gia_bhyt'              => 'nullable|regex:/^\d*(\.\d{1,2})?$/',
            'gia_nuoc_ngoai'        => 'nullable|regex:/^\d*(\.\d{1,2})?$/'
        ];
    }
}
