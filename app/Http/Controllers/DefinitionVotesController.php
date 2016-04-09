<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Definition;
use Auth;

class DefinitionVotesController extends Controller
{

    public function __construct()
    {
        // User has to be authenticated.
        $this->middleware('auth');
    }

    /**
     * Give up vote for the definition
     * 
     * @param Request $request
     * @param string $id
     * @return type
     */
    public function voteUp(Request $request, $id)
    {
        $input = $request->all();
        $definition = Definition::findOrFail($id);

        // Set user_id and concept_id for the vote.
        $input['user_id'] = Auth::id();

        // Check if the vote already exists
        $exists = $definition->votes()
                ->where('user_id', $input['user_id'])
                ->exists();

        if ($exists) {
            return back()->with([
                        'alert' => trans('alerts.alreadyvoteddef'),
                        'alert_class' => 'alert alert-warning'
            ]);
        }

        $voteWeight = Auth::user()->role->vote_weight;
        // Vote is positive, so it is the same as vote weight from roles.
        $input['is_positive'] = true;

        // Create vote for the term.
        $definition->votes()->create($input);

        // Increment the votes_sum on the term
        $definition->increment('votes_sum', $voteWeight);

        return back()->with([
                    'alert' => trans('alerts.votedup'),
                    'alert_class' => 'alert alert-success'
        ]);
    }

    /**
     * Give down vote for the definition
     * 
     * @param Request $request
     * @param string $id
     * @return type
     */
    public function voteDown(Request $request, $id)
    {
        $input = $request->all();
        $definition = Definition::findOrFail($id);

        // Set user_id and concept_id for the vote.
        $input['user_id'] = Auth::id();

        // Check if the vote already exists
        $exists = $definition->votes()
                ->where('user_id', $input['user_id'])
                ->exists();

        if ($exists) {
            return back()->with([
                        'alert' => trans('alerts.alreadyvoteddef'),
                        'alert_class' => 'alert alert-warning'
            ]);
        }

        $voteWeight = Auth::user()->role->vote_weight;
        // Vote is down, so make it negative.
        $input['is_positive'] = false;

        // Create vote for the term.
        $definition->votes()->create($input);

        // Decrement the votes_sum on the term
        $definition->decrement('votes_sum', $voteWeight);

        return back()->with([
                    'alert' => trans('alerts.voteddown'),
                    'alert_class' => 'alert alert-success'
        ]);
    }

}
