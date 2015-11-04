<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Definition;

class DefinitionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:1000', ['except' => [
            'store',
            ]]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Requests\CreateDefinitionRequest $request)
    {
        $input = $request->all();
        // Prepare optional values.
        $input['source'] = getNullForOptionalInput($input['source']);
        $input['link'] = getNullForOptionalInput($input['link']);
        
        // Save the definition using relationship with user.
        Auth::user()->definitions()->create($input);
        
        // Return back with alerts in session.
        return back()->with([
            'alert' => 'Definition suggested...',
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
                    'alert' => 'Definition approved...',
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
                    'alert' => 'Definition rejected...',
                    'alert_class' => 'alert alert-success'
        ]);
    }

}
