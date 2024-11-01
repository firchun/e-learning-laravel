<?php

namespace App\Http\Controllers;

use App\Models\Matkul;
use Illuminate\Http\Request;
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
    public function getall()
    {
        $matkul = Matkul::orderByDesc('id');
        // if(Auth::user())

        $data =  $matkul->get();
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
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $MatkulData = [
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
        ];

        if ($request->filled('id')) {
            $Matkul = Matkul::find($request->input('id'));
            if (!$Matkul) {
                return response()->json(['message' => 'Matkul not found'], 404);
            }

            $Matkul->update($MatkulData);
            $message = 'Matkul updated successfully';
        } else {
            Matkul::create($MatkulData);
            $message = 'Matkul created successfully';
        }

        return response()->json(['message' => $message]);
    }
    public function destroy($id)
    {
        $Matkuls = Matkul::find($id);

        if (!$Matkuls) {
            return response()->json(['message' => 'Matkul not found'], 404);
        }

        $Matkuls->delete();

        return response()->json(['message' => 'Matkul deleted successfully']);
    }
    public function edit($id)
    {
        $Matkul = Matkul::find($id);

        if (!$Matkul) {
            return response()->json(['message' => 'Matkul not found'], 404);
        }

        return response()->json($Matkul);
    }
}
