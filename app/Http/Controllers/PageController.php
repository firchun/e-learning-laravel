<?php

namespace App\Http\Controllers;

use App\Models\DosenMatkul;
use App\Models\Matkul;
use App\Models\MatkulMahasiswa;
use App\Models\Point;
use App\Models\RiwayatBelajar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Homepage'
        ];
        return view('welcome', $data);
    }
    public function pencapaian()
    {
        $matkulProgress = MatkulMahasiswa::where('id_user', Auth::id())
        ->with('matkul')
        ->get()
        ->map(function ($item) {
            // Hitung progress dari jumlah riwayat belajar
            $totalRiwayat = RiwayatBelajar::where('id_matkul', $item->id_matkul)
                ->where('id_user', Auth::id())
                ->count();

            // Kamu bisa atur berapa target riwayat supaya 100%
            $target = 10; // contoh target 10 kegiatan belajar

            $progress = $target > 0 ? min(100, ($totalRiwayat / $target) * 100) : 0;

            return (object)[
                'id' => $item->id_matkul,
                'nama_matkul' => $item->matkul->nama_matkul ?? '-',
                'progress' => round($progress),
               
            ];
        });
    
        $data = [
            'title' => 'Capaian Belajar',
            'matkulProgress' => $matkulProgress,
            'totalPoint'=> Point::where('id_user', Auth::id())->sum('point')
        ];
    
        return view('admin.pencapaian.index', $data);
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
           'mahasiswa' => User::select('users.*')
                    ->selectSub(function ($query) {
                        $query->from('point')
                            ->selectRaw('COALESCE(SUM(point), 0)')
                            ->whereColumn('point.id_user', 'users.id');
                    }, 'total_point')
                    ->where('role', 'Mahasiswa')
                    ->having('total_point', '>', 0)
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
            ->withCount(['matkul as total_matkul' => function ($q) {}])
            ->get();

        return response()->json($dosen);
    }
    public function cariMahasiswa(Request $request)
    {
        $query = $request->get('search');

            $mahasiswa =  User::select('users.*')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                    ->orWhere('identity', 'like', '%' . $query . '%');
            })
            ->selectSub(function ($query) {
                $query->from('point')
                    ->selectRaw('COALESCE(SUM(point), 0)')
                    ->whereColumn('point.id_user', 'users.id');
            }, 'total_point')
            ->where('role', 'Mahasiswa')
            ->having('total_point', '>', 0)
            ->orderByDesc('total_point')
            ->take(10)
            ->get();

        return response()->json($mahasiswa);
    }
}