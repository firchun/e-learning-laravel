<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;
    protected $table = 'point';
    protected $guarded = [];


    public static function tambah($data)
    {
        return self::create($data);
    }
}