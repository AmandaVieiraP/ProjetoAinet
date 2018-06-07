<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StoreAccount extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code'=> ['required', Rule::unique('accounts')->where(function ($query) {
                return $query->where('owner_id', Auth::id());
            })],
            'account_type_id'=>['required', 'exists:account_types,id',Rule::unique('accounts')->where(function ($query) {
                return $query->where('owner_id', Auth::id());
            })],
            'start_balance' => 'required|numeric',
            'description'=>'nullable|string',
            'date'=>'required|date',
        ];
    }

    public function messages()
    {
        return [
            'account_type_id.required' => 'The account type can not be empty',
            'account_type_id.exists' => 'The type choosen is not valid',
            'code.required' => 'The code can not be empty',
            'date.required' => 'The date field can not be empty',
            'date.date' => 'The date is invalid',
            'start_balance.required'=> 'The start balance value can not be empty',
        ];
    }
}
