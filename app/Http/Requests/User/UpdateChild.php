<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChild extends FormRequest
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
            'firstname'  => 'required|max:55',
            'lastname'   => 'required|max:55',
            'email'      => 'nullable|max:155',
            'username'   => 'required',
            'dateofbirth'=> 'nullable',
            'membership' => 'nullable',
            'anzdivisionid' => 'nullable',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'firstname.required' => 'First name is required',
            'lastname.required'  => 'Last name is required',
            'username.required'  => 'Error with page'
        ];
    }
}
