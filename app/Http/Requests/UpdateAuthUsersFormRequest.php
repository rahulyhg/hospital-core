<?php

namespace App\Http\Requests;

class UpdateAuthUsersFormRequest extends ApiFormRequest
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
            'fullname'              => 'required|string',
            'email'                 => 'required|email',
            'khoa'                  => 'required|string',
            'chuc_vu'               => 'required|string'
        ];
    }
}
