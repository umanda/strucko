<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Definition;
use App\Term;
use App\Status;

class DefinitionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:1000', ['except' => [
            'store', // Protected by EditDefinitionRequest
            'edit', // Protected by EditDefinitionRequest
            'update' // Protected by EditDefinitionRequest
            ]]);
        // Check spam threshold.
        $this->middleware('spam', ['only' => ['store']]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Requests\EditDefinitionRequest $request)
    {
        $input = $request->all();
        // Prepare optional values.
        $input['source'] = getNullForOptionalInput($input['source']);
        $input['link'] = getNullForOptionalInput($input['link']);
        
        // Save the definition using relationship with user.
        Auth::user()->definitions()->create($input);
        
        // Return back with alerts in session.
        return back()->with([
            'alert' => trans('alerts.defsuggested'),
            'alert_class' => 'alert alert-success'
        ]);
        
    }
    
    public function edit($id, Requests\EditDefinitionRequest $request)
    {
        $definition = Definition::findOrFail($id);
        $term = Term::findOrFail($definition->term_id);
        return view('definitions.edit', compact('definition', 'term'));
    }
    
    public function update($id, Requests\EditDefinitionRequest $request)
    {
        $definition = Definition::where('id', $id)
                ->with('term')
                ->firstOrFail();
        $input = $request->all();
        $definition->definition = $input['definition'];
        // Prepare optional values.
        $definition->source = getNullForOptionalInput($input['source']);
        $definition->link = getNullForOptionalInput($input['link']);
        // If definition was approved, set status to Edited.
        if ($definition->status_id > 750) {
            $definition->status_id = 750;
        }
        $definition->save();
        return redirect(resolveUrlAsAction('TermsController@show', ['slug' => $definition->term->slug]))
                        ->with([
                            'alert' => trans('alerts.defupdated'),
                            'alert_class' => 'alert alert-success'
        ]);
    }
    
    /**
     * Set the status of the definition to approved.
     * 
     * @param int $id ID of the definition
     * @return \Illuminate\Http\RedirectResponse Go back
     */
    public function approve($id)
    {
        $definition = Definition::findOrFail($id);

        $definition->status_id = 1000;

        $definition->save();

        return back()->with([
                    'alert' => trans('alerts.defapproved'),
                    'alert_class' => 'alert alert-success'
        ]);
    }
    
    /**
     * Set the status of the definition to rejected.
     * 
     * @param int $id ID of the definition
     * @return \Illuminate\Http\RedirectResponse Go back
     */
    public function reject($id)
    {
        $definition = Definition::findOrFail($id);

        $definition->status_id = 250;

        $definition->save();

        return back()->with([
                    'alert' => trans('alerts.defrejected'),
                    'alert_class' => 'alert alert-success'
        ]);
    }

}
