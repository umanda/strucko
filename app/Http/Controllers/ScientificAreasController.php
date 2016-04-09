<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ScientificArea;

class ScientificAreasController extends Controller
{
    public function __construct()
    {
        // User has to be authenticated, except for specified methods.
        $this->middleware('auth');
        // Check if user has Administrator role, except for specified methods.
        $this->middleware('role:1000');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // Get areas and scientific fields.
        $areas = ScientificArea::orderBy('mark')->get();
        //$areas = $areas->sortBy('mark');
        
        return view('areas.index', compact('areas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('areas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        // Checkboxes:
        $request->has('active') ? $input['active'] = 1 : $input['active'] = 0;
        
        $area = ScientificArea::create($input);
        return redirect(action('ScientificAreasController@show', $area->id))
            ->with([
                    'alert' => trans('alerts.areacreated'),
                    'alert_class' => 'alert alert-success'
                ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $area = ScientificArea::findOrFail($id);
        return view('areas.show', compact('area'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $area = ScientificArea::findOrFail($id);
        return view('areas.edit', compact('area'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        // Checkboxes:
        $request->has('active') ? $input['active'] = 1 : $input['active'] = 0;
        
        ScientificArea::find($id)->update($input);
        return redirect(action('ScientificAreasController@show', $id))
            ->with([
                    'alert' => trans('alerts.areaedited'),
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
        ScientificArea::destroy($id);
        return redirect(action('ScientificAreasController@index'))
                ->with([
                    'alert' => trans('alerts.areadeleted'),
                    'alert_class' => 'alert alert-success'
                ]);
    }
}
