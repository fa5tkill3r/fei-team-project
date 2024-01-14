<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->user = auth()->user();
    }

    public function index() 
    {
        return response()->json([
            'data' => Tag::all(),
        ]);
    }

    public function store(Request $request)
    {
        $tag = Tag::create($request->all());

        return response()->json([
            'data' => $tag,
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'data' => Tag::find($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $tag = Tag::find($id);
        $tag->update($request->all());

        return response()->json([
            'data' => $tag,
        ]);
    }
}
