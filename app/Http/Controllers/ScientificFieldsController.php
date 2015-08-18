<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ScientificField;
use App\ScientificArea;

class ScientificFieldsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($areaId)
    {
        $fields = ScientificField::where('scientific_area_id', $areaId)
                ->with('scientificArea')
                ->orderBy('mark')
                ->get();
        return view('areas.fields.index', compact('fields'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('areas.fields.create');
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
        
        $field = ScientificField::create($input);
        return redirect(action('ScientificFieldsController@show', $field->id))
            ->with([
                    'alert' => 'Field created...',
                    'alert_class' => 'alert alert-success'
                ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($areaId, $fieldId)
    {
        $field = ScientificField::where('scientific_area_id', $areaId)
                ->where('id', $fieldId)->firstOrFail();
        return view('areas.fields.show', compact('field'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($areaId, $fieldId)
    {
        $field = ScientificField::where('id', $fieldId)
                ->with('scientificArea')
                ->firstOrFail();
        
        $areas = ScientificArea::without($field->scientific_area_id)->get();
        
        return view('areas.fields.edit', compact('field', 'areas'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $areaId, $fieldId)
    {
        $input = $request->all();
        // Checkboxes:
        $request->has('active') ? $input['active'] = 1 : $input['active'] = 0;
        
        ScientificField::findOrFail($fieldId)->update($input);
        
        return redirect(action('ScientificFieldsController@show', [$areaId, $fieldId]))
            ->with([
                    'alert' => 'Field edited...',
                    'alert_class' => 'alert alert-success'
                ]);;
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
