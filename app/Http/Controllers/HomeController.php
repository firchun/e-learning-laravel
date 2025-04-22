<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DosenMatkul;
use App\Models\Matkul;
use App\Models\MatkulMahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $matkulDosen = DosenMatkul::where('id_user', Auth::id())->get();
        $matkulIds = $matkulDosen->pluck('id_matkul');

        $data = [
            'title' => 'Dashboard',
            'dosen' => User::where('role', 'Dosen')->count(),
            'mahasiswa' => User::where('role', 'Mahasiswa')->count(),
            'matkul' => Matkul::count(),
            'matkulDosen' => $matkulDosen->count(),
            'matkulMahasiswa' => MatkulMahasiswa::whereIn('id_matkul', $matkulIds)->count(),
        ];

        return view('admin.dashboard', $data);
    }
}