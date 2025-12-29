<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\APIRequest;
use Illuminate\Foundation\Http\FormRequest;

class GamePromotionRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'start_date'=>'required',
            'end_date'=>'required',
            'deposit_amount'=>'required|numeric',
            'promotion_percentage'=>'required|numeric|min:0|max:100',
        ];
    }
}
