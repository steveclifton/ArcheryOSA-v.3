<?php

namespace App\Http\Controllers\Ranking;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RankingController extends Controller
{
    public function getCountryRankings(Request $request)
    {
        return view('rankings.rankings');
    }
}
