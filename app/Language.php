<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'id',
        'locale',
        'part2b',
        'part2t',
        'part1',
        'scope',
        'type',
        'ref_name',
        'comment',
    ];
    
    /**
     * Scope for languages which are available to choose from on Strucko.
     * 
     * @param type $query
     * @return type
     */
    public function scopeActive ($query)
    {
        return $query->where('active', 1);
    }
    
    /**
     * Scope for living languages.
     * 
     * @param type $query
     * @return type
     */
    public function scopeLiving ($query)
    {
        return $query->where('type', 'L');
    }
    
    public function scopeIndividual ($query)
    {
        return $query->where('scope', 'I');
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
     * Language may have many terms.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function terms()
    {
        return $this->hasMany('App\Term');
    }
}
