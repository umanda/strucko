<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScientificBranch extends Model
{
    protected $fillable = [
        'scientific_branch',
        'scientific_field_id',
        'mark',
        'description',
        'active'
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
     * Scientific branch belongs to scientific field.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scientificField()
    {
        return $this->belongsTo('App\ScientificField');
    }
}
