<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Term;
use DB;

class PagesController extends Controller
{
    public function getHome()
    {
        $categories = Term::approved()
                ->select(DB::raw('count(*) as count'), 'language_id', 'scientific_field_id')
                ->groupBy('language_id', 'scientific_field_id')
                ->orderBy('count', 'DESC')
                ->take(4)
                ->with('language', 'scientificField')
                ->get();
                
        return view('pages.home', compact('categories'));
    }
}
