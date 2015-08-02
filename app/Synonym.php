<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Synonym extends Model
{
    /**
     * Synonym may have many terms.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function terms()
    {
        return $this->hasMany('App\Term');
    }
}
