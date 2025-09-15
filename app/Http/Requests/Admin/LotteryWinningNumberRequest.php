<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class LotteryWinningNumberRequest extends FormRequest
{
    public function rules()
    {
        $id=$this->get('id');
        return [
            'name'=>'required',
            'phone_number' => [
                'required',
                $id ? null : Rule::unique('contacts'),
            ],
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
