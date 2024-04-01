<?php

namespace App\Http\Controllers;

use App\Models\AdditionalIncidentInfo;
use App\Models\Incident;
use Illuminate\Http\Request;

class AdditionalInfoIncidentController extends Controller
{
    public function store(Request $request)
    {
        $incident = Incident::find($request->incident_id);


        $additionalInfo = new AdditionalIncidentInfo();
        $additionalInfo->attacked_service = $request->attacked_service;
        $additionalInfo->attack_severity = $request->attack_severity;
        $additionalInfo->attack_solution = $request->attack_solution;
        $additionalInfo->attack_description = $request->attack_description;
        $additionalInfo->attack_vector = $request->attack_vector;
        $additionalInfo->attack_category = $request->attack_category;
        $additionalInfo->attack_type = $request->attack_type;
        $additionalInfo->security_measures = $request->security_measures;
        $additionalInfo->notes = $request->notes;
        $additionalInfo->incident()->associate($incident);
        $additionalInfo->save();

        return response()->json(['message' => 'Incident created successfully'], 201);
    }

    public function update(Request $request, $id)
    {
        $additionalInfo = AdditionalIncidentInfo::where('incident_id', $id)->first();

        if ($additionalInfo) {
            $additionalInfo->update(request()->all());
            return response()->json(['message' => 'Incident updated successfully'], 200);
        } else {
            return response()->json(['message' => 'Additional info not found'], 404);
        }
    }

    public function show($id)
    {
        $additionalInfo = AdditionalIncidentInfo::where('incident_id', $id)->first();
        return response()->json(['data' => $additionalInfo], 200);
    }

    public function destroy($id)
    {
        $additionalInfo = AdditionalIncidentInfo::find($id);
        $additionalInfo->delete();
        return response()->json(['message' => 'Incident deleted successfully'], 200);
    }

    public function getEnumValues()
    {
        return response()->json([
            'attackSeverity' => AdditionalIncidentInfo::getAttackSeverityValues(),
            'attackCategory' => AdditionalIncidentInfo::getAttackCategoryValues()
        ], 200);
    }

}
