<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\APIRequest;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class AccountCreateRequest extends APIRequest
{
    public function rules()
    {
        $id=$this->get('id');
        $account_type=$this->get('account_type');
        return [
            'account_type'=>'required',
            'name'=>'required',
            'phone_number' => [
                'required',
                $id ? null : Rule::unique('accounts')->where(function ($query) use ($account_type) {
                    return $query->where('account_type', $account_type);
                }),
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
