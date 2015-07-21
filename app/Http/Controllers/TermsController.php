<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Term;
use App\PartOfSpeech;
use App\ScientificBranch;
use App\Language;

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
        $scientificBranches = ScientificBranch::all();
        $languages = Language::all();
        return view('terms.create', compact(
                'partOfSpeeches', 
                'scientificBranches',
                'languages'
                ));
    }

    public function show($slugUnique)
    {
        $term = Term::where('slug_unique', $slugUnique)->firstOrFail();
        
        return $term;
    }

}
