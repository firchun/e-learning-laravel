<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatkulMahasiswa extends Model
{
    use HasFactory;
    protected $table = 'matkul_mahasiswa';
    protected $guarded = [];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
