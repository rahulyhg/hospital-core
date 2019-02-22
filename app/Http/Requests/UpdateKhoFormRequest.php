<?php

namespace App\Http\Requests;

class UpdateKhoFormRequest extends ApiFormRequest
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
            'ten_kho'                   => 'required|string',
            'ky_hieu'                   => 'required|string',
            'kho_cha_id'                => 'nullable|int',
            'nhap_tu_ncc'               => 'required|boolean',
            'duoc_ban'                  => 'required|boolean',
            'tu_truc'                   => 'required|boolean'
        ];
    }
}
