<?php

namespace App\Http\Controllers;

use App\Models\MateriMatkul;
use App\Models\Matkul;
use App\Models\Point;
use App\Models\RiwayatBelajar;
use App\Models\Setting;
use Database\Seeders\SettingSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MateriMatkulController extends Controller
{
    public function index()
    {
        $materi = MateriMatkul::all();
        return response()->json($materi);
    }
    public function daftar_isi($kode_matkul)
    {
        $matkul = Matkul::where('kode_matkul', $kode_matkul)->first();
        $materi = MateriMatkul::where('id_matkul', $matkul->id)->get();
        $materiTerakhir = RiwayatBelajar::where('id_matkul', $matkul->id)->where('id_user', Auth::id())->latest()->first();
        $riwayat =  RiwayatBelajar::where('id_matkul', $matkul->id)->where('id_user', Auth::id())->get();
        $data = [
            'title' => 'Mata Kuliah : ' . $matkul->nama_matkul,
            'matkul' => $matkul,
            'materiTerakhir' => $materiTerakhir,
            'materi' => $materi,
            'riwayat' => $riwayat
        ];
        return view('admin.materi.daftar_isi', $data);
    }
    public function open_materi($kode_matkul)
    {
        $matkul = Matkul::where('kode_matkul', $kode_matkul)->first();
        $data = [
            'title' => 'Matakuliah : ' . $matkul->nama_matkul,
            'matkul' => $matkul
        ];
        return view('admin.materi.open', $data);
    }
    public function materi($kode_matkul)
    {
        $matkul = Matkul::where('kode_matkul', $kode_matkul)->first();
        $data = [
            'title' => 'Matakuliah : ' . $matkul->nama_matkul,
            'matkul' => $matkul
        ];
        return view('admin.materi.create', $data);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_matkul' => 'required',
            'judul' => 'required|string|max:255',
            'isi_materi' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xlsx|max:5120',
        ]);

        $data = [
            'id_matkul' => $validated['id_matkul'],
            'judul' => $validated['judul'],
            'slug' => Str::slug($validated['judul']),
            'isi_materi' => $validated['isi_materi'],
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images/materi', 'public');
        }

        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('files/materi', 'public');
        }

        $matkul = MateriMatkul::create($data);

        return response()->json(['message' => 'Berhasil menambahkan materi', 'materi' => $matkul]);
    }
    public function storeRiwayat(Request $request)
    {
        $validated = $request->validate([
            'id_matkul' => 'required',
            'id_materi' => 'required'
        ]);

        $userId = Auth::user()->id;

        // Periksa apakah riwayat sudah ada
        $existingRiwayat = RiwayatBelajar::where('id_materi', $validated['id_materi'])
            ->where('id_user', $userId)
            ->first();

        if ($existingRiwayat) {
            return response()->json([
                'message' => 'Riwayat sudah ada',
                'riwayat' => $existingRiwayat
            ], 200);
        }

        // Jika tidak ada, buat riwayat baru
        $data = [
            'id_matkul' => $validated['id_matkul'],
            'id_materi' => $validated['id_materi'],
            'id_user' => $userId,
        ];

        $jumlah_point = Setting::first();

        $point = Point::tambah([
            'id_user' => $userId,
            'point' => $jumlah_point->jumlah_point ?? 5,
        ]);

        $riwayat = RiwayatBelajar::create($data);

        return response()->json([
            'message' => 'Berhasil menambahkan progress belajar',
            'riwayat' => $riwayat,
            'point' => $point
        ], 201);
    }

    public function destroy($id)
    {
        $materi = MateriMatkul::findOrFail($id);
        $materi->delete();
        return response()->json(['message' => 'Materi berhasil dihapus'], 200);
    }
}