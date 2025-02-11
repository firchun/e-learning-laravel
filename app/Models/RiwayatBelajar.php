<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatBelajar extends Model
{
    use HasFactory;
    protected $table = 'riwayat_belajar';
    protected $guarded = [];

    public function matkul(): BelongsTo
    {
        return $this->belongsTo(Matkul::class, 'id_matkul');
    }
    public function materi(): BelongsTo
    {
        return $this->belongsTo(MateriMatkul::class, 'id_materi');
    }
}
