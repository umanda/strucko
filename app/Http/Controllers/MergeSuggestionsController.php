<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\MergeSuggestion;
use Auth;

class MergeSuggestionsController extends Controller
{
    public function __construct()
    {
        // User has to be authenticated, except for specified methods.
        $this->middleware('auth');
    }
    
    /**
     * Show the specific merge suggestion.
     * 
     */
    public function show($id)
    {
        $mergeSuggestion = MergeSuggestion::where('id', $id)->with('term', 'concept.terms')->firstOrFail();
        
        return view('suggestions.merges.show', compact('mergeSuggestion'));
    }
    
    /**
     * Approve the sugegsted merge
     */
    public function approveMerge ()
    {
    // After the merge, reset votes_sum on the merged term.
    // Merge all terms with the same concept_id.
    // Merge all definitions with the same concept_id to the new concept_id.
    }
    
    /**
     * Give up vote for the merge suggestion
     * 
     * @param Request $request
     * @param string $id
     * @return type
     */
    public function voteUp(Request $request, $id)
    {
        $input = $request->all();
        $mergeSuggestion = MergeSuggestion::where('id', $id)->with('votes')->firstOrFail();
        
        // Set user_id
        $input['user_id'] = Auth::id();
        
        // Check if the vote already exists
        $exists = $mergeSuggestion->votes()
                ->where('user_id', $input['user_id'])
                ->exists();
        
        if ($exists) {
            return back()->with([
                    'alert' => 'You have already voted for this suggestion...',
                    'alert_class' => 'alert alert-warning'
                ]);
        }                
        
        $voteWeight = Auth::user()->role->vote_weight;
        // Vote is positive, so it is the same as vote weight from roles.
        $input['is_positive'] = true;
        
        // Create vote for the term.
        $mergeSuggestion->votes()->create($input);
        
        // Increment the votes_sum on the term
        $mergeSuggestion->increment('votes_sum', $voteWeight);
        
        return back()->with([
                    'alert' => 'Voted up!',
                    'alert_class' => 'alert alert-success'
                ]);
        
    }
    
    /**
     * Give down vote for the suggestion
     * 
     * @param Request $request
     * @param string $id
     * @return type
     */
    public function voteDown(Request $request, $id)
    {
        $input = $request->all();
        $mergeSuggestion = MergeSuggestion::where('id', $id)->with('votes')->firstOrFail();
        
        // Set user_id and concept_id for the vote.
        $input['user_id'] = Auth::id();
        
        // Check if the vote already exists
        $exists = $mergeSuggestion->votes()
                ->where('user_id', $input['user_id'])
                ->exists();
        
        if ($exists) {
            return back()->with([
                    'alert' => 'You have already voted for this suggestion...',
                    'alert_class' => 'alert alert-warning'
                ]);
        }                
        
        $voteWeight = Auth::user()->role->vote_weight;
        // Vote is down, so make it negative.
        $input['is_positive'] = false;
        
        // Create vote for the term.
        $mergeSuggestion->votes()->create($input);
        
        // Decrement the votes_sum on the term
        $mergeSuggestion->decrement('votes_sum', $voteWeight);
        
        return back()->with([
                    'alert' => 'Voted down!',
                    'alert_class' => 'alert alert-success'
                ]);
    }
}
