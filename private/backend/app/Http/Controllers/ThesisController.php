<?php

namespace App\Http\Controllers;

use App\Models\Thesis;
use Illuminate\Http\Request;
use Spatie\WebhookServer\WebhookCall;
use Tymon\JWTAuth\Facades\JWTAuth;

class ThesisController extends Controller
{
    private function sendWebhook(string $event, $data): void
    {
        WebhookCall::create()
            ->url(env('PUBLIC_WEBHOOK_URL'))
            ->payload([
                'event' => $event,
                'data' => $data,
            ])
            ->doNotSign()
            ->dispatch();
    }

    public function index()
    {
        return response()->json([
            'data' => Thesis::all(),
        ]);
    }

    public function store(Request $request)
    {
//        if (!JWTAuth::parseToken()->authenticate()) {
//            return response()->json([
//                'message' => 'User not found',
//            ], 404);
//        }

        $final_exam = Thesis::create($request->all());

        $this->sendWebhook('final_exam.created', $final_exam);

        return response()->json([
            'data' => $final_exam,
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'data' => Thesis::find($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!JWTAuth::parseToken()->authenticate()) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $final_exam = Thesis::findOrFail($id);
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

        Thesis::findOrFail($id)->delete();

        $this->sendWebhook('final_exam.deleted', $id);

        return response()->json(null, 204);
    }
}
