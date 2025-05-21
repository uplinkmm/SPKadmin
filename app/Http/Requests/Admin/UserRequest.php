<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Validation\Rule;
use App\Http\Requests\APIRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class UserRequest extends APIRequest
{
    public function rules()
    {
        $id = $this->get('id');
        return [
            'name' => 'required',
            'phone_number' => [
                'required',
                $id ? null : Rule::unique('users'),
            ],
            'username' => [
                'required',
                $id ? null : Rule::unique('users'),
            ],
            // 'password' => 'required|confirmed|min:6',
            'role'=>'required|string|in:super-admin,admin',
            'password' => $id ? 'nullable|confirmed|min:6' : 'required|confirmed|min:6',
            'old_password' => [
                'nullable', // Allow this to be null if not provided
                function ($attribute, $value, $fail) use ($id) {
                    if ($value !== null) {
                        // Retrieve the current user's password
                        $user = User::find($id);
                        if (!$user || !Hash::check($value, $user->password)) {
                            $fail('The old password is incorrect.');
                        }
                    }
                },
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
