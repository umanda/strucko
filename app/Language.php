<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'id',
        'part2b',
        'part2t',
        'part1',
        'scope',
        'type',
        'ref_name',
        'comment',
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
     * Language may have many terms.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function terms()
    {
        return $this->hasMany('App\Term');
    }
}
