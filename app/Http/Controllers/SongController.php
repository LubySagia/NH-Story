<?php

namespace App\Http\Controllers;

use App\Http\Resources\SongResource;
use App\Models\Song;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SongController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return SongResource::collection(Song::all()->sortByDesc('created_at'));
    }

    /**

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'audio_file' => 'required|mimes:mp3,flac,wav,ogg',
            'cover_image' => 'image'
        ]);
        $audio_extension = $request->file('audio_file')->getClientOriginalExtension();
        $request['slug'] = SlugService::createSlug(Song::class, 'slug', $request->input('title'));
        $request['file_type'] = $audio_extension;
        $request->file('audio_file')->storeAs('public/song_file/', $request['slug'].$audio_extension);
        if ($request->file('cover_image')) {
            $cover_extension = $request->file('cover_image')->getClientOriginalExtension();
            $request['cover'] = $request['slug'].'_cover.'.$cover_extension;
            $request->file('cover_image')->storeAs('public/song_cover/', $request['cover']);
        }
        $song = Song::create($request->except('audio_file', 'cover_image'));
        return response(new SongResource($song), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Song  $song
     * @return SongResource
     */
    public function show(Song $song)
    {
        return new SongResource($song);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Song  $song
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Song $song)
    {
        $request->validate([
            'audio_file' => 'mimes:mp3,flac,wav,ogg',
            'cover_image' => 'image'
        ]);
        if ($request->input('title')) {
            $request['slug'] = SlugService::createSlug(Song::class, 'slug', $request->input('title'));
        }
        if ($request->file('audio_file')) {
            $audio_extension = $request->file('audio_file')->getClientOriginalExtension();
            $request['file_type'] = $audio_extension;
            $request->file('audio_file')->storeAs('public/song_file/', $request['slug'].$audio_extension);
        }
        if ($request->file('cover_image')) {
            $cover_extension = $request->file('cover_image')->getClientOriginalExtension();
            $request['cover'] = $request['slug'].'_cover.'.$cover_extension;
            $request->file('cover_image')->storeAs('public/song_cover/', $request['cover']);
        }
        $song->update($request->except('audio_file', 'cover_image'));
        return response(new SongResource($song), Response::HTTP_CREATED);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Song  $song
     * @return \Illuminate\Http\Response
     */
    public function destroy(Song $song)
    {
        $song->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
