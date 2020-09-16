<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Story extends Model
{
    use Sluggable;
    protected $guarded = [];

    public function getPathAttribute() {
        return asset("api/stories/$this->id");
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
