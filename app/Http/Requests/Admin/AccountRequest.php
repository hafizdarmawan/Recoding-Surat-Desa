<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
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
            'photo' => 'nullable|image|mimes:png,jpg,jpeg,gif,svg|max:2048',
            'name'  => 'required|min:5|max:255'
        ];
    }

    public function filters()
    {
        return  [
            'name'  => 'trim|escape|capitalize'
        ];
    }
}
