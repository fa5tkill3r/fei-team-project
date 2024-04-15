<?php

namespace App\Http\Controllers;

use App\Models\AdditionalIncidentInfo;
use App\Models\Incident;
use App\Models\IncidentChronologically;
use App\Models\IncidentSolution;
use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class IncidentReportPDFGenerator extends Controller
{
    public function generatePDF($incidentId)
    {
        $incident = Incident::findOrFail($incidentId);

        $additionalIncidentInfo = AdditionalIncidentInfo::where('incident_id', $incidentId)->first();

        $incidentChronologically = IncidentChronologically::where('additional_incident_info_id', $additionalIncidentInfo->id)
            ->orderBy('date', 'asc')
            ->get();
        $solutions = IncidentSolution::where('additional_incident_info_id', $additionalIncidentInfo->id)
            ->orderBy('deadline', 'asc')
            ->get();


        $task = Task::where('incident_id', $incidentId)->first();
        $comments = TaskComment::where('id', 0)->get();
        if($task){
            $comments = TaskComment::where('task_id', $task->id)->where('show_in_report', 1)->get();
        }

        $pdf = PDF::loadView('report', [
            'incident' => $incident,
            'solutions' => $solutions,
            'additional' => $additionalIncidentInfo,
            'incidentChronologically' => $incidentChronologically,
            'comments' => $comments
            ])->setPaper('a4');

        return $pdf->download('incident-report'. $incidentId . '.pdf');
    }
}
