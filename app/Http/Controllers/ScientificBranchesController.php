<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ScientificBranch;
use App\ScientificField;

class ScientificBranchesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($areaId, $fieldId)
    {
        // TODO Deal this with model relationships.
        $branches = ScientificBranch::where('scientific_field_id', $fieldId)
                ->with('scientificField', 'scientificField.scientificArea')
                ->orderBy('mark')
                ->get();
        return view('areas.fields.branches.index', compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($areaId, $fieldId)
    {
        $fields = ScientificField::where('id', $fieldId)->get();
        return view('areas.fields.branches.create', compact('fields', 'areaId', 'fieldId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request, $areaId, $fieldId)
    {
        $input = $request->all();
        // Checkboxes:
        $request->has('active') ? $input['active'] = 1 : $input['active'] = 0;
        $branch = ScientificBranch::create($input);
        return redirect(action('ScientificBranchesController@show', [$areaId, $fieldId, $branch->id]))
            ->with([
                    'alert' => 'Branch created...',
                    'alert_class' => 'alert alert-success'
                ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($areaId, $fieldId, $branchId)
    {
        $branch = ScientificBranch::where('scientific_field_id', $fieldId)
                ->where('id', $branchId)
                ->firstOrFail();
        return view('areas.fields.branches.show', compact('areaId', 'branch'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($areaId, $fieldId, $branchId)
    {
        $branch = ScientificBranch::where('id', $branchId)
                ->with('scientificField', 'scientificField.scientificArea')
                ->firstOrFail();
        
        $fields = ScientificField::without($branch->scientific_field_id)->get();
        
        return view('areas.fields.branches.edit', compact('branch', 'fields'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $areaId, $fieldId, $branchId)
    {
        $input = $request->all();
        // Checkboxes:
        $request->has('active') ? $input['active'] = 1 : $input['active'] = 0;
        
        ScientificBranch::findOrFail($branchId)->update($input);
        
        return redirect(action('ScientificBranchesController@show', [$areaId, $fieldId, $branchId]))
            ->with([
                    'alert' => 'Branch edited...',
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
