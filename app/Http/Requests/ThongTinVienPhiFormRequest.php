<?php

namespace App\Http\Requests;

class ThongTinVienPhiFormRequest extends ApiFormRequest
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
            'vien_phi_id'                  => 'int|nullable',
        ];
    }
}