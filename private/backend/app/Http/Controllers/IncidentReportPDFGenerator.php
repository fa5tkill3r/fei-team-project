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

        $incidentChronologically = IncidentChronologically::where('additional_incident_info_id', $additionalIncidentInfo->id)->get();
        $solutions = IncidentSolution::where('additional_incident_info_id', $additionalIncidentInfo->id)->get();

        $task = Task::where('incident_id', $incidentId)->first();

        $comments = [];
        if($task){
            $comments = TaskComment::where('task_id', $task->id)->where('show_in_report', 1)->get();
        }
        Pdf::setOption(['dpi' => 150, 'defaultFont' => 'times-new-roman']);

        $pdf = PDF::loadView('report', [
            'incident' => $incident,
            'additional' => $additionalIncidentInfo,
            'incidentChronologically' => $incidentChronologically,
            'solutions' => $solutions,
            'comments' => $comments
            ]);

        return $pdf->download('incident-report.pdf');
    }
}
