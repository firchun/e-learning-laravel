<?php

namespace App\Http\Controllers;

use App\Models\Matkul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class MatkulController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Mata Kuliah',
        ];
        return view('admin.matkul.index', $data);
    }
    public function materi($kode_matkul)
    {
        $matkul = Matkul::where('kode_matkul', $kode_matkul)->first();
        $data = [
            'title' => 'Matakuliah : ' . $matkul->nama_matkul,
        ];
        return view('admin.matkul.materi', $data);
    }
    public function getAll(Request $request)
    {
        $search = $request->query('search');
        $matkul = Matkul::when($search, function ($query, $search) {
            return $query->where('nama_matkul', 'like', '%' . $search . '%');
        });
        if (Auth::check() && Auth::user()->role == 'Dosen') {
            $matkul->join('dosen_matkul', 'matkul.id', '=', 'dosen_matkul.id_matkul')
                ->where('dosen_matkul.id_user', Auth::id())
                ->select('matkul.*');
        }

        $data = $matkul->get();

        return response()->json($data);
    }
    public function getMatkulDataTable()
    {
        $matkul = Matkul::orderByDesc('id');

        return DataTables::of($matkul)
            ->addColumn('action', function ($customer) {
                return view('admin.matkul.components.actions', compact('customer'));
            })

            ->rawColumns(['action', 'phone'])
            ->make(true);
    }
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama_matkul' => 'required|string|max:255',
            'sks_matkul' => 'required|integer',
        ]);

        // Generate kode mata kuliah sebelum membuat data
        $kodeMatkul = $this->generateKodeMatkul($validated['nama_matkul']);

        // Simpan data ke database
        $matkul = Matkul::create([
            'kode_matkul' => $kodeMatkul,
            'nama_matkul' => $validated['nama_matkul'],
            'sks_matkul' => $validated['sks_matkul'],
        ]);

        return response()->json([
            'id' => $matkul->id,
            'kode_matkul' => $matkul->kode_matkul,
            'nama_matkul' => $matkul->nama_matkul,
            'sks_matkul' => $matkul->sks_matkul,
        ]);
    }
    function generateKodeMatkul($namaMatkul)
    {
        // Ambil dua kata pertama dari nama mata kuliah
        $words = explode(' ', $namaMatkul);
        $prefix = strtoupper(substr($words[0], 0, 1)); // Huruf pertama dari kata pertama
        if (isset($words[1])) {
            $prefix .= strtoupper(substr($words[1], 0, 1)); // Huruf pertama dari kata kedua (jika ada)
        }

        // Tambahkan angka unik
        do {
            $uniqueNumber = random_int(10, 99); // Angka 2 digit
            $kodeMatkul = $prefix . $uniqueNumber;

            // Cek keunikan di database
            $exists = \App\Models\Matkul::where('kode_matkul', $kodeMatkul)->exists();
        } while ($exists);

        return $kodeMatkul;
    }

    public function edit($id)
    {
        $matkul = Matkul::find($id);
        return response()->json($matkul);
    }

    public function update(Request $request, $id)
    {
        $matkul = Matkul::find($id);
        $matkul->update($request->all());
        return response()->json(['nama_matkul' => $matkul->nama_matkul]);
    }

    public function destroy($id)
    {
        Matkul::destroy($id);
        return response()->json(['success' => true]);
    }
}