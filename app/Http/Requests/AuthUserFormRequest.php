<?php

namespace App\Http\Requests;

class AuthUserFormRequest extends ApiFormRequest
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
            'email'                 => 'required|unique:auth_users|email',
            'password'              => 'required|string',
            'khoa'                  => 'required|string',
            'chuc_vu'               => 'required|string'
        ];
    }
}
