<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Resources\TagResource;

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
        return TagResource::collection(Tag::all());
        
    }

    public function store(Request $request)
    {
        $tag = Tag::create($request->all());
        return new TagResource($tag);
    }

    public function show($id)
    {
        return new TagResource(Tag::find($id));
    }

    public function update(Request $request, $id)
    {
        $tag = Tag::find($id);
        $tag->update($request->all());

        return new TagResource($tag);
    }

    public function destroy($id)
    {
        $tag = Tag::find($id);
        $tag->delete();

        return new TagResource($tag);
    }

    
}
