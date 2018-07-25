<?php

namespace App\Http\Controllers\Record;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RecordController extends Controller
{
    public function getCountryRecords(Request $request)
    {
        return view('records.records');
    }
}
