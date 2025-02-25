<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ujian extends Model
{
    use HasFactory;
    protected $table = 'ujian';
    protected $guarded = [];

    public function matkul(): BelongsTo
    {
        return $this->belongsTo(Matkul::class, 'id_matkul');
    }
    public function  pertanyaanUjian(): HasMany
    {
        return $this->HasMany(PertanyaanUjian::class, 'id_ujian');
    }
}
