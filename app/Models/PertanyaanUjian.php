<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PertanyaanUjian extends Model
{
    use HasFactory;
    protected $table = 'pertanyaan_ujian';
    protected $guarded = [];

    public function ujian(): BelongsTo
    {
        return $this->belongsTo(Ujian::class, 'id_ujian');
    }
}
