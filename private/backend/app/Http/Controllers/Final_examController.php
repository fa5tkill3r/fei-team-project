<?php

namespace App\Http\Controllers;

use App\Models\Final_exam;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class Final_examController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Final_exam::all(),
        ]);
    }

    public function store(Request $request)
    {
        if(!JWTAuth::parseToken()->authenticate()) {
            return response()->json([
                'message' => 'User not found',
            ], 404);    
        } 

        $final_exam = Final_exam::create($request->all());

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
        if(!JWTAuth::parseToken()->authenticate()) {
            return response()->json([
                'message' => 'User not found',
            ], 404);    
        } 

        $final_exam = Final_exam::findOrFail($id);
        $final_exam->update($request->all());

        return response()->json([
            'data' => $final_exam,
        ]);
    }

    public function destroy($id)
    {
        if(!JWTAuth::parseToken()->authenticate()) {
            return response()->json([
                'message' => 'User not found',
            ], 404);    
        } 

        Final_exam::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
