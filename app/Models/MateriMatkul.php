<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class MateriMatkul extends Model
{
    use HasFactory;
    protected $table = 'materi_matkul';
    protected $guarded = [];

    public function matkul(): BelongsTo
    {
        return $this->belongsTo(Matkul::class, 'id_matkul');
    }
    static function getRiwayat($id_materi)
    {
        $riwayat = RiwayatBelajar::where('id_materi', $id_materi)->where('id_user', Auth::id())->first();
        return $riwayat ? 1 : 0;
    }
}
