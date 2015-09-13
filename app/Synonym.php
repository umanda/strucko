<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Synonym;
use App\MergeSuggestion;

class Synonym extends Model
{
        protected $fillable = [
        'language_id',
        'part_of_speech_id',
        'scientific_field_id',
    ];
        
    /**
     * Synonym may have many terms.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function terms()
    {
        return $this->hasMany('App\Term');
    }
    
    /**
     * Synonym may have many definitions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function definitions()
    {
        return $this->hasMany('App\Definition');
    }
    
    /**
     * Synonym is in a language.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo('App\Language');
    }
    
    /**
     * Synonym belongs to scientific branch.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scientificField()
    {
        return $this->belongsTo('App\ScientificField');
    }
    
    /**
     * Synonym belongs to part of speech.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partOfSpeech()
    {
        return $this->belongsTo('App\PartOfSpeech');
    }
    
    /**
     * Synonym may have many synonym translations.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany BelongsToMany relationship
     * 
     */
    public function translations()
    {
        return $this->belongsToMany(Synonym::class, 'synonym_translation', 'synonym_id', 'translation_id')
                ->withPivot('id', 'status_id', 'user_id')
                ->withTimestamps();
    }
    
    /**
     * Synonym may have many synonym merge suggestions. 
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany BelongsToMany relationship
     */
    public function mergeSuggestions()
    {
         return $this->hasMany(MergeSuggestion::class);
    }
    
    /**
     * Add translation in both ways.
     * 
     * @param type $translationId
     * @param type $userId
     * @param type $statusId
     */
    public function addTranslation($translationId, $userId, $statusId = 500)
    {
        // Add translation to this model instance.
        $this->translations()->attach($translationId, ['user_id' => $userId, 'status_id' => $statusId]);
        // Get the translated model instance.
        $translation = Synonym::findOrFail($translationId);
        // Add the oposite direction translation.
        $translation->translations()->attach($this->id, ['user_id' => $userId, 'status_id' => $statusId]);
    }
    
    /**
     * Remove translation in both ways.
     * 
     * @param type $translationId
     */
    public function removeTranslation($translationId)
    {
        // Remove translation from this model instance.
        $this->translations()->detach($translationId);
        // Get the removed translation model instance.
        $translation = Synonym::findOrFail($translationId);
        // Remove the oposite direction translation.
        $translation->translations()->detach($this->id);
    }
}
