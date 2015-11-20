<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SynonymVote extends Model
{
    protected $fillable = [
        'synonym_id',
        'user_id',
        'is_positive',
    ];
}
