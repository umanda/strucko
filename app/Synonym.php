<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Synonym extends Model
{
    protected $fillable = [
        'term_id',
        'synonym_id',
        'user_id',
    ];
    
    public function scopeGreaterThanRejected($query)
    {
        $query->where('status_id', '>', 250);
    }
    
    /**
     * Synonym is owned by user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    /**
     * Synonym belongs to term.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function term()
    {
        return $this->belongsTo('App\Term');
    }
    
    /**
     * Synonym belongs to synonym term.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function synonym()
    {
        return $this->belongsTo('App\Term');
    }
    
    /**
     * Synonym has a status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo('App\Status');
    }
    
    /**
     * Translation can have many votes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function votes()
    {
        return $this->hasMany('App\SynonymVote');
    }
}
