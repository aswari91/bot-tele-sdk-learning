<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TgConfig extends Model
{
    protected $fillable = ['key', 'value'];

    public static function getValue(string $key): ?string
    {
        $config = self::where('key', $key)->first();
        return $config ? $config->value : null;
    }
}
