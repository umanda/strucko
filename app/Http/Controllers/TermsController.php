<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Term;
use App\PartOfSpeech;
use App\Language;
use App\Concept;
use App\Status;
use App\ScientificField;
use Auth;
use App\Http\Requests\EditTermRequest;
use App\Http\Requests\ShowTermRequest;
use App\Repositories\TermsFilterRepository;
use App\Http\Controllers\Traits\ManagesTerms;
use App\Repositories\TermShowFilterRepository;
use DB;
use App\Translation;

class TermsController extends Controller
{
    // Trait with common functions related to Terms.
    use ManagesTerms;

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
        // Check if user has Administrator role, except for specified methods.
        $this->middleware('role:1000', ['except' => [
            'index',
            'create',
            'store',
            'show',
            ]]);
        // Check spam threshold.
        $this->middleware('spam', ['only' => ['store']]);
    }

    /**
     * List the terms.
     *
     * @return \Illuminate\View\View
     */
    public function index(TermsFilterRepository $filters)
    {
        $this->filters = $filters;
        $allFilters = $this->filters->allFilters();
        $termFilters = $this->filters->termFilters();
        $menuLetterFilters = $this->filters->menuLetterFilters();

        // Check appropriate query parameters and variables.
        if ($this->filters->isSetLanguageAndField()) {
            // Make sure selected language and field exist.
            Language::active()->findOrFail($allFilters['language_id']);
            ScientificField::active()->findOrFail($allFilters['scientific_field_id']);
            
            // Get available letters for selected language and filed.
            $menuLetters = $this->getMenuLettersForLanguageAndField($allFilters);
            
            // Check if the menu_letter is set. If so, get terms with that letter
            // and other term filters.
            if ($this->filters->isSetMenuLetter()) {
                $terms = Term::greaterThanRejected()
                        ->where($termFilters)
                        ->with('partOfSpeech', 'language', 'status')
                        ->orderBy('term')
                        ->paginate();
                
                // If the translate_to is set, get translations.
                if ($this->filters->isSetTranslateTo()) {
                    $terms->load(['translations' => function ($query) use ($allFilters) {
                        $translateFilters = [];
                        Auth::check() ? '' : $translateFilters['status_id'] = 1000;
                          
                        $query->greaterThanRejected()
                                ->where($translateFilters)
                                ->whereHas('translation', function ($query) use ($allFilters){
                                    $query->where('language_id', $allFilters['translate_to']);
                                })
                                ->with('translation', 'translation.language', 'translation.status')
                                ->orderBy('status_id', 'DESC')
                                ->orderBy('votes_sum', 'DESC')
                                ->get();
                    },
                    ]);
                }
            }

            // Check if the search is set. If so, try to find terms.
            if ($this->filters->isSetSearch()) {
                $searchFilters = [];
                $searchFilters['language_id'] = $allFilters['language_id'];
                $searchFilters['scientific_field_id'] = $allFilters['scientific_field_id'];
                Auth::check() ? '' : $searchFilters['status_id'] = 1000;
                
                $terms = Term::greaterThanRejected()
                        ->where('term', 'like', '%' . $allFilters['search'] . '%')
                        ->where($searchFilters)
                        ->with('partOfSpeech', 'language', 'status')
                        ->orderBy('term')
                        ->paginate();
                
                // If the translate_to is set, get approved translations.
                if ($this->filters->isSetTranslateTo()) {
                    $terms->load(['translations' => function ($query) use ($allFilters) {
                        $translateFilters = [];
                        Auth::check() ? '' : $translateFilters['status_id'] = 1000;
                          
                        $query->greaterThanRejected()
                                ->where($translateFilters)
                                ->whereHas('translation', function ($query) use ($allFilters){
                                    $query->where('language_id', $allFilters['translate_to']);
                                })
                                ->with('translation', 'translation.language', 'translation.status')
                                ->orderBy('status_id', 'DESC')
                                ->orderBy('votes_sum', 'DESC')
                                ->get();
                    },
                    ]);
                }
            }
        }
        
        // Prepare other data used in view.
        $languages = Language::active()->orderBy('ref_name')->get();
        $scientificFields = $this->prepareScientificFields();
        // Get current values for language, field and menu letter.
        $language = isset($allFilters['language_id']) ? $languages->lists('ref_name', 'id')->get($allFilters['language_id']) : '';
        $scientificField = isset($allFilters['scientific_field_id']) ? collect(call_user_func_array('array_replace', $scientificFields))->get($allFilters['scientific_field_id']) : '';
        $menuLetter = isset($allFilters['menu_letter']) ? htmlspecialchars($allFilters['menu_letter']) : '';
        $search = isset($allFilters['search']) ? htmlspecialchars($allFilters['search']) : '';
        $translateToLanguage = isset($allFilters['translate_to']) ? Language::active()->findOrFail($allFilters['translate_to'])->ref_name : '';
                
        // Get metadata like description, title.
        $indexMeta = $this->prepareIndexMeta($allFilters, $language, 
                $scientificField, $menuLetter, $translateToLanguage, $search);
        
        return view('terms.index',
                compact('terms', 'menuLetters', 'language', 'scientificField',
                        'languages', 'scientificFields', 'menuLetterFilters',
                        'indexMeta', 'translateToLanguage', 'search', 'menuLetter'));
    }

    /**
     * Show the create view.
     *  
     * @return type
     */
    public function create()
    {
        // Prepare data for the form.
        $partOfSpeeches = PartOfSpeech::active()->get();
        $scientificFields = $this->prepareScientificFields();
        $languages = Language::active()->orderBy('ref_name')->get();

        return view('terms.create', compact(
                        'partOfSpeeches', 'scientificFields', 'languages'
        ));
    }

    /**
     * Persist suggested term.
     * TODO Consider making this a transaction.
     * 
     * @return type
     */
    public function store(EditTermRequest $request)
    {
        // Get input from the request and prepare slug and menu letter.
        $input = $this->prepareInputValues($request->all());
        // Get the user suggesting the term
        $input['user_id'] = Auth::id();

        // Make sure that the term doesn't already exist (check unique constraint).
        if ($this->termExists($input)) {
            // Flash messages that the term exists.
            $this->flashTermExists();
            return back()->withInput();
        }

        // Prepare new concept
        $concept = Concept::create();

        // Persist the new Term using the relationship
        $concept->terms()->create($input);

        // Redirect with alerts in session.
        return redirect('terms/' . $input['slug'])->with([
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
    public function show(Term $term, ShowTermRequest $request, TermShowFilterRepository $filters)
    {
        // Prepare all filters from request
        $termShowFilters = $filters->allFilters();

        // Get languages for translation options in suggest translation section.
        $languages = Language::active()
                ->without($term->language_id)
                ->orderBy('ref_name')
                ->get();
        
        // Load synonyms
        $term->load(['synonyms' => function ($query) use ($term) {
                    $synonymFilters = [];
                    Auth::check() ? '' : $synonymFilters['status_id'] = 1000;

                    $query->greaterThanRejected()
                            ->where($synonymFilters)
                            ->whereHas('synonym', function ($query) use ($term){
                                $query->where('language_id', $term->language_id)
                                        ->where('scientific_field_id', $term->scientific_field_id)
                                        ->where('part_of_speech_id', $term->part_of_speech_id);
                            })
                            ->with('synonym', 'synonym.language', 'synonym.status')
                            ->orderBy('status_id', 'DESC')
                            ->orderBy('votes_sum', 'DESC')
                            ->get();
                    },
                    'votes' => function ($query) {
                        $query->where('user_id', Auth::id());
                    },
                    'status', 'user'
            ]);
                    
        // Load definitions
        // Only load votes for current user.
        $term->load(['definitions' => function ($query) {
            $definitionFilters = [];
            Auth::check() ? '' : $definitionFilters['status_id'] = 1000;
                        
            $query->greaterThanRejected()
                    ->where($definitionFilters)
                    ->with(['status',
                        'votes' => function($query) {
                            $query->where('user_id', Auth::id());
                        },
                        'user', 'language'
                        ])
                    ->orderBy('status_id', 'DESC')
                    ->orderBy('votes_sum', 'DESC');
        }]);
        
        // If the translate_to is set, get the translations.
        if ($filters->isSetTranslateTo()) {
            
            $term->load(['translations' => function ($query) use ($termShowFilters) {
                        // Prepare filters
                        $translateFilters = [];
                        Auth::check() ? '' : $translateFilters['status_id'] = 1000;
                          
                        $query->greaterThanRejected()
                                ->where($translateFilters)
                                ->whereHas('translation', function ($query) use ($termShowFilters){
                                    $query->where('language_id', $termShowFilters['translate_to']);
                                })
                                ->with('translation', 'translation.language', 'translation.status')
                                ->orderBy('votes_sum', 'DESC');
                    },
                    'votes' => function ($query) {
                        $query->where('user_id', Auth::id());
                    },
                    'status', 'user'
                    ]);
        }
        
        // Prepare meta data.
        $translateToLanguage = isset($termShowFilters['translate_to']) ? Language::active()->findOrFail($termShowFilters['translate_to'])->ref_name : '';
        $showMeta = $this->prepareShowMeta($filters, $term, $translateToLanguage);

        return view('terms.show', compact('term', 'languages', 'showMeta'));
    }

    /**
     * Show the view to edit the term.
     * 
     * @param string $slug The unique slug used to identify term.
     * @return type
     */
    public function edit($slug)
    {
        // Get the term with relationships.
        $term = Term::where('slug', $slug)
                ->with('status', 'language', 'scientificField', 'partOfSpeech', 'concept.definitions')
                ->firstOrFail();

        // Prepare data for the form withouth the ones already in the term instance.
        $partOfSpeeches = PartOfSpeech::active()->without($term->part_of_speech_id)->get();
        $scientificFields = $this->prepareScientificFields();
        // Left filterLanguages() method for example. Using the Form::select for Languages.
        // $languages = $this->filterLanguages($term->language_id);
        $languages = Language::active()->orderBy('ref_name')->get();
        $statuses = Status::active()->orderBy('id')->lists('status', 'id');

        return view('terms.edit', compact('term', 'partOfSpeeches', 'scientificFields', 'languages', 'statuses'));
    }

    /**
     * Update the term.
     * 
     * @param string $slug Unique slug used to identify term.
     * @param EditTermRequest $request
     */
    public function update($slug, EditTermRequest $request)
    {
        // Get the term to be updated, and synonym.
        $term = Term::where('slug', $slug)->firstOrFail();

        // Prepare new input values, without user_id
        $input = $this->prepareInputValues($request->all());
        // Make sure that the user_id stays the same
        $input['user_id'] = $term->user_id;

        // Make sure that the term doesn't already exist (check unique constraint).
        // We will send the ID of the term we are updating so that we can check
        // if the term which exists is the same term we are updating.
        if ($this->termExists($input, $term->id)) {
            // Flash messages that the term exists.
            $this->flashTermExists();
            return back()->withInput();
        }

        // Update the term.
        $term->update($input);

        return redirect(action('TermsController@show', ['slug' => $input['slug']]))
                        ->with([
                            'alert' => 'Term updated...',
                            'alert_class' => 'alert alert-success'
        ]);
    }

    /**
     * Update the status of the Term.
     * 
     * @param \App\Http\Requests\EditStatusRequest $request
     * @param string $slug Unique slug for Term
     * @return type Return to the previous page
     */
    public function updateStatus(Requests\EditStatusRequest $request, $slug)
    {

        $term = Term::where('slug', $slug)->firstOrFail();

        $term->status_id = $request->input('status_id');

        $term->save();

        return back()->with([
                    'alert' => 'Status updated...',
                    'alert_class' => 'alert alert-success'
        ]);
    }

    /**
     * Set the status of the term to approved.
     * 
     * @param string $slug Unique slug of the term
     * @return \Illuminate\Http\RedirectResponse Go back
     */
    public function approveTerm($slug)
    {
        $term = Term::where('slug', $slug)->firstOrFail();

        $term->status_id = 1000;

        $term->save();

        return back()->with([
                    'alert' => 'Term approved...',
                    'alert_class' => 'alert alert-success'
        ]);
    }

    /**
     * Set the status of the term to rejected.
     * 
     * @param string $slug Unique slug of the term
     * @return \Illuminate\Http\RedirectResponse Go back
     */
    public function rejectTerm($slug)
    {
        $term = Term::where('slug', $slug)->firstOrFail();

        $term->status_id = 250;

        $term->save();

        return back()->with([
                    'alert' => 'Term rejected...',
                    'alert_class' => 'alert alert-success'
        ]);
    }

}
