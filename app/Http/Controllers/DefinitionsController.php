<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

class DefinitionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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

}
