<?php

namespace App\Http\Requests;

class ThanhToanVienPhiFormRequest extends ApiFormRequest
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
            'loai_thanh_toan_moi'                  => 'int|required',
            'ly_do_thay_loai_thanh_toan'           => 'string|required',
            'nguoi_chuyen_loai_thanh_toan'         => 'int|required',
        ];
    }
}