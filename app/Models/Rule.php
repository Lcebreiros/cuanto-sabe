<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $fillable = ['title', 'content', 'sort_order', 'active'];

    public static function ordered()
    {
        return static::orderBy('sort_order')->orderBy('id');
    }
}
