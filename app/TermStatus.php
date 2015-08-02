<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TermStatus extends Model
{
    /**
     * TermStatus may have many terms.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function terms()
    {
        return $this->hasMany('App\Term');
    }
}
