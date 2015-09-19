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
        'link'
    ];
    /**
     * Definition belongs to synonym.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function synonym()
    {
        return $this->belongsTo('App\Synonym');
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
}
