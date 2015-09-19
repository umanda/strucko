<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TermVote extends Model
{
    protected $fillable = [
        'term_id',
        'user_id',
        'concept_id',
        'is_positive',
    ];
}
