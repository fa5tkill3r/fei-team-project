<?php

namespace App\Http\Controllers;

use App\Models\Final_exam;
use Illuminate\Http\Request;
use Spatie\WebhookServer\WebhookCall;
use Tymon\JWTAuth\Facades\JWTAuth;

class Final_examController extends Controller
{
    private function sendWebhook(string $event, $data): void
    {
        WebhookCall::create()
            ->url(env('PUBLIC_WEBHOOK_URL'))
            ->payload([
                'event' => $event,
                'data' => $data,
            ])
            ->dispatch();
    }

    public function index()
    {
        return response()->json([
            'data' => Final_exam::all(),
        ]);
    }

    public function store(Request $request)
    {
        if (!JWTAuth::parseToken()->authenticate()) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $final_exam = Final_exam::create($request->all());

        $this->sendWebhook('final_exam.created', $final_exam);

        return response()->json([
            'data' => $final_exam,
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'data' => Final_exam::find($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!JWTAuth::parseToken()->authenticate()) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $final_exam = Final_exam::findOrFail($id);
        $final_exam->update($request->all());

        $this->sendWebhook('final_exam.updated', $final_exam);

        return response()->json([
            'data' => $final_exam,
        ]);
    }

    public function destroy($id)
    {
        if (!JWTAuth::parseToken()->authenticate()) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        Final_exam::findOrFail($id)->delete();

        $this->sendWebhook('final_exam.deleted', $id);

        return response()->json(null, 204);
    }
}
