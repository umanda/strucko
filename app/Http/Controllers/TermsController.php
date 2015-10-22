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
use Auth;
use App\Http\Requests\EditTermRequest;
use App\Http\Requests\ShowTermRequest;
use App\Repositories\TermsFilterRepository;
use App\Http\Controllers\Traits\ManagesTerms;
use App\Repositories\TermShowFilterRepository;

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
        // Check if user has Administrator role for specified methods.
        $this->middleware('role:1000', ['only' => ['edit', 'update', 'updateStatus']]);
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
            // Get available letters for selected language and filed.
            $menuLetters = $this->getMenuLettersForLanguageAndField($allFilters);
            // I will send the language and filed to the view.
            $languageId = $allFilters['language_id'];
            $scientificFieldId = $allFilters['scientific_field_id'];

            // Check if the menu_letter is set. If so, get terms with that letter
            // and other term filters.
            if ($this->filters->isSetMenuLetter()) {
                $terms = Term::greaterThanRejected()
                        ->where($termFilters)
                        ->with('partOfSpeech')
                        ->orderBy('term')
                        ->paginate();
                
                // If the translate_to is set, get approved translations.
                if ($this->filters->isSetTranslateTo()) {
                    $terms->load(['concept.terms' => function ($query) use ($allFilters) {
                        $translateFilters = [];
                        $translateFilters['language_id'] = $allFilters['translate_to'];
                        Auth::check() ? '' : $translateFilters['status_id'] = 1000;
                        
                        $query->greaterThanRejected()
                                ->where($translateFilters)
                                ->with('status')
                                ->orderBy('status_id', 'DESC')
                                ->orderBy('votes_sum');
                    }]);
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
                        ->with('partOfSpeech')
                        ->orderBy('term')
                        ->paginate();
                
                // If the translate_to is set, get approved translations.
                if ($this->filters->isSetTranslateTo()) {
                    $terms->load(['concept.terms' => function ($query) use ($allFilters) {
                        $translateFilters = [];
                        $translateFilters['language_id'] = $allFilters['translate_to'];
                        Auth::check() ? '' : $translateFilters['status_id'] = 1000;
                        
                        $query->greaterThanRejected()
                                ->where($translateFilters)
                                ->with('status')
                                ->orderBy('status_id', 'DESC')
                                ->orderBy('votes_sum');
                    }]);
                }
            }
        }

        // Prepare languages and fields for filtering
        $languages = Language::active()->orderBy('ref_name')->get();
        $scientificFields = $this->prepareScientificFields();

        return view('terms.index', compact('terms', 'menuLetters', 'languageId', 'scientificFieldId', 'languages', 'scientificFields', 'menuLetterFilters'));
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
                        'partOfSpeeches', 'scientificFields', 'languages'
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

        //$term = Term::where('slug', $slug)->firstOrFail();
        // Get languages for translation options in suggest translation section.
        $languages = Language::active()
                ->without($term->language_id)
                ->orderBy('ref_name')
                ->get();
        // Prepare filters for synonyms. Approved ones are only for authenticated users.
        $synonymFilters = [];
        $synonymFilters['concept_id'] = $term->concept_id;
        $synonymFilters['language_id'] = $term->language_id;
        // For guests we will set the filter to get only approved terms.
        Auth::check() ? '' : $synonymFilters['status_id'] = 1000;

        // Get the terms with the same concept_id and the same language_id (synonyms).
        // Get votes only for logged in user.
        $synonyms = Term::greaterThanRejected()
                ->where($synonymFilters)
                ->without($term->id)
                ->with(['status',
                    'votes' => function($query) {
                        $query->where('user_id', Auth::id());
                    },
                    'user'
                    ])
                ->orderBy('status_id', 'DESC')
                ->orderBy('votes_sum', 'DESC')
                ->get();
        
        // Load definitions in the appropriate language.
        // Only load votes for current user.
        $languageId = $term->language_id;
        $term->load(['concept.definitions' => function ($query) use ($languageId) {
            $definitionFilters = [];
            $definitionFilters['language_id'] = $languageId;
            Auth::check() ? '' : $definitionFilters['status_id'] = 1000;
                        
            $query->where($definitionFilters)
                    ->with(['status',
                        'votes' => function($query) {
                            $query->where('user_id', Auth::id());
                        },
                        'user'
                        ])
                    ->orderBy('status_id', 'DESC')
                    ->orderBy('votes_sum', 'DESC');
        }]);
        // Load votes from the user on the term. Auth::id() returns null if guest.
        $term->load(['votes' => function ($query) {
            $query->where('user_id', Auth::id());
        }]);
        
        // If the translate_to is set, get the translations.
        if ($filters->isSetTranslateTo()) {
            // Prepare filters needed for translation
            $translationFilters = [];
            $translationFilters['concept_id'] = $term->concept_id;
            $translationFilters['language_id'] = $termShowFilters['translate_to'];
            // For guests we will only show approved translations
            Auth::check() ? '' : $translationFilters['status_id'] = 1000;

            // Get the terms with the same concept_id but with different language_id (translations)
            // Get votes for the current user
            $translations = Term::greaterThanRejected()
                    ->where($translationFilters)
                    ->with(['status',
                        'votes' => function($query) {
                            $query->where('user_id', Auth::id());
                        },
                        'user'
                        ])
                    ->orderBy('votes_sum', 'DESC')
                    ->get();
        }
        
        // Load merge suggestions if user is logged in.
        // Only load votes for the current user.
        if (Auth::check()) {
            $term->load(['mergeSuggestions' => function($query) {
                            $query->greaterThanRejected()
                                    ->orderBy('votes_sum');
                        },
                        'mergeSuggestions.concept.terms' => function($query) use ($languageId) {
                            $query->greaterThanRejected()
                                    ->where('language_id', $languageId)
                                    ->orderBy('votes_sum');
                        },
                        'mergeSuggestions.votes' => function($query) {
                            $query->where('user_id', Auth::id());
                        },
                        'user'
                        ]);
        }

        return view('terms.show', compact('term', 'synonyms', 'languages', 'translations'));
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
