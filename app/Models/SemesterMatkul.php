<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemesterMatkul extends Model
{
    use HasFactory;
    protected $table = 'semester_matkul';
    protected $guarded = [];
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester');
    }
    public function matkul()
    {
        return $this->belongsTo(Matkul::class, 'id_matkul');
    }
}