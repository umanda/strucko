<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SynonymVote extends Model
{
    protected $fillable = [
        'term_id',
        'synonym_id',
        'user_id',
        'vote'
    ];
}
