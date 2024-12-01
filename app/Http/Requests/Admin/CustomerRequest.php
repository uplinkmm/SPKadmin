<?php

namespace App\Http\Requests\Admin;

use App\Models\Customer;
use Illuminate\Validation\Rule;
use App\Http\Requests\APIRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class CustomerRequest extends APIRequest
{
    public function rules()
    {
        $id = $this->get('id');
        return [
            'name' => 'required',
            'phone_number' => [
                'required',
                $id ? null : Rule::unique('customers'),
            ],
            // 'password' => 'required|confirmed|min:6',
            'password' => $id ? 'nullable|confirmed|min:6' : 'required|confirmed|min:6',
            // 'old_password' => [
            //     'nullable', // Allow this to be null if not provided
            //     function ($attribute, $value, $fail) use ($id) {
            //         if ($value !== null) {
            //             // Retrieve the current user's password
            //             $user = Customer::find($id);
            //             if (!$user || !Hash::check($value, $user->password)) {
            //                 $fail('The old password is incorrect.');
            //             }
            //         }
            //     },
            // ],
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
