<?php

namespace App\Http\Controllers;

use App\Models\Matkul;
use App\Models\MatkulMahasiswa;
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

        // Check if the user is authenticated and their role is 'Mahasiswa'
        if (Auth::check() && Auth::user()->role == 'Mahasiswa') {
            $matkul->join('matkul_mahasiswa', 'matkul.id', '=', 'matkul_mahasiswa.id_matkul')
                ->where('matkul_mahasiswa.id_user', Auth::id())
                ->select('matkul.*');
        }

        $data = $matkul->get();
        $data->map(function ($item) {
            $dosenNames = $item->dosen->pluck('name')->toArray();
            $item->nama_dosen = implode(', ', $dosenNames);
            return $item;
        });

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
    public function storeMahasiswa(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'id_matkul' => 'required|string|max:255',
        ]);

        $matkul = MatkulMahasiswa::where('id_matkul', $validated['id_matkul'])->where('id_user', Auth::id())->first();

        if (!$matkul) {
            // Menambahkan data jika belum ada
            MatkulMahasiswa::create([
                'id_matkul' => $validated['id_matkul'],
                'id_user' => Auth::id(),
            ]);

            return response()->json([
                'message' => 'Berhasil menambahkan matakuliah',
            ]);
        } else {
            return response()->json([
                'message' => 'Matakuliah sudah ada',
            ]);
        }
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
    public function destroyMahasiswa($id)
    {

        $matkul = MatkulMahasiswa::where('id_matkul', $id)
            ->where('id_user', Auth::id())
            ->first();

        if (!$matkul) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan atau tidak milik pengguna.'], 404);
        }
        $matkul->delete();

        return response()->json(['success' => true, 'message' => 'Matakuliah berhasil dihapus.']);
    }
    public function getMahasiswa($matkul_id)
    {
        $mahasiswa = MatkulMahasiswa::with(['mahasiswa'])->where('id_matkul', $matkul_id)->get();
        return response()->json(['mahasiswa' => $mahasiswa]);
    }
}
