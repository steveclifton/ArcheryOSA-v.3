<?php

namespace App\Http\Requests\Auth\EventRegistration;

use App\Models\Event;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateRegistration extends FormRequest
{

    public function authorize()
    {
        if (Auth::check()) {
            return true;
        }
        return false;
    }


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return
     */
    public function rules()
    {
        $event = Event::where('eventurl', $this->eventurl)->get()->first();

        if (empty($event)) {
            return false;
        }

        $dobstate = 'nullable|date';
        if ($event->dateofbirth ?? false) {
            $dobstate = 'required|date';
        }
        $clubrequired = 'nullable';
        if (!empty($event->clubrequired)) {
            $clubrequired = 'required';
        }

        $schoolrequired = 'nullable';
        if (!empty($event->schoolrequired)) {
            $schoolrequired = 'required';
        }

        $membershiprequired = 'nullable';
        if (!empty($event->membershiprequired)) {
            $membershiprequired = 'required';
        }

        return [
            'eventid'        => 'required',
            'userid'         => 'required',
            'firstname'      => 'required',
            'lastname'       => 'required',
            'email'          => 'nullable',
            'membership'     => $membershiprequired,
            'phone'          => 'nullable',
            'address'        => 'nullable',
            'notes'          => 'nullable',
            'clubid'         => $clubrequired,
            'schoolid'       => $schoolrequired,
            'gender'         => 'nullable',
            'pickup'         => 'nullable',
            'roundids'       => 'required',
            'country'        => 'required',
            'divisionid'     => 'required',
            'dateofbirth'    => $dobstate,
            'bib'            => 'nullable',
            'individualqualround' => 'nullable',
            'teamqualround' => 'nullable',
            'individualfinal' => 'nullable',
            'teamfinal' => 'nullable',
            'mixedteamfinal' => 'nullable',
            'subclass' => 'nullable'

        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'firstname.required'   => 'Firstname is required',
            'lastname.required'    => 'Lastname is required',
            'email.required'       => 'Email address is required',
            'dateofbirth.required' => 'Date of Birth is required',
            'divisionid.required'  => 'Division is required',
            'membership.required' => 'Membership is required',
        ];
    }
}
