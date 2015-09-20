<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Synonym merge suggestion model.
 * 
 */
class MergeSuggestion extends Model
{
    protected $fillable = [
        'term_id',
        'concept_id',
    ];
    
    /**
     * Merge suggestion has a status.
     * 
     * @return type
     */
    public function status()
    {
        return $this->belongsTo('App\Status');
    }
    
    /**
     * Merge suggestion belongs to user.
     * 
     * @return type
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    /**
     * Merge suggestion belongs to Concept.
     * 
     * @return type
     */
    public function concept()
    {
        return $this->belongsTo('App\Concept');
    }
    
    /**
     * Merge suggestion belongs to term.
     * 
     * @return type
     */
    public function term()
    {
        return $this->belongsTo('App\Term');
    }
    
}
