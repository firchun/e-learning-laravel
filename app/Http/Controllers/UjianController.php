<?php

namespace App\Http\Controllers;

use App\Models\Matkul;
use App\Models\PertanyaanUjian;
use App\Models\Ujian;
use Illuminate\Http\Request;

class UjianController extends Controller
{
    public function create($id)
    {
        $ujian = Ujian::where('id_matkul', $id)->first();
        $matkul = Matkul::findOrFail($ujian->id_matkul);
        $data = [
            'title' => 'Ujian ' . $ujian->jenis . ' : ' . $matkul->nama_matkul,
            'matkul' => $matkul,
            'ujian' => $ujian
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

        ]);

        $ujian = Ujian::create($request->only('id_matkul', 'keterangan', 'jenis', 'tanggal_ujian', 'waktu_mulai', 'waktu_selesai'));

        return response()->json(['success' => true, 'ujian' => $ujian]);
    }
    public function store_pertanyaan(Request $request)
    {
        // Validasi data input
        $request->validate([
            'id_ujian' => 'required|exists:ujian,id',
            'text_pertanyaan' => 'required|string',
            'jenis_pertanyaan' => 'required|in:essay,pilihan_ganda',
            'pilihan' => 'nullable|array',
            'pilihan.*' => 'nullable|string',
            'jawaban_benar' => 'nullable|string|in:a,b,c,d,e'
        ]);

        // Simpan pertanyaan ke database
        $pertanyaan = new PertanyaanUjian();
        $pertanyaan->id_ujian = $request->id_ujian;
        $pertanyaan->text_pertanyaan = $request->text_pertanyaan;
        $pertanyaan->jenis_pertanyaan = $request->jenis_pertanyaan;

        // Jika pilihan ganda, simpan pilihan dalam format JSON
        if ($request->jenis_pertanyaan === 'pilihan_ganda' && $request->has('pilihan')) {
            $pertanyaan->pilihan = json_encode($request->pilihan);
            $pertanyaan->jawaban_benar = $request->jawaban_benar;
        }

        $pertanyaan->save();

        return response()->json([
            'success' => true,
            'message' => 'Pertanyaan berhasil disimpan!',
            'pertanyaan' => $pertanyaan
        ], 201);
    }
    public function cekKetersediaan($matkulId, $jenis)
    {
        $ujian = Ujian::where('id_matkul', $matkulId)->where('jenis', $jenis)->first();
        return response()->json(['tersedia' => $ujian ? true : false]);
    }
    public function getPertanyaan($id)
    {
        $pertanyaan = PertanyaanUjian::where('id_ujian', $id)->get();
        return response()->json($pertanyaan);
    }

    public function deletePertanyaan($id)
    {
        $pertanyaan = PertanyaanUjian::find($id);
        if ($pertanyaan) {
            $pertanyaan->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Pertanyaan tidak ditemukan.']);
    }
}
