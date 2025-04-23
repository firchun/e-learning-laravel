<?php

namespace App\Http\Controllers;

use App\Models\DosenMatkul;
use App\Models\Matkul;
use App\Models\User;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Homepage'
        ];
        return view('welcome', $data);
    }
    public function detailMatkul($id)
    {
        $matkul = Matkul::find($id);
        $data = [
            'title' => 'Detail Matakuliah',
            'matkul' => $matkul
        ];
        return view('detail_matkul', $data);
    }
    public function dosen()
    {
        $data = [
            'title' => 'Dosen',
            'dosen' => User::where('role', 'dosen')->withCount(['matkul as total_matkul' => function ($q) {}])->get(),
        ];
        return view('dosen', $data);
    }
    public function point()
    {
        $data = [
            'title' => 'Leaderboard',
            'mahasiswa' => User::where('role', 'Mahasiswa')
                ->withCount(['riwayat as total_point' => function ($q) {}])
                ->orderByDesc('total_point')
                ->take(10)
                ->get(),
        ];
        return view('point', $data);
    }
    public function cariDosen(Request $request)
    {
        $query = $request->get('search');

        $dosen = User::where('role', 'dosen')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                    ->orWhere('identity', 'like', '%' . $query . '%');
            })
            ->withCount(['matkul as total_matkul' => function ($q) {
                // Optional: tambahan filter bisa dimasukkan di sini jika perlu
            }])
            ->get();

        return response()->json($dosen);
    }
    public function cariMahasiswa(Request $request)
    {
        $query = $request->get('search');

        $mahasiswa = User::where('role', 'Mahasiswa')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                    ->orWhere('identity', 'like', '%' . $query . '%');
            })
            ->withCount(['riwayat as total_point' => function ($q) {
                // Optional: tambahan filter bisa dimasukkan di sini jika perlu
            }])
            ->orderByDesc('total_point')
            ->take(10)
            ->get();

        return response()->json($mahasiswa);
    }
}