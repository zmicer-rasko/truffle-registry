<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class Truffle extends Authenticatable
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'sku',
        'weight',
        'price',
        'user_id',
        'source_type',
        'created_at',
        'expires_at',
        'exported_at',
    ];

    /**
     * @return string
     */
    public static function generateSku()
    {
        return (string)Str::uuid();
    }
}
