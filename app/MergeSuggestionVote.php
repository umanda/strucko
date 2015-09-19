<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MergeSuggestionVote extends Model
{
    protected $fillable = [
        'merge_suggestion_id',
        'user_id',
        'is_positive',
    ];
}
