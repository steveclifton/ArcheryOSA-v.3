<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateRound extends FormRequest
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
            'label'          => 'required|max:155|unique:rounds,label,'. $this->roundid.',roundid',
            'organisationid' => 'nullable|integer',
            'type'           => 'required',
            'code'           => 'required|unique:rounds,code,'. $this->code.',code',
            'dist1'          => 'required|numeric',
            'dist1max'       => 'required|numeric',
            'dist2'          => 'nullable|numeric',
            'dist2max'       => 'nullable|numeric',
            'dist3'          => 'nullable|numeric',
            'dist3max'       => 'nullable|numeric',
            'dist4'          => 'nullable|numeric',
            'dist4max'       => 'nullable|numeric',
            'totalmax'       => 'required|numeric',
            'visible'        => 'nullable',
            'matchplay'      => 'nullable',
            'retired'        => 'nullable',
            'anz_record_id' => 'nullable',
            'anz_record_dist1_id' => 'nullable',
            'anz_record_dist2_id' => 'nullable',
            'anz_record_dist3_id' => 'nullable',
            'anz_record_dist4_id' => 'nullable',
        ];
    }


    /**
     * @return array
     */
    public function messages()
    {
        return [
            'label.required' => 'Round name is required',
            'label.unique'   => 'Round name must be unique',
            'code.required'  => 'Code is required',
            'code.unique'    => 'Code name must be unqiue',
            'dist1.required' => 'Distance 1 is required',
            'dist1max.required'=> 'Distance 1 max is required',
            'totalmax.required'=> 'Total max is required',
        ];
    }
}
