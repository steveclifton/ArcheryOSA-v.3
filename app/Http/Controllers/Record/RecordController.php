<?php

namespace App\Http\Controllers\Record;

use App\Models\Record;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RecordController extends Controller
{
    public function getCountryRecords(Request $request)
    {
        $records = Record::get();

        if (empty($records)) {
            return redirect();
        }
        return view('records.records', compact('records'));
    }
}
