<?php

namespace App\Http\Controllers;

use App\Models\AdditionalIncidentInfo;
use App\Models\IncidentChronologically;
use Illuminate\Http\Request;

class IncidentChronologicallyController extends Controller
{
    public function index($incidentId)
    {
        $additionalIncidentInfo = AdditionalIncidentInfo::where('incident_id', $incidentId)->first();

        $incidentChronologically = IncidentChronologically::where('additional_incident_info_id', $additionalIncidentInfo->id)->get();

        return response()->json(['data' => $incidentChronologically]);
    }

    public function store(Request $request, $incidentId)
    {
        $additionalIncidentInfo = AdditionalIncidentInfo::where('incident_id', $incidentId)->first();

        $incidentChronologically = new IncidentChronologically();
        $incidentChronologically->description = $request->description;
        $incidentChronologically->date = $request->date;
        $incidentChronologically->additional_incident_info_id = $additionalIncidentInfo->id;
        $incidentChronologically->save();

        return response()->json(['data' => $incidentChronologically]);
    }

    public function show($incidentId, $incidentChronologicallyId)
    {
        $additionalIncidentInfo = AdditionalIncidentInfo::where('incident_id', $incidentId)->first();

        $incidentChronologically = IncidentChronologically::where('additional_incident_info_id', $additionalIncidentInfo->id)->where('id', $incidentChronologicallyId)->first();

        return response()->json(['data' => $incidentChronologically]);
    }

    public function update(Request $request, $incidentId, $incidentChronologicallyId)
    {
        $additionalIncidentInfo = AdditionalIncidentInfo::where('incident_id', $incidentId)->first();

        $incidentChronologically = IncidentChronologically::where('additional_incident_info_id', $additionalIncidentInfo->id)->where('id', $incidentChronologicallyId)->first();
        $incidentChronologically->description = $request->description;
        $incidentChronologically->date = $request->date;
        $incidentChronologically->save();

        return response()->json(['data' => $incidentChronologically]);
    }

    public function destroy($incidentId, $incidentChronologicallyId)
    {
        $additionalIncidentInfo = AdditionalIncidentInfo::where('incident_id', $incidentId)->first();
        $incidentChronologically = IncidentChronologically::where('additional_incident_info_id', $additionalIncidentInfo->id)->where('id', $incidentChronologicallyId)->first();
        $incidentChronologically->delete();

        return response()->json(['data' => $incidentChronologically]);
    }
}
