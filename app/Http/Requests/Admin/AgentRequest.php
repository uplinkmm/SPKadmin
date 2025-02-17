<?php

namespace App\Http\Requests\Admin;

use App\Models\Agent;
use Illuminate\Validation\Rule;
use App\Http\Requests\APIRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class AgentRequest extends APIRequest
{
    public function rules()
    {
        $id = $this->get('id');
        $newPassword=$this->get('password');
        $haveNewPassword=false;
        if($id){
            if($newPassword!=null){
                $haveNewPassword=true;
            }
        }
        return [
            'name' => 'required',
            'phone_number' => [
                'required',
                Rule::unique('agents')->ignore($id), // Ignore the current record when checking uniqueness
            ],
            'code' => [
                'required',
                Rule::unique('agents')->ignore($id), // Ignore the current record when checking uniqueness
            ],
            // 'password' => 'required|min:6',
            'new_password' => $id ?  'required|min:6' : 'nullable',
            'pass'
            // 'password' => $id ? 'nullable|min:6' : 'required|min:6',
            // 'old_password' => [
            //     'nullable', // Allow this to be null if not provided
            //     function ($attribute, $value, $fail) use ($id) {
            //         if ($value !== null) {
            //             // Retrieve the current user's password
            //             $user = Agent::find($id);
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
