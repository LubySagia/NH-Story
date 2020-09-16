<?php

namespace App\Http\Controllers;

use App\Http\Resources\StoryResource;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use \Cviebrock\EloquentSluggable\Services\SlugService;


class StoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return StoryResource::collection(Story::all()->sortByDesc('created_at'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param User $user
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'required|image',
            'content' => 'required',
            'date_time' => 'required'
        ]);
        $extension = $request->file('image')->getClientOriginalExtension();
        $request['slug'] = SlugService::createSlug(Story::class, 'slug', $request->input('title'));
        $request['cover'] = $request['slug'].'_cover.'.$extension;
        $request->file('image')->storeAs('public/story_cover/', $request['cover']);
        $story = Story::create($request->except('image'));
        return response(new StoryResource($story), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Story  $story
     * @return StoryResource
     */
    public function show(Story $story)
    {
        return new StoryResource($story);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Story $story)
    {
        $request->validate([
            'image' => 'image'
        ]);
        if ($request->input('title')) {
            $request['slug'] = Str::slug($request->input('title'), '-');
        }
        if ($request->file('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $request['cover'] = $request['slug'].'_cover.'.$extension;
            $request->file('image')->storeAs('public/story_cover/', $request['cover']);
        }
        $story->update($request->except('image'));
        return response(new StoryResource($story), Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function destroy(Story $story)
    {
        $story->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
