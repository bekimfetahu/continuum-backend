<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class ClientCreateRequest extends FormRequest
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
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'unique:clients'],
            'avatar' => ['mimes:jpeg,jpg,png,gif|required|max:10000'],
        ];
    }
}
