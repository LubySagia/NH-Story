<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class SongResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'artist' => $this->artist,
            'path' => $this->path,
            'lyric' => $this->lyric,
            'cover_image' => (($this->cover) ? asset("storage/song_cover/$this->cover") : null),
            'audio_file' => asset("storage/song_file/$this->slug".'.'."$this->file_type"),
        ];
    }
}
