<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteQrCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nombres'=>'required|max:300',
            'apellidopaterno'=>'required|max:300',
            'apellidomaterno'=>'required|max:300',
            'email'=>'required|email|max:150',
            'dni'=>'required|min:8|max:8',
            'telefono'=>'required|digits:9',
            'terminos'=>'required',
        ];
    }
}
