<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\APIRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;

class DrawCreateRequest extends APIRequest
{
    public function rules()
    {
        $id=$this->get('id');
        return [
            'name'=>'required',
            'opening_date_time'=>'required',
            'closing_date_time'=>'required',
            'lottery_date_time'=>'required',
            'limitation_quantity'=>'required|numeric',
            'price'=>'required|numeric',
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
