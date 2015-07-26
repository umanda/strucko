<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    protected $fillable = [
        'term',
        'abbreviation',
        'slug',
        'slug_unique',
        'synonym_id',
        'user_id',
        'language_id',
        'part_of_speech_id',
        'scientific_branch_id'
    ];
}
