<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScientificArea extends Model
{
    protected $fillable = [
        'scientific_area',
        'mark',
        'description',
        'active',
    ];
    
    public function scopeActive($query) {
        return $query->where('active', 1);
    }
    
    public function scopeWithout($query, $itemToRemove)
    {
        $query->where('id', '<>', $itemToRemove);
    }
    
    /**
     * Scientific area may have many scientific fields.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scientificFields()
    {
        return $this->hasMany('App\ScientificField');
    }
}
