<?php

namespace App\Http\Requests;



class RegisterFormRequest extends ApiFormRequest
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
            'name' => 'required|string|unique:auth_users',
            'email' => 'required|email|unique:auth_users',
            'password' => 'required|string|min:6|max:10',
        ];
    }
}
