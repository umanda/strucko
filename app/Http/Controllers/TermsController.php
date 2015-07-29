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
// use Request;
use Auth;
use App\Http\Requests\CreateTermRequest;

class TermsController extends Controller
{

    public function index()
    {
        // Get the latest terms.
        $terms = Term::latest()->approved()->get();

        return view('terms.index', compact('terms'));
    }
    
    // TODO: List only active partofspeech, branches and languages.
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
    public function store(CreateTermRequest $request)
    {
        // Get input from the request
        $input = $request->all(); // $input = Request::all();
        
        // Prepare new synonym and append synonym_id to the input
        $synonym = Synonym::create();
        $input['synonym_id'] = $synonym->id;
        
        // Prepare the slug and slug_unique
        $slug = str_limit(str_slug($input['term']), $limit = 100 );
        $input['slug'] = $slug;
        // Get the strings for language, partOfSpeech and category, for SEO.
        $language = Language::where('id', $input['language_id'])->firstOrFail();
        $partOfSpeech = PartOfSpeech::where('id', $input['part_of_speech_id'])->firstOrFail();
        $scientificBranch = ScientificBranch::where('id', $input['scientific_branch_id'])->firstOrFail();
        $input['slug_unique'] = $slug
                . "-" . $language->ref_name
                . "-" . $partOfSpeech->part_of_speech
                . "-" . $scientificBranch->scientific_branch;
        // Limit the length of the slug_unique and append the IDs
        $input['slug_unique'] = str_limit($input['slug_unique'], 200);
        $input['slug_unique'] = $input['slug_unique'] . "-"
                . str_limit($language->id . $partOfSpeech->id . $scientificBranch->id, 55);
        
        // Get the user who is suggesting the Term.
        $user = Auth::user();
        $input['user_id'] = $user->id;
        
        // TODO: Consider setting the mysql default term_status_ID instead of this 
        $input['term_status_id'] = 1;
        
        // Persist the new Term and return to /terms
        Term::create($input);
        return redirect('terms');
    }

}
