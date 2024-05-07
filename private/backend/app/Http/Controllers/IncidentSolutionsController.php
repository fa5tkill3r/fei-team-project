<?php

namespace App\Http\Controllers;

use App\Models\AdditionalIncidentInfo;
use App\Models\IncidentSolution;
use Illuminate\Http\Request;

class IncidentSolutionsController extends Controller
{
    public function index($incidentId)
    {
        $additionalIncidentInfo = AdditionalIncidentInfo::where('incident_id', $incidentId)->first();

        $solutions = IncidentSolution::where('additional_incident_info_id', $additionalIncidentInfo->id)->get();

        return response()->json(['data' => $solutions]);
    }

    public function store(Request $request, $incidentId)
    {
        $additionalIncidentInfo = AdditionalIncidentInfo::where('incident_id', $incidentId)->first();

        $solution = new IncidentSolution();
        $solution->name = $request->name;
        $solution->description = $request->description;
        $solution->name_of_responsible_person = $request->name_of_responsible_person;
        $solution->deadline = $request->deadline;
        $solution->additional_incident_info_id = $additionalIncidentInfo->id;
        $solution->save();

        return response()->json(['data' => $solution]);
    }

    public function show($incidentId, $solutionId)
    {
        $additionalIncidentInfo = AdditionalIncidentInfo::where('incident_id', $incidentId)->first();

        $solution = IncidentSolution::where('additional_incident_info_id', $additionalIncidentInfo->id)->where('id', $solutionId)->first();

        return response()->json(['data' => $solution]);
    }

    public function update(Request $request, $incidentId, $solutionId)
    {
        $additionalIncidentInfo = AdditionalIncidentInfo::where('incident_id', $incidentId)->first();

        $solution = IncidentSolution::where('additional_incident_info_id', $additionalIncidentInfo->id)->where('id', $solutionId)->first();
        $solution->name = $request->name;
        $solution->description = $request->description;
        $solution->name_of_responsible_person = $request->name_of_responsible_person;
        $solution->deadline = $request->deadline;
        $solution->save();

        return response()->json(['data' => $solution]);
    }

    public function destroy($incidentId, $solutionId)
    {
        $additionalIncidentInfo = AdditionalIncidentInfo::where('incident_id', $incidentId)->first();

        $solution = IncidentSolution::where('additional_incident_info_id', $additionalIncidentInfo->id)->where('id', $solutionId)->first();
        $solution->delete();

        return response()->json(['data' => $solution]);
    }
}
