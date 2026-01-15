<?php

namespace App\Http\Requests\Admin;

use App\Models\Customer;
use Illuminate\Validation\Rule;
use App\Http\Requests\APIRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class CustomerVerifyRequest extends APIRequest
{
    public function rules()
    {
        $id = $this->get('id');
        return [
            'customer_id' => 'required|exists:customers,id',
            'password' => ['required', 'min:6', 'confirmed'],
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
