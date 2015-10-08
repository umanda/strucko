<?php

namespace App\Http\Controllers\Traits;

use App\Language;
use App\PartOfSpeech;
use App\ScientificArea;
use App\ScientificField;
use App\Term;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Session;

trait ManagesTerms
{

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
                ->where('language_id', $input['language_id'])
                ->where('part_of_speech_id', $input['part_of_speech_id'])
                ->where('scientific_field_id', $input['scientific_field_id'])
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
     * Prepare slug for the given term.
     * 
     * @param array $input
     * @return array
     */
    protected function prepareSlug($input)
    {
        // Get the strings for language, partOfSpeech and scientificField, for SEO.
        $language = Language::where('id', $input['language_id'])->firstOrFail();
        $partOfSpeech = PartOfSpeech::where('id', $input['part_of_speech_id'])->firstOrFail();
        $scientificField = ScientificField::where('id', $input['scientific_field_id'])->firstOrFail();

        // Prepare 'slug' attribute.
        $slug = str_limit(str_slug($input['term']), 100);
        $input['slug'] = $slug . '-' . str_slug(
                        $language->ref_name . '-'
                        . $partOfSpeech->part_of_speech . '-'
                        . $scientificField->scientific_field
        );
        // Limit the length of the slug_unique and append the IDs
        $input['slug'] = str_limit($input['slug'], 200);
        $input['slug'] = $input['slug'] . '-'
                . str_limit($language->id . $partOfSpeech->id . $scientificField->id, 55);

        return $input;
    }

    /**
     * Get and filter trough all languages and return the ones without the
     * one asked to be removed.
     * 
     * @param integer $itemToRemove
     * @return Collection
     */
    protected function filterLanguages($itemToRemove)
    {
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
    protected function flashTermExists()
    {
        Session::flash('alert', 'This term already exists for the '
                . 'selected language, part of speech, and category...');
        session()->flash('alert_class', 'alert alert-warning');
    }

    /**
     * Prepare an array of scientific fields to be used in forms, grouped by area.
     * 
     * @return array Array of fields grouped by area.
     */
    protected function prepareScientificFields()
    {
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

    protected function prepareMenuLetter($term, $languageId)
    {

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
        return urlencode('0');
    }

    /**
     * Prepare slugs, menu letter, and is_abbreviation for term.
     * 
     * @param array $input
     * @return array
     */
    protected function prepareInputValues($input)
    {
        // Prepare slugs.
        $input = $this->prepareSlug($input);
        // Prepare menu_letter for the term and add to input.
        $input['menu_letter'] = $this->prepareMenuLetter($input['term'], $input['language_id']);
        // Prepare is_abbreviation value.
        $input['is_abbreviation'] = isset($input['is_abbreviation']) ? 1 : 0;

        return $input;
    }

    /**
     * Get menu letters for terms in the selected language and scientific field.
     * 
     * @param array $activeFilters Filters for terms
     * @return Collection Filtered letters
     */
    protected function getMenuLettersForLanguageAndField(array $activeFilters)
    {
        // Get locale for the language
        $locale = Language::where('id', $activeFilters['language_id'])->value('locale');

        // Create collator instance which will be used to properly sort items.
        $collator = new \Collator($locale);

        $letters = Term::approved()
                ->where('language_id', $activeFilters['language_id'])
                ->where('scientific_field_id', $activeFilters['scientific_field_id'])
                ->groupBy('menu_letter')
                ->lists('menu_letter')
                ->toArray();

        // Sort letters
        $collator->sort($letters);

        // Return collection of letters
        return collect($letters);
    }

    /**
     * Get the synonym ID of the existing term. Check existance first.
     * 
     * @param array $input Form data
     * @return integer ID of the Synonym
     */
    protected function getExistingConceptId(array $input)
    {
        return Term::where('term', $input['term'])
                        ->where('language_id', $input['language_id'])
                        ->where('part_of_speech_id', $input['part_of_speech_id'])
                        ->where('scientific_field_id', $input['scientific_field_id'])
                        ->value('concept_id');
    }

}
