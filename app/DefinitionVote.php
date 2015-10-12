<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DefinitionVote extends Model
{
    protected $fillable = [
        'definition_id',
        'language_id',
        'user_id',
        'concept_id',
        'is_positive',
    ];
}
