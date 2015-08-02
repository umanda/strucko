<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScientificField extends Model
{
    /**
     * Scientific field belongs to scientific area.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scientificArea()
    {
        return $this->belongsTo('App\ScientificArea');
    }

    /**
     * Scientific field may have many scientific branches.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scientificBranches()
    {
        return $this->hasMany('App\ScientificBranch');
    }
}
