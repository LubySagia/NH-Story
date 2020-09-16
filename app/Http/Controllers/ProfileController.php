<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'avatar' => 'image|required',
            'cover' => 'image|required'
        ]);
        $nextId = DB::table('profiles')->max('id') + 1;
        $avatar_name = 'avatar_'.$nextId.'.'.$request->file('avatar')->getClientOriginalExtension();
        $cover_name = 'cover_'.$nextId.'.'.$request->file('cover')->getClientOriginalExtension();
        $request->file('avatar')->storeAs('public/avatar', $avatar_name);
        $request->file('cover')->storeAs('public/cover', $cover_name);
        $profile = new Profile;
        $profile->avatar = $avatar_name;
        $profile->cover = $cover_name;
        $profile->save();

        return response(new ProfileResource($profile), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return ProfileResource
     */
    public function show(Profile $profile)
    {
        return new ProfileResource($profile);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profile $profile)
    {
        $request->validate([
            'avatar' => 'image',
            'cover' => 'image'
        ]);
        if ($request->file('avatar')) {
            $avatar_name = 'avatar_'.$profile->id.'.'.$request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->storeAs('public/avatar', $avatar_name);
            $profile->avatar = $avatar_name;
        }
        if ($request->file('cover')) {
            $cover_name = 'cover_'.$profile->id.'.'.$request->file('cover')->getClientOriginalExtension();
            $request->file('cover')->storeAs('public/cover', $cover_name);
            $profile->cover = $cover_name;
        }
        $profile->save();

        return response(new ProfileResource($profile), Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        //
    }
}
