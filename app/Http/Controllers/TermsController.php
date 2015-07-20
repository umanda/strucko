<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Term;
use App\PartOfSpeech;

class TermsController extends Controller
{

    public function index()
    {
        $terms = Term::all();

        return view('terms.index', compact('terms'));
    }
    
    public function create()
    {
        $partOfSpeeches = PartOfSpeech::all();
        return view('terms.create', compact('partOfSpeeches'));
    }

    public function show($slugUnique)
    {
        $term = Term::where('slug_unique', $slugUnique)->firstOrFail();
        
        return $term;
    }

}
