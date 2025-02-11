<?php

namespace App\Http\Controllers;

use App\Models\Matkul;
use App\Models\PertanyaanUjian;
use App\Models\Ujian;
use Illuminate\Http\Request;

class UjianController extends Controller
{
    public function create($kode_matkul)
    {
        $matkul = Matkul::where('kode_matkul', $kode_matkul)->first();
        $data = [
            'title' => 'Ujian : ' . $matkul->nama_matkul,
            'matkul' => $matkul
        ];
        return view('admin.materi.create_exam', $data);
    }
    public function store(Request $request)
    {
        $request->validate([
            'id_matkul' => 'required|exists:matkul,id',
            'keterangan' => 'nullable|string',
            'jenis' => 'required|in:UTS,UAS',
            'tanggal_ujian' => 'required|date',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after:waktu_mulai',
            'questions' => 'required|array',
            'questions.*.text_pertanyaan' => 'required|string',
            'questions.*.jenis_pertanyaan' => 'required|in:pilihan_ganda,essay',
            'questions.*.pilihan' => 'nullable|string',
            'questions.*.jawaban_benar' => 'nullable|string',
        ]);

        $ujian = Ujian::create($request->only('id_matkul', 'keterangan', 'jenis', 'tanggal_ujian', 'waktu_mulai', 'waktu_selesai'));

        foreach ($request->questions as $question) {
            $data = [
                'id_ujian' => $ujian->id,
                'text_pertanyaan' => $question['text_pertanyaan'],
                'jenis_pertanyaan' => $question['jenis_pertanyaan'],
            ];

            if ($question['jenis_pertanyaan'] === 'pilihan_ganda') {
                $data['pilihan'] = json_encode(explode(',', $question['pilihan']));
                $data['jawaban_benar'] = $question['jawaban_benar'];
            }

            PertanyaanUjian::create($data);
        }

        return response()->json(['success' => true, 'ujian' => $ujian]);
    }
}
