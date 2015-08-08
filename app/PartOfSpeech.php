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
     * Part of Speech may have many terms.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function terms()
    {
        return $this->hasMany('App\Term');
    }
}
