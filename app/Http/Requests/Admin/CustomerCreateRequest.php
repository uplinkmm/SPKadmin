<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\APIRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;

class CustomerCreateRequest extends APIRequest
{
    public function rules()
    {
        $id=$this->get('id');
        return [
            'name'=>'required',
            'phone_number' => [
                'required',
                $id ? null : Rule::unique('customers'),
            ],
            'password'=>'required|confirmed|min:6',
        ];
    }
    public function authorize()
    {
        return parent::authorize();
    }

    public function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator);
    }
}
