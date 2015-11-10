<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    /**
     * Attributes that are allowed to be inserted for the Term model.
     * Slug, user_id are defined in the store() method.
     *
     * @var array
     */
    protected $fillable = [
        'term',
        'slug',
        'menu_letter',
        'user_id',
        'language_id',
        'part_of_speech_id',
        'scientific_field_id',
        'is_abbreviation',
    ];

    public function scopeApproved($query)
    {
        $query->where('status_id', 1000);
    }
    
    public function scopeSuggested($query)
    {
        $query->where('status_id', 500);
    }
    
    public function scopeGreaterThanRejected($query)
    {
        $query->where('status_id', '>', 250);
    }
    
    /**
     * 
     * @param type $query
     * @param type $itemToRemove
     */
    public function scopeWithout($query, $itemToRemove)
    {
        $query->where('id', '<>', $itemToRemove);
    }

    /**
     * Term is owned by user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Term has a status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    /**
     * Term belongs to Concept.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function concept()
    {
        return $this->belongsTo('App\Concept');
    }
    
    /**
     * Term can have many votes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function votes()
    {
        return $this->hasMany('App\TermVote');
    }
    
    /**
     * Term can have many synonym votes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function synonymVotes()
    {
        return $this->hasMany('App\SynonymVote');
    }
    
    /**
     * Term can have many synonym votes. This is the 
     * same as synonymVotes, except that I use it to retreive 
     * votes for specific user when defining the $query.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function synonymUserVote()
    {
        return $this->hasMany('App\SynonymVote');
    }
    
    /**
     * Terms is in a language.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo('App\Language');
    }
    
    /**
     * Term belongs to scientific branch.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scientificField()
    {
        return $this->belongsTo('App\ScientificField');
    }
    
    /**
     * Term belongs to part of speech.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partOfSpeech()
    {
        return $this->belongsTo('App\PartOfSpeech');
    }
    
    /**
     * Term can have many merge suggestions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mergeSuggestions()
    {
        return $this->hasMany('App\MergeSuggestion');
    }
}
