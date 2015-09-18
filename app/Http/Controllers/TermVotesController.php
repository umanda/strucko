<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Term;
use Auth;

class TermVotesController extends Controller
{
    public function __construct()
    {
        // User has to be authenticated, except for specified methods.
        $this->middleware('auth');
    }
    
    /**
     * Give up vote for the term
     * 
     * @param Request $request
     * @param string $slug
     * @return type
     */
    public function voteUp(Request $request, $slug)
    {
        $input = $request->all();
        $term = Term::where('slug', $slug)->with('votes')->firstOrFail();
        
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
        // Vote is positive, so it is the same as vote weight from roles.
        $input['is_positive'] = true;
        
        // Create vote for the term.
        $term->votes()->create($input);
        
        // Increment the votes_sum on the term
        $term->increment('votes_sum', $voteWeight);
        
        return back()->with([
                    'alert' => 'Voted up!',
                    'alert_class' => 'alert alert-success'
                ]);
        
    }
    
    /**
     * Give down vote for the term
     * 
     * @param Request $request
     * @param string $slug
     * @return type
     */
    public function voteDown(Request $request, $slug)
    {
        $input = $request->all();
        $term = Term::where('slug', $slug)->with('votes')->firstOrFail();
        
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
        $input['is_positive'] = false;
        
        // Create vote for the term.
        $term->votes()->create($input);
        
        // Decrement the votes_sum on the term
        $term->decrement('votes_sum', $voteWeight);
        
        return back()->with([
                    'alert' => 'Voted down!',
                    'alert_class' => 'alert alert-success'
                ]);
    }

}
