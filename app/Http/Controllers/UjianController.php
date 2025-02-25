<?php

namespace App\Http\Controllers;

use App\Models\JawabanUjian;
use App\Models\Matkul;
use App\Models\PertanyaanUjian;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UjianController extends Controller
{
    public function create($id)
    {
        $ujian = Ujian::where("id_matkul", $id)->first();
        $matkul = Matkul::findOrFail($ujian->id_matkul);
        $data = [
            "title" => "Ujian " . $ujian->jenis . " : " . $matkul->nama_matkul,
            "matkul" => $matkul,
            "ujian" => $ujian,
        ];
        return view("admin.materi.create_exam", $data);
    }
    public function store(Request $request)
    {
        $request->validate([
            "id_matkul" => "required|exists:matkul,id",
            "keterangan" => "nullable|string",
            "jenis" => "required|in:UTS,UAS",
            "tanggal_ujian" => "required|date",
            "waktu_mulai" => "required|date",
            "waktu_selesai" => "required|date|after:waktu_mulai",
        ]);

        $ujian = Ujian::create(
            $request->only(
                "id_matkul",
                "keterangan",
                "jenis",
                "tanggal_ujian",
                "waktu_mulai",
                "waktu_selesai"
            )
        );

        return response()->json(["success" => true, "ujian" => $ujian]);
    }
    public function store_pertanyaan(Request $request)
    {
        // Validasi data input
        $request->validate([
            "id_ujian" => "required|exists:ujian,id",
            "text_pertanyaan" => "required|string",
            "jenis_pertanyaan" => "required|in:essay,pilihan_ganda",
            "pilihan" => "nullable|array",
            "pilihan.*" => "nullable|string",
            "jawaban_benar" => "nullable|string|in:a,b,c,d,e",
        ]);

        // Simpan pertanyaan ke database
        $pertanyaan = new PertanyaanUjian();
        $pertanyaan->id_ujian = $request->id_ujian;
        $pertanyaan->text_pertanyaan = $request->text_pertanyaan;
        $pertanyaan->jenis_pertanyaan = $request->jenis_pertanyaan;

        // Jika pilihan ganda, simpan pilihan dalam format JSON
        if (
            $request->jenis_pertanyaan === "pilihan_ganda" &&
            $request->has("pilihan")
        ) {
            $pertanyaan->pilihan = json_encode($request->pilihan);
            $pertanyaan->jawaban_benar = $request->jawaban_benar;
        }

        $pertanyaan->save();

        return response()->json(
            [
                "success" => true,
                "message" => "Pertanyaan berhasil disimpan!",
                "pertanyaan" => $pertanyaan,
            ],
            201
        );
    }
    public function cekKetersediaan($matkulId, $jenis)
    {
        $ujian = Ujian::where("id_matkul", $matkulId)
            ->where("jenis", $jenis)
            ->first();
        return response()->json(["tersedia" => $ujian ? true : false]);
    }
    public function getPertanyaan($id)
    {
        $pertanyaan = PertanyaanUjian::where("id_ujian", $id)->get();
        return response()->json($pertanyaan);
    }

    public function deletePertanyaan($id)
    {
        $pertanyaan = PertanyaanUjian::find($id);
        if ($pertanyaan) {
            $pertanyaan->delete();
            return response()->json(["success" => true]);
        }
        return response()->json([
            "success" => false,
            "message" => "Pertanyaan tidak ditemukan.",
        ]);
    }
    public function index()
    {
        $ujians = Ujian::all();
        return response()->json($ujians);
    }

    public function show($id)
    {
        // $ujian = Ujian::with('pertanyaanUjian')->findOrFail($id);

        // $waktuMulai = strtotime($ujian->waktu_mulai);
        // $waktuSelesai = strtotime($ujian->waktu_selesai);
        // $durasi = ($waktuSelesai - $waktuMulai) / 60; // dalam menit

        // return response()->json([
        //     'ujian' => $ujian,
        //     'durasi' => $durasi . ' menit'
        // ]);
        $ujian = Ujian::with("pertanyaanUjian")->findOrFail($id);
        $data = [
            'title' => 'Ujian',
            'ujian' => $ujian
        ];
        return view("admin.ujian.index", $data);
    }
    public function submit(Request $request, $id_ujian)
    {
        $id_user = Auth::id();

        foreach ($request->jawaban as $id_pertanyaan => $jawaban) {
            $pertanyaan = PertanyaanUjian::find($id_pertanyaan);
            $isBenar = null;

            if ($pertanyaan->jenis_pertanyaan == "pilihan_ganda") {
                $isBenar = $jawaban == $pertanyaan->jawaban_benar;
            }

            JawabanUjian::create([
                "id_user" => $id_user,
                "id_ujian" => $id_ujian,
                "id_pertanyaan" => $id_pertanyaan,
                "jawaban" => $jawaban,
                "is_benar" => $isBenar,
            ]);
        }

        return redirect()
            ->route("ujian.result", $id_ujian)
            ->with("success", "Jawaban berhasil disimpan!");
    }
    public function hasilUjian($id_ujian)
    {
        $id_user = Auth::id();
        $ujian = Ujian::findOrFail($id_ujian);
        $jawaban = JawabanUjian::where("id_user", $id_user)
            ->where("id_ujian", $id_ujian)
            ->get();

        $totalSoal = count($jawaban);
        $jawabanBenar = $jawaban->where("is_benar", true)->count();
        $nilai = $totalSoal > 0 ? ($jawabanBenar / $totalSoal) * 100 : 0;

        return view("admin.ujian.hasil", compact("ujian", "jawaban", "nilai"));
    }
}
