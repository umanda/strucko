<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Language;

class LanguagesController extends Controller
{
    public function __construct()
    {
        // User has to be authenticated.
        $this->middleware('auth');
        // Check if user has Administrator role.
        $this->middleware('role:1000');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $languages = Language::active()
                ->living()
                ->individual()
                ->orderBy('ref_name')
                ->paginate(25);
        
        return view('languages.index', compact('languages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $language = Language::findOrFail($id);
        
        return view('languages.show', compact('language'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $language = Language::findOrFail($id);
        
        return view('languages.edit', compact('language'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Requests\EditLanguageRequest $request, $id)
    {
        $input = $request->all();
        // Checkboxes:
        $request->has('active') ? $input['active'] = 1 : $input['active'] = 0;
        
        Language::findOrFail($id)->update($input);
        
        return redirect(url('languages', $input['id']))
                ->with([
                    'alert' => 'Language edited...',
                    'alert_class' => 'alert alert-success'
                ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
