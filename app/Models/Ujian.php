<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ujian extends Model
{
    use HasFactory;
    protected $table = 'ujian';
    protected $guarded = [];

    public function matkul(): BelongsTo
    {
        return $this->belongsTo(Matkul::class, 'id_matkul');
    }
}
