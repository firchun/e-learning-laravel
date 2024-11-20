<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DosenMatkul extends Model
{
    use HasFactory;
    protected $table = 'dosen_matkul';
    protected $guarded = [];

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function matkul(): BelongsTo
    {
        return $this->belongsTo(Matkul::class, 'id_matkul');
    }
}