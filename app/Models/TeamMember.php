<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = ['name', 'role', 'description', 'photo_url', 'sort_order'];

    public static function ordered()
    {
        return static::orderBy('sort_order')->get();
    }
}
