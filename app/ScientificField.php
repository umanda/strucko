<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScientificField extends Model
{
    protected $fillable = [
        'scientific_field',
        'mark',
        'scientific_area_id',
        'description',
        'active'
    ];
    
    public function scopeActive($query) {
        return $query->where('active', 1);
    }
    
    public function scopeWithout($query, $itemToRemove)
    {
        $query->where('id', '<>', $itemToRemove);
    }    

    /**
     * Scientific field may have many terms.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function terms()
    {
        return $this->hasMany('App\Term');
    }
    
    /**
     * Scientific field belongs to scientific area.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scientificArea()
    {
        return $this->belongsTo('App\ScientificArea');
    }

    /**
     * Scientific field may have many scientific branches.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scientificBranches()
    {
        return $this->hasMany('App\ScientificBranch');
    }
}
