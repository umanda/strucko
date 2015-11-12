<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TranslationVote extends Model
{
    protected $fillable = [
        'term_id',
        'translation_id',
        'user_id',
        'vote'
    ];
}
