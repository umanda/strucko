<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Term;
use App\PartOfSpeech;
use App\ScientificBranch;
use App\Language;
use App\Synonym;
use Request;

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
    /**
     * TODO: Rewrite the unique slug to include the language, part of speech and category name
     * in the correct language.
     * 
     * @return type
     */
    public function store()
    {
        // Get input from the request
        $input = Request::all();
        
        // Prepare new synonym and append synonym_id to the input
        $synonym = Synonym::create();
        $input['synonym_id'] = $synonym->id;
        
        // Prepare the slug and slug_unique
        $slug = str_limit(str_slug($input['term']), $limit = 100 );
        $input['slug'] = $slug;
        // Get the strings for language, partOfSpeech and category, for SEO.
        $language = Language::where('id', $input['language'])->firstOrFail();
        $partOfSpeech = PartOfSpeech::where('id', $input['part-of-speech'])->firstOrFail();
        $scientificBranch = ScientificBranch::where('id', $input['scientific-branch'])->firstOrFail();
        $input['slug_unique'] = $slug
                . "-" . $language->ref_name
                . "-" . $partOfSpeech->part_of_speech
                . "-" . $scientificBranch->scientific_branch;
        // Limit the length of the slug_unique and append the IDs
        $input['slug_unique'] = str_limit($input['slug_unique'], 200);
        $input['slug_unique'] = $input['slug_unique'] . "-"
                . str_limit($language->id . $partOfSpeech->id . $scientificBranch->id, 55);
        
        return $input;
    }

}
