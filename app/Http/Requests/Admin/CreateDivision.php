<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateDivision extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::check() && Auth::user()->roleid <= 2) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'label'          => 'required|string|max:155|unique:divisions,label',
            'organisationid' => 'nullable|integer',
            'code'           => 'required|unique:divisions,code',
            'description'    => 'nullable',
            'visible'        => 'nullable',

        ];
    }


    /**
     * @return array
     */
    public function messages()
    {
        return [
            'label.required' => 'Division name is required',
            'label.unique'   => 'Division name must be unique',
            'code.required'  => 'Code is required',
            'code.unique'    => 'Code name must be unqiue',
        ];
    }


}
