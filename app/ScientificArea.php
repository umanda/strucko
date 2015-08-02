<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScientificArea extends Model
{
    /**
     * Scientific area may have many scientific fields.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scientificFields()
    {
        return $this->hasMany('App\ScientificField');
    }
}
