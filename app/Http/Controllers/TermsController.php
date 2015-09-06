<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Term;
use App\PartOfSpeech;
use App\ScientificField;
use App\Language;
use App\Synonym;
use App\ScientificArea;
use App\Status;
// use App\Definition;
// use Request;
use Auth;
use App\Http\Requests\EditTermRequest;
use App\Http\Requests\ShowTermRequest;
use Session;
use App\Repositories\FilterRepository;

class TermsController extends Controller
{
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
        $activeFilters = $this->filters->all();
        
        // Check appropriate query parameters and variables.
        if ($this->filters->isSetLanguageAndField()) {
            
            $menuLetters = Term::approved()
                ->whereHas('synonym', function ($query) use ($activeFilters) {
                    $query->where('language_id', $activeFilters['language_id'])
                          ->where('scientific_field_id', $activeFilters['scientific_field_id']);
                })
                ->groupBy('menu_letter')
                ->orderBy('menu_letter')
                ->lists('menu_letter');
            
            $language_id = $activeFilters['language_id'];
            $scientific_field_id = $activeFilters['scientific_field_id'];
            
            // Check if the menu_letter is set. If so, get terms with that letter.
            if ($this->filters->isSetMenuLetter()) {
                $terms = Term::approved()
                    ->latest()
                    ->whereHas('synonym', function ($query) use ($activeFilters) {
                        $query->where($activeFilters);
                    })
                    ->get();
            }
            
            // Check if the search is set. If so, try to find terms.
            if ($this->filters->isSetSearch()) {
                $terms = Term::latest()
                    ->approved()
                    ->where('term', 'like', '%'. $activeFilters['search'] . '%')
                    ->whereHas('synonym', function ($query) use ($activeFilters) {
                        $query->where('language_id', $activeFilters['language_id'])
                              ->where('scientific_field_id', $activeFilters['scientific_field_id']);
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
                        'language_id',
                        'scientific_field_id',
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
    
    /**
     * Add translation for the current synonym.
     * 
     * @param \App\Http\Requests\EditTranslationRequest $request
     * @param type $slugUnique
     * @return type
     */
    public function addTranslation(Requests\EditTranslationRequest $request, $slugUnique)
    {
        // Get all input from the request. Also prepare input values in case we need to create a new term.
        $input = $this->prepareInputValues($request->all());
        // Get the user suggesting the translation
        $input['user_id'] = Auth::id();
        
        // Get the term to be used to add translation trough synonym relationship.
        $term = Term::where('slug_unique', $slugUnique)->with('synonym', 'synonym.translations')->firstOrFail();
        
        // Make sure that languages are not the same
        if ($term->synonym->language_id == $input['language_id']) {
            return back()->with([
                    'alert' => 'Translated term can not be in the same language',
                    'alert_class' => 'alert alert-warning'
                ]);
        }
        
        // Check if the translation term already exist
        if ($this->termExists($input)) {
            // We will get the existing term and use its synonym_id as translation_id
            $translationId = Term::where('term', $input['term'])
                ->whereHas('synonym', function ($query) use ($input) {
                        $query->where('language_id', $input['language_id'])
                              ->where('part_of_speech_id', $input['part_of_speech_id'])
                              ->where('scientific_field_id', $input['scientific_field_id']);
                    })
                ->value('synonym_id');
            
            // Check if the translation for synonyms already exists
            if ($term->synonym->translations->contains($translationId)) {
                return back()->withInput()->with([
                    'alert' => 'This term already exists as translation...',
                    'alert_class' => 'alert alert-warning'
                ]);
            }
                        
            // If no, we can add (suggest) a translation
            $term->synonym->addTranslation($translationId, $input['user_id']);
            return back()->with([
                    'alert' => 'Translation suggested for existing term',
                    'alert_class' => 'alert alert-success'
                ]);
        }
        else {
            // Prepare new synonym 
            $translationSynonym = Synonym::create($input);
            // Persist the new Term
            $translationSynonym->terms()->create($input);
            // Ok, we can suggest the translation.
            $term->synonym->addTranslation($translationSynonym->id, $input['user_id']);
            return back()->with([
                    'alert' => 'New term added and translation suggested',
                    'alert_class' => 'alert alert-success'
                ]);
        }
    }

    /**
     * Check if the term already exists in the database for the choosen language,
     * part of speech and scientific field.
     * 
     * @param array $input
     * @param integer $updatedTermId
     * @return boolean
     */
    protected function termExists($input, $updatedTermId = 0)
    {
        // Try to get the term.
        $term = Term::where('term', $input['term'])
                ->whereHas('synonym', function ($query) use ($input) {
                        $query->where('language_id', $input['language_id'])
                              ->where('part_of_speech_id', $input['part_of_speech_id'])
                              ->where('scientific_field_id', $input['scientific_field_id']);
                    })
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
        // Get the strings for language, partOfSpeech and category, for SEO.
        $language = Language::where('id', $input['language_id'])->firstOrFail();
        $partOfSpeech = PartOfSpeech::where('id', $input['part_of_speech_id'])->firstOrFail();
        $scientificField = ScientificField::where('id', $input['scientific_field_id'])->firstOrFail();
        
        // Prepare 'slug' attribute.
        $slug = str_limit(str_slug($input['term']), 100);
        $input['slug'] = $slug;
        
        // Prepare 'slug_unique' attribute.
        $input['slug_unique'] = $slug . "-" . str_slug(
                $language->ref_name . "-"
                . $partOfSpeech->part_of_speech. "-"
                . $scientificField->scientific_field
                );
        // Limit the length of the slug_unique and append the IDs
        $input['slug_unique'] = str_limit($input['slug_unique'], 200);
        $input['slug_unique'] = $input['slug_unique'] . "-"
                . str_limit($language->id . $partOfSpeech->id . $scientificField->id, 55);
        
        return $input;
    }
    
    /**
     * Get and filter trough all languages and return the ones without the
     * one asked to be removed.
     * 
     * @param integer $itemToRemove
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function filterLanguages($itemToRemove) {
        return Language::active()
                ->orderBy('ref_name')
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
    
    /**
     * Prepare an array of scientific fields to be used in forms, grouped by area.
     * 
     * @return array Array of fields grouped by area.
     */
    protected function prepareScientificFields() {
        $fields = [];
        
        // Get areas including their fileds.
        $areas = ScientificArea::active()
                ->with(['scientificFields' => function ($query) {
                    $query->where('active', 1)->orderBy('scientific_field');
                }])
                ->orderBy('scientific_area')
                ->get();
        
        // Populate an array with areas as keys, and fields as sub arrays.
        foreach ($areas as $area) {
            $fields[$area->scientific_area] = array();
            
            foreach ($area->scientificFields->all() as $field) {
                
                $fields[$area->scientific_area][$field->id] = $field->scientific_field;
            }
        }
        
        return $fields;
    }
    
    protected function prepareMenuLetter ($term, $languageId) {
        
        // Get locale for the language and then set locale.
        $locale = Language::where('id', $languageId)->value('locale');
        setlocale(LC_CTYPE, $locale);
       
        // Get the first letter of the term
        // Didn't use mb_substr because I get ?
        $letter = substr($term, 0, 1);
        
        // If the letter is alpha, return letter
        if (ctype_alpha($letter)) {
            // Here I use mb_substr because I get real letter.
            return mb_strtoupper(mb_substr($term, 0, 1));
        }
        
        // The letter is not alpha, so return default string.
        return '0';
        
    }

    /**
     * Get null if the string from the form input is actually empty. 
     * If it is not empty, it will return its value.
     * 
     * @param string $input Value to check if it is empty or not
     * @return string|null String if not empty, else null
     */
    public function getNullForOptionalInput($input)
    {
        return empty(trim($input)) ? null : $input;
    }
    
    /**
     * Prepare slugs, abbreciation, menu letter for term.
     * 
     * @param array $input
     * @return array
     */
    public function prepareInputValues($input)
    {
        // Prepare slugs.
        $input = $this->prepareSlugs($input);
        // Make sure that abbreviation is null if empty.
        $input['abbreviation'] = $this->getNullForOptionalInput($input['abbreviation']);
        // Prepare menu_letter for the term and add to input.
        $input['menu_letter'] = $this->prepareMenuLetter($input['term'], $input['language_id']);
        
        return $input;
       
    }

}