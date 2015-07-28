<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    /**
     * Attributes that are allowed to be inserted for the Term model.
     * 
     * @var array
     */
    protected $fillable = [
        'term',
        'abbreviation',
        'slug',
        'slug_unique',
        'synonym_id',
        'user_id',
        'language_id',
        'term_status_id',
        'part_of_speech_id',
        'scientific_branch_id'
    ];
    
    // TODO: implement the scopeApproved
    public function scopeApproved($query) {
        $query->where('term_status_id', 3);
    }
}
