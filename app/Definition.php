<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Definition extends Model
{
    protected $fillable = [
        'definition',
        'concept_id',
        'language_id',
        'source',
        'link',
        //TODO remove user_id from fillable array after seed
        'user_id'
    ];
    
    public function scopeSuggested($query)
    {
        $query->where('status_id', 500);
    }
    
    /**
     * Definition belongs to concept.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function concept()
    {
        return $this->belongsTo('App\Concept');
    }
    
    /**
     * Definition belongs to user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    /**
     * Definition has a status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo('App\Status');
    }
    
    /**
     * Definition can have many votes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function votes()
    {
        return $this->hasMany('App\DefinitionVote');
    }
}
