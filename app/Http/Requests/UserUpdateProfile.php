<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateProfile extends FormRequest
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
            'dateofbirth'=> 'nullable|date|max:15',
            'phone'      => 'nullable|numeric',
            'address'    => 'nullable|string|max:155',
            'city'       => 'nullable|string|max:55',
            'postcode'   => 'nullable|numeric',
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
        ];
    }
}
