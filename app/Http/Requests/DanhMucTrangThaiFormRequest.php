<?php

namespace App\Http\Requests;

class DanhMucTrangThaiFormRequest extends ApiFormRequest
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
        \Validator::extend( 'composite_unique', function ( $attribute, $value, $parameters, $validator ) {
                
                // remove first parameter and assume it is the table name
                $table = array_shift( $parameters ); 

                // start building the conditions
                $fields = [ $attribute => $value ];

                // iterates over the other parameters and build the conditions for all the required fields
                while ( $field = array_shift( $parameters ) ) {
                    $fields[ $field ] = $this->get( $field );
                }

                // query the table with all the conditions
                $result = \DB::table( $table )->select( \DB::raw( 1 ) )->where( $fields )->first();

                return empty( $result ); // edited here
            }, 'Cặp trường khóa và giá trị vừa nhập đã có trong Cơ sở dữ liệu, vui lòng thay đổi 1 trong 2 trường!' );
            
        return [
            'khoa'      => 'required|string|composite_unique:danh_muc_trang_thai,gia_tri',
            'gia_tri'   => 'required|string',
            'dien_giai' => 'required|string'
        ];
    }
}
