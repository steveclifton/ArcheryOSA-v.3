<?php

namespace App\Http\Requests\Auth\Events;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateEvent extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::check() && Auth::user()->roleid <= 3) {
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
            'label'          => 'required|max:155|unique:events,label',
            'entryclose'     => 'nullable|date',
            'start'          => 'required|date',
            'end'            => 'required|date|after_or_equal:start',
            'contactname'    => 'nullable',
            'phone'          => 'nullable',
            'email'          => 'required|email',
            'location'       => 'nullable',
            'cost'           => 'nullable',
            'bankaccount'    => 'nullable',
            'bankreference'  => 'nullable',
            'schedule'       => 'nullable',
            'info'           => 'nullable',
            'organisationid' => 'nullable|integer',
            'clubid'         => 'nullable|integer',
            'visible'        => 'nullable',
            'eventtypeid'    => 'required',

        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'label.required' => 'Event name is required',
            'label.unique' => 'Event name must be unique',
            'end.after_or_equal' => 'Event finish date must be after the start date',
            'email.required' => 'Email address is required',
        ];
    }

}
