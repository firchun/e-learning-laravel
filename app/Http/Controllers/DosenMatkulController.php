<?php

namespace App\Http\Controllers;

use App\Models\DosenMatkul;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DosenMatkulController extends Controller
{
    public function getDosenMatkulDataTable($id)
    {
        $matkul = DosenMatkul::with(['dosen'])->where('id_matkul', $id)->orderByDesc('id');

        return DataTables::of($matkul)
            ->addColumn('action', function ($customer) {
                return '<button type="button" class="btn btn-danger btn-sm delete-dosen" data-id="' . $customer->id . '" ><i class="bi bi-trash"></i></button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'id_user' => 'required|string|max:255',
            'id_matkul' => 'required|string',
        ]);

        DosenMatkul::create([
            'id_user' => $validated['id_user'],
            'id_matkul' => $validated['id_matkul'],
        ]);

        return response()->json([
            'message' => 'berhasil menambahkan dosen',
        ]);
    }
    public function destroy($id)
    {
        DosenMatkul::destroy($id);
        return response()->json(['success' => true]);
    }
}