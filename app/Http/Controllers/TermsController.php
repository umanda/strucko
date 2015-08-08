<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Term;
use App\PartOfSpeech;
use App\ScientificBranch;
use App\Language;
use App\Synonym;
use App\Definition;
// use Request;
use Auth;
use App\Http\Requests\CreateTermRequest;
use App\Http\Requests\ShowTermRequest;
use Session;

class TermsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * List the terms.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // TODO Implement filtering
        // null !== \Input::get('filter') ? dd('true') : dd('false');
        
        // Get the latest terms.
        $terms = Term::latest()->approved()->get();

        return view('terms.index', compact('terms'));
    }
    /**
     * Show suggested terms - logged in usrs only. 
     * 
     * @return type
     */
    public function suggestions()
    {
        $terms = Term::latest()->suggested()->get();
        return view('terms.index', compact('terms'));
    }

    
    /**
     * Show the create view.
     * 
     * TODO: List only active partofspeech, branches and languages.
     * 
     * @return type
     */
    public function create()
    {
        // Prepare data for the form.
        $partOfSpeeches = PartOfSpeech::active()->get();
        $scientificBranches = ScientificBranch::active()->get();
        $languages = Language::active()->get();
        
        return view('terms.create', compact(
            'partOfSpeeches',
            'scientificBranches',
            'languages'
        ));
    }
    
    /**
     * Show the term.
     * This method uses route model binding, just to have that example. 
     * TODO Implement the show single term view.
     * 
     * @param Term $term
     * @param ShowTermRequest $request
     * @return type
     */
    public function show(Term $term, ShowTermRequest $request)
    {
        // return $term;
        //$term = Term::where('slug_unique', $slugUnique)->firstOrFail();

        return view('terms.show', compact('term'));
    }

    /**
     * TODO: Rewrite the unique slug to include the language, part of speech and category name
     * in the correct language. Consider changing the slug_unique logic to
     * take into account the posibility to change category...
     * Consider making this a transaction.
     * 
     * @return type
     */
    public function store(CreateTermRequest $request)
    {
        // Get input from the request and prepare slugs.
        // $input = Request::all();
        $input = $this->prepareSlugs($request->all());

        // Make sure that the term doesn't already exist (check unique constraint).
        // TODO: Unique constraint - try to check using custom validation.
        if ($this->termExists($input)) {
            // Flash messages that the term exists.
            $this->flashTermExists();
            
            return back()->withInput();
        }

        // Prepare new synonym and append synonym_id to the input
        $synonym = Synonym::create();
        $input['synonym_id'] = $synonym->id;
        
        // Get the user who is suggesting the Term.
//        $user = Auth::user();
//        $input['user_id'] = $user->id;
        $input['user_id'] = Auth::id();
        
        // If definition is not empty, create it for the synonym.
        if ($request->has('definition')) {
            // Definition::create();
            $synonym->definitions()->create([
                'definition' => $input['definition'], 
                'synonym_id' => $input['synonym_id'],
                'user_id' => $input['user_id'],
            ]);
        }

        // Persist the new Term
        Term::create($input);
        
        // Set alerts to session.
//        Session::flash('alert', 'Term suggested...');
//        Session::flash('alert_class', 'alert alert-success');
        
        // Redirect with alerts in session.
        return redirect('terms')->with([
            'alert' => 'Term suggested...',
            'alert_class' => 'alert alert-success'
        ]);
    }

    /**
     * Show the view to edit the term.
     * TODO Only administrators can edit terms - implement with middleware.
     * * 
     * @param Term $term
     * @return type
     */
    public function edit($slugUnique)
    {
        // Get the term with relationships.
        $term = Term::where('slug_unique', $slugUnique)
                ->with('language', 'status', 'scientificBranch', 'partOfSpeech', 'synonym.definitions')
                ->firstOrFail();
        
        // Prepare data for the form withouth the ones already in the term instance.
        $partOfSpeeches = PartOfSpeech::active()->without($term->part_of_speech_id)->get();
        $scientificBranches = ScientificBranch::active()->without($term->scientific_branch_id)->get();
        // Left filterLanguages() method for example, could use scope without() instead.
        $languages = $this->filterLanguages($term->language_id);
        
        return view('terms.edit', compact('term', 'partOfSpeeches', 'scientificBranches', 'languages'));
    }

    /**
     * Update the term.
     * 
     * @param type $slugUnique
     * @param Request $request
     */
    public function update($slugUnique, Request $request)
    {
        // Get the term to be updated.
        $term = Term::where('slug_unique', $slugUnique)->firstOrFail();
        
        // Prepare new slugs from the new input.
        $input = $this->prepareSlugs($request->all());
        
        // Make sure that the term doesn't already exist (check unique constraint).
        // We will send the ID of the term we are updating so that we can check
        // if the term which exists is the same term we are updating.
        // TODO: Unique constraint - try to check using custom validation.
        if ($this->termExists($input, $term->id)) {
            // Flash messages that the term exists.
            $this->flashTermExists();
            
            return back()->withInput();
        }
        
        // If definition is not empty, create it for the synonym.
        if ($request->has('definition')) {
            //dd($term->synonym->definitions);
            $term->synonym->definitions()->create([
                'definition' => $input['definition'], 
                //'synonym_id' => $input['synonym_id'],
                'user_id' => Auth::id(),
            ]);
        }
        
        // Update the term.
        $term->update($input);
        
        return redirect(action('TermsController@show', ['slugUnique' => $input['slug_unique']]))
                ->with([
                    'alert' => 'Term edited...',
                    'alert_class' => 'alert alert-success'
                ]);
    }
    
    /**
     * Check if the term already exists in the database for the choosen language,
     * part of speech and category.
     *
     * @param array $input
     * @return App\Term
     */
    protected function termExists($input, $updatedTermId = 0)
    {
        // Try to get the term.
        $term = Term::where('term', $input['term'])
                ->where('language_id', $input['language_id'])
                ->where('part_of_speech_id', $input['part_of_speech_id'])
                ->where('scientific_branch_id', $input['scientific_branch_id'])
                ->first();
                
        // If the term term doesn't exist, we can go on.
        if (is_null($term)) {
            return false;
        }
        
        // The term exists, but we have to check if this is the update() method
        // by comparing ID of the term we are trying to update
        // and the ID of the term we found in database.
        
        if ($updatedTermId == $term->id) {
            // This is the update, so we will let this action go on.
            return false;
        }
        
        // The term exists.
        return true;
        
    }
    
    /**
     * Prepare slug and slug_unique for the given term.
     * 
     * @param array $input
     * @return array
     */
    protected function prepareSlugs($input) {
        $slug = str_limit(str_slug($input['term']), 100);
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
        return $input;
    }
    
    /**
     * Get and filter trough all scientific branches and return the ones without the
     * one asked to be removed.
     * 
     * @param integer $itemToRemove
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function filterLanguages($itemToRemove) {
        return Language::active()
                ->get()
                ->reject(function($item) use ($itemToRemove) {
                    return $item->id == $itemToRemove;
                });
    }
    
    /** 
     * Flash the messages when the term exists.
     * 
     */
    protected function flashTermExists() {
        Session::flash('alert', 'This term already exists for the '
                    . 'selected language, part of speech, and category...');
        session()->flash('alert_class', 'alert alert-warning');
    }
}
