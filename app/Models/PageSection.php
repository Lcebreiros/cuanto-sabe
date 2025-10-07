<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    protected $fillable = ['key', 'content'];

    public static function getContent(string $key, string $default = '')
    {
        return static::firstWhere('key', $key)->content ?? $default;
    }

    public static function setContent(string $key, string $content): void
    {
        static::updateOrCreate(['key' => $key], ['content' => $content]);
    }
}
