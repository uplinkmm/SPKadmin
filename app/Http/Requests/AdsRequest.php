<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;

class AdsRequest extends APIRequest
{
    public function rules()
    {
        $type=$this->get('type');
        if($type=='marquee' || $type=='ads'){
            return [
                'name' => [
                    'required',
                ]
            ];
        }
        if($type=='promotion'){
            return [
                'name' => [
                    'required',
                ],
                'body'=>['required'],
            ];
        }
       
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
