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
            'khoa'      => 'required|string',
            'gia_tri'   => 'required|string',
            'dien_giai' => 'nullable|string'
        ];
    }
}
