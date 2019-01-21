<?php

namespace App\Http\Requests;

class KhoaFormRequest extends ApiFormRequest
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
            'ma_khoa'       => 'required|string',
            'ten_khoa'      => 'required|string',
            'loai_khoa'     => 'int',
            'ma_khoa_byt'   => 'required|string',
            'benh_vien_id'  => 'int'
        ];
    }
}
