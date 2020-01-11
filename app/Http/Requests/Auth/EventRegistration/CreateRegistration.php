<?php

namespace App\Http\Requests\Auth\EventRegistration;

use App\Models\Event;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateRegistration extends FormRequest
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

        if ($event->isnonshooting()) {
            return [
                'userid'         => 'nullable',
                'eventid'        => 'required',
                'firstname'      => 'required',
                'lastname'       => 'required',
                'email'          => 'nullable',
                'membership'     => 'nullable',
                'phone'          => 'nullable',
                'address'        => 'nullable',
                'notes'          => 'nullable',
                'paymenttype'    => 'nullable',
            ];
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

        $mqs = 'nullable';
        if (!empty($event->mqs)) {
            $mqs = 'required';
        }

        $waver = 'nullable';
        if (!empty($event->waver)) {
            $waver = 'required';
        }

        return [
            'userid'         => 'nullable',
            'eventid'        => 'required',
            'firstname'      => 'required',
            'lastname'       => 'required',
            'email'          => 'nullable',
            'membership'     => $membershiprequired,
            'phone'          => 'nullable',
            'address'        => 'nullable',
            'notes'          => 'nullable',
            'clubid'         => $clubrequired,
            'schoolid'       => $schoolrequired,
            'gender'         => 'required',
            'pickup'         => 'nullable',
            'country'        => 'required',
            'roundids'       => 'nullable',
            'divisionid'     => 'required',
            'dateofbirth'    => $dobstate,
            'bib'            => 'nullable',
            'teamqualround'  => 'nullable',
            'teamfinal'      => 'nullable',
            'mixedteamfinal' => 'nullable',
            'subclass'       => 'nullable',
            'mqs'            => $mqs,
            'waver'          => $waver,
            'paymenttype'    => 'nullable',
            'individualfinal' => 'nullable',
            'eventcompetitionid' => 'nullable',
            'individualqualround' => 'nullable',
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
            'clubid.required'      => 'Club is required',
            'membership.required' => 'Membership is required',
        ];
    }
}
