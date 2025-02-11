<?php

namespace App\Models;

use GuzzleHttp\RetryMiddleware;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matkul extends Model
{
    use HasFactory;

    protected $table = 'matkul';
    protected $guarded = [];
    public function dosen(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'dosen_matkul', 'id_matkul', 'id_user');
    }
    static function getMahasiswa($id)
    {
        $matkulMahasiswa = MatkulMahasiswa::where('id_matkul', $id)->count();
        return $matkulMahasiswa ?? 0;
    }
}
