<?php

namespace App\Http\Requests;



class DangKyKhamBenhFormRequest extends ApiFormRequest
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
            'ho_va_ten' => 'required|string',
            'ngay_sinh' => 'required|date_format:Y-m-d',
            'gioi_tinh_id' => 'required',
            'yeu_cau_kham_id' => 'required',
            'tinh_thanh_pho_id' => 'required',
            'quan_huyen_id' => 'required',
            'phuong_xa_id' => 'required',
            'phong_id' => 'required',//|string|regex:/^[a-zA-Z]+$/u',
            'khoa_id' => 'required',
            'yeu_cau_kham_id' => 'required',
            //nếu có bh check bh so vs ngày hiện tại
        ];
    }
}
