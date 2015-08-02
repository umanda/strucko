<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartOfSpeech extends Model
{
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
