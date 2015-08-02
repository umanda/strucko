<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScientificBranch extends Model
{
    /**
     * Scientific branch belongs to scientific field.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scientificField()
    {
        return $this->belongsTo('App\ScientificField');
    }

    /**
     * Scientific branch may have many terms.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function terms()
    {
        return $this->hasMany('App\Term');
    }
}
