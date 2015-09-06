<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    /**
     * Attributes that are allowed to be inserted for the Term model.
     * Slug, slug_unique, synonym_id, user_id are defined in the store() method.
     *
     * @var array
     */
    protected $fillable = [
        'term',
        'abbreviation',
        'slug',
        'slug_unique',
        'menu_letter',
        'synonym_id',
        'user_id',
    ];

    public function scopeApproved($query)
    {
        $query->where('status_id', 1000);
    }
    
    public function scopeSuggested($query)
    {
        $query->where('status_id', 500);
    }

    /**
     * Term is owned by user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Term has a status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    /**
     * Term belongs to synonym.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function synonym()
    {
        return $this->belongsTo('App\Synonym');
    }
}
