<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SemesterController extends Controller
{
    public function index()
    {
        $semesters = Semester::all(); // Mengambil semua data semester
        return view('admin.semester.index', compact('semesters'));
    }


    public function getDatatable()
    {
        $data = Semester::query();

        return DataTables::of($data)
            ->addColumn('status', function ($row) {
                return $row->status == 1 ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>';
            })
            ->addColumn('action', function ($row) {
                    return '
                    <button class="btn btn-sm btn-warning edit-semester" data-id="' . $row->id . '">Edit</button>
                    <button class="btn btn-sm btn-danger delete-semester" data-id="' . $row->id . '">Hapus</button>
                ';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }
    public function update(Request $request, $id)
    {
        // Validasi data
        $request->validate([
            'tahun_ajaran' => 'required|string|max:255',
            'semester' => 'required|in:Ganjil,Genap',
            'status' => 'required|boolean',
        ]);

        $semesterData = Semester::findOrFail($id);

        // Membuat kode semester sesuai format tahun_ajaran + nomor semester
        $tahun_ajaran = $request->tahun_ajaran;
        $semester = $request->semester;

        $semester_code = $this->generateSemesterCode($tahun_ajaran, $semester);

        // Update data semester
        $semesterData->code = $semester_code;
        $semesterData->tahun_ajaran = $tahun_ajaran;
        $semesterData->semester = $semester;
        $semesterData->status = $request->status;
        $semesterData->save();

        return response()->json(['message' => 'Semester updated successfully!']);
    }
    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'tahun_ajaran' => 'required|string|max:255',
            'semester' => 'required|in:Ganjil,Genap',
            'status' => 'required|boolean',
        ]);

        // Membuat kode semester sesuai format tahun_ajaran + nomor semester
        $tahun_ajaran = $request->tahun_ajaran;
        $semester = $request->semester;

        $semester_code = $this->generateSemesterCode($tahun_ajaran, $semester);

        // Simpan data semester
        $semesterData = new Semester();
        $semesterData->code = $semester_code;
        $semesterData->tahun_ajaran = $tahun_ajaran;
        $semesterData->semester = $semester;
        $semesterData->status = $request->status;
        $semesterData->save();

        return response()->json(['message' => 'Semester created successfully!']);
    }
    private function generateSemesterCode($tahun_ajaran, $semester)
    {
        // Tentukan angka untuk semester: 1 untuk Ganjil, 2 untuk Genap
        $semester_number = ($semester === 'Ganjil') ? 1 : 2;

        // Gabungkan tahun_ajaran dan semester_number menjadi code
        return $tahun_ajaran . $semester_number;
    }
    public function show($id)
    {
        return Semester::findOrFail($id);
    }

    public function destroy($id)
    {
        $semester = Semester::findOrFail($id);
        $semester->delete();
        return response()->json(['success' => true]);
    }
}