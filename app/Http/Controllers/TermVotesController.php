<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\TermVote;
use App\Term;
use Auth;

class TermVotesController extends Controller
{
    public function __construct()
    {
        // User has to be authenticated, except for specified methods.
        $this->middleware('auth');
    }
    
    public function voteUp(Request $request, $slugUnique)
    {
        $input = $request->all();
        $term = Term::where('slug_unique', $slugUnique)->firstOrFail();
        
        $input['user_id'] = Auth::id();
        // Check if the vote already exists
        $exists = $term->votes()->where('user_id', $input['user_id'])->exists();
        
        if ($exists) {
            return back()->with([
                    'alert' => 'You have already voted...',
                    'alert_class' => 'alert alert-warning'
                ]);
        }                
        
        $voteWeight = Auth::user()->role->vote_weight;
        // Vote is positive, so it is the same as vote weight from roles.
        $input['vote'] = $voteWeight;
        
        // Create vote for the term.
        $term->votes()->create($input);
        
        return back()->with([
                    'alert' => 'Voted up!',
                    'alert_class' => 'alert alert-success'
                ]);
        
    }
    
    public function voteDown(Request $request, $slugUnique)
    {
        $input = $request->all();
        $term = Term::where('slug_unique', $slugUnique)->firstOrFail();
        
        $input['user_id'] = Auth::id();
        // Check if the vote already exists
        $exists = $term->votes()->where('user_id', $input['user_id'])->exists();
        
        if ($exists) {
            return back()->with([
                    'alert' => 'You have already voted for this term...',
                    'alert_class' => 'alert alert-warning'
                ]);
        }                
        
        $voteWeight = Auth::user()->role->vote_weight;
        // Vote is down, so make it negative.
        $input['vote'] = $voteWeight * (-1);
        
        // Create vote for the term.
        $term->votes()->create($input);
        
        return back()->with([
                    'alert' => 'Voted down!',
                    'alert_class' => 'alert alert-success'
                ]);
    }

}
