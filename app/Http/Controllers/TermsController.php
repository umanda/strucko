<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Term;
use App\PartOfSpeech;
use App\Language;
use App\Synonym;
use App\Status;
use Auth;
use App\Http\Requests\EditTermRequest;
use App\Http\Requests\ShowTermRequest;
use App\Repositories\FilterRepository;

use App\Http\Controllers\Traits\ManagesTermsAndSynonyms;

class TermsController extends Controller
{
    use ManagesTermsAndSynonyms;
    
    /**
     * Filters used to get specific terms.
     * 
     * @var type 
     */
    protected $filters;
    
    public function __construct()
    {
        // User has to be authenticated, except for specified methods.
        $this->middleware('auth', ['except' => ['index', 'show']]);
        // Check if user has Administrator role for specified methods.
        $this->middleware('role:1000', ['only' => ['edit', 'update']]);
    }

    /**
     * List the terms.
     *
     * @return \Illuminate\View\View
     */
    public function index(FilterRepository $filters)
    {
        $this->filters = $filters;
        
        $allFilters = $this->filters->allFilters();
        
        $termFilters = $this->filters->termFilters();
        
        // Check appropriate query parameters and variables.
        if ($this->filters->isSetLanguageAndField()) {
            
            $menuLetters = $this->getMenuLettersForLanguageAndField($allFilters);
            
            $languageId = $allFilters['language_id'];
            $scientificFieldId = $allFilters['scientific_field_id'];
            
            // Check if the menu_letter is set. If so, get terms with that letter
            // and other term filters.
            if ($this->filters->isSetMenuLetter()) {
                $terms = Term::approved()
                    ->latest()
                    ->whereHas('synonym', function ($query) use ($termFilters) {
                        $query->where($termFilters);
                    })
                    ->get();
            }
            
            // Check if the search is set. If so, try to find terms.
            if ($this->filters->isSetSearch()) {
                $terms = Term::latest()
                    ->approved()
                    ->where('term', 'like', '%'. $allFilters['search'] . '%')
                    ->whereHas('synonym', function ($query) use ($allFilters) {
                        $query->where('language_id', $allFilters['language_id'])
                              ->where('scientific_field_id', $allFilters['scientific_field_id']);
                    })
                    ->get();
            }
        }
        
        // Prepare languages and fields for filtering
        $languages = Language::active()->orderBy('ref_name')->get();
        $scientificFields = $this->prepareScientificFields();
        
        return view('terms.index',
                compact('terms',
                        'menuLetters',
                        'languageId',
                        'scientificFieldId',
                        'languages',
                        'scientificFields'));
    }
    
    /**
     * TODO Create another view for suggested terms (because of FilterRepository).
     * TODO Consider creating Filter repository and view composer, like for approved terms.
     * 
     * Show suggested terms - logged in users only. 
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
     * @return type
     */
    public function create()
    {
        // Prepare data for the form.
        $partOfSpeeches = PartOfSpeech::active()->orderBy('part_of_speech')->get();
        $scientificFields = $this->prepareScientificFields();
        $languages = Language::active()->orderBy('ref_name')->get();
        
        return view('terms.create', compact(
            'partOfSpeeches',
            'scientificFields',
            'languages'
        ));
    }
    
     /**
     * TODO Make sure that only active language, part of speech and category can be set (implement guarding - trough request?).
     * TODO Consider making this a transaction.
     * 
     * @return type
     */
    public function store(EditTermRequest $request)
    {
        // Get input from the request and prepare slugs.
        // $input = Request::all();
        $input = $this->prepareInputValues($request->all());
        // Get the user suggesting the term
        $input['user_id'] = Auth::id();

        // Make sure that the term doesn't already exist (check unique constraint).
        if ($this->termExists($input)) {
            // Flash messages that the term exists.
            $this->flashTermExists();
            return back()->withInput();
        }
        // Prepare new synonym
        $synonym = Synonym::create($input);
        
        // Persist the new Term using the relationship
        $synonym->terms()->create($input);
        
        // Redirect with alerts in session.
        return redirect('terms/' . $input['slug_unique'])->with([
            'alert' => 'Term suggested...',
            'alert_class' => 'alert alert-success'
        ]);
    }
    
    /**
     * Show the term.
     * This method uses route model binding, just to have that example.
     * 
     * @param Term $term
     * @param ShowTermRequest $request
     * @return type
     */
    public function show(Term $term, ShowTermRequest $request)
    {
        //$term = Term::where('slug_unique', $slugUnique)->firstOrFail();
        // Get languages for translation options
        $languages = Language::active()
                ->without($term->synonym->language_id)
                ->orderBy('ref_name')
                ->get();
        
        return view('terms.show', compact('term', 'languages'));
    }

    /**
     * Show the view to edit the term.
     * 
     * @param string $slugUnique The unique slug used to identify term.
     * @return type
     */
    public function edit($slugUnique)
    {
        // Get the term with relationships.
        $term = Term::where('slug_unique', $slugUnique)
                ->with('status', 'synonym.language', 'synonym.scientificField', 'synonym.partOfSpeech', 'synonym.definitions')
                ->firstOrFail();
        
        // Prepare data for the form withouth the ones already in the term instance.
        $partOfSpeeches = PartOfSpeech::active()->without($term->synonym->part_of_speech_id)->get();
        $scientificFields = $this->prepareScientificFields();
        // Left filterLanguages() method for example. Using the Form::select for Languages.
        // $languages = $this->filterLanguages($term->language_id);
        $languages = Language::active()->orderBy('ref_name')->get();
        $statuses = Status::active()->orderBy('id')->lists('status', 'id');
        
        return view('terms.edit', 
                compact('term', 'partOfSpeeches', 'scientificFields', 'languages', 'statuses'));
    }

    /**
     * Update the term.
     * 
     * @param string $slugUnique Unique slug used to identify term.
     * @param EditTermRequest $request
     */
    public function update($slugUnique, EditTermRequest $request)
    {
        // Get the term to be updated, and synonym.
        $term = Term::where('slug_unique', $slugUnique)->with('synonym')->firstOrFail();
//        $synonym = Synonym::whereHas('terms', function ($query) use ($slugUnique) { 
//            $query->where('slug_unique', $slugUnique);
//        })->with('terms')->firstOrFail();
        
        // Prepare new input values, without user_id
        $input = $this->prepareInputValues($request->all());
        // Make sure that the user_id stays the same
        $input['user_id'] = $term->user_id;
        
        // Make sure that the term doesn't already exist (check unique constraint).
        // We will send the ID of the term we are updating so that we can check
        // if the term which exists is the same term we are updating.
        // TODO: Unique constraint - try to check using custom validation.
        if ($this->termExists($input, $term->id)) {
            // Flash messages that the term exists.
            $this->flashTermExists();
            return back()->withInput();
        }
                
        // Update the term and synonym.
        $term->update($input);
        $term->synonym()->update($input);
        
        return redirect(action('TermsController@show', ['slugUnique' => $input['slug_unique']]))
                ->with([
                    'alert' => 'Term updated...',
                    'alert_class' => 'alert alert-success'
                ]);
    }
    
    /**
     * Update the status of the Term.
     * 
     * @param \App\Http\Requests\EditStatusRequest $request
     * @param string $slugUnique Unique slug for Term
     * @return type Return to the previous page
     */
    public function updateStatus(Requests\EditStatusRequest $request, $slugUnique) {
        
        $term = Term::where('slug_unique', $slugUnique)->firstOrFail();
        
        $term->status_id = $request->input('status_id');
        
        $term->save();
        
        return back()->with([
                    'alert' => 'Status updated...',
                    'alert_class' => 'alert alert-success'
                ]);
    }
}