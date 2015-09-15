<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Synonym merge suggestion model.
 * 
 */
class MergeSuggestion extends Model
{
    protected $fillable = [
        'synonym_id',
        'merge_id',
    ];
    
    /**
     * Merge suggestion has a status.
     * 
     * @return type
     */
    public function status()
    {
        return $this->belongsTo('App\Status');
    }
    
    /**
     * Merge suggestion belongs to user.
     * 
     * @return type
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    /**
     * Merge suggestion belongs to synonym.
     * 
     * @return type
     */
    public function synonym()
    {
        return $this->belongsTo('App\Synonym');
    }
    
    /**
     * Merge suggestion has a merged synonym suggestion.
     * 
     * @return type
     */
    public function mergedSynonym()
    {
        return $this->belongsTo('App\Synonym', 'merge_id');
    }
}
