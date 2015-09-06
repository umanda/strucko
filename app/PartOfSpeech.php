<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartOfSpeech extends Model
{
    protected $fillable = [
        'part_of_speech',
    ];
    
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
    
    public function scopeWithout($query, $itemToRemove)
    {
        $query->where('id', '<>', $itemToRemove);
    }
    
    /**
     * Part of Speech may have many synonyms.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function synonyms()
    {
        return $this->hasMany('App\Synonym');
    }
}
