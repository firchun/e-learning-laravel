<?php

namespace App\Http\Controllers;

use App\Models\RiwayatBelajar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class RiwayatBelajarController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Riwayat Belajar'
        ];
        return view('admin.riwayat_belajar.index', $data);
    }
    public function getRiwayatDataTable(Request $request)
    {
        $riwayat = RiwayatBelajar::with(['matkul', 'materi','user'])->orderByDesc('id');
        if (Auth::user()->role == 'Mahasiswa') {
            $riwayat->where('id_user', Auth::id());
        }
        if ($request->has('id_mahasiswa')) {
            $riwayat->where('id_user', $request->input('id_mahasiswa'));
        }
        return DataTables::of($riwayat)

            ->addColumn('tanggal', function ($riwayat) {
                return $riwayat->created_at->format('d F Y') . '<br><small>' . $riwayat->created_at->format('H:i A') . '</small>';
            })
            ->rawColumns(['tanggal'])
            ->make(true);
    }
}