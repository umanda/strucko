<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TranslationVote extends Model
{
    protected $fillable = [
        'translation_id',
        'user_id',
        'is_positive',
    ];
}
