<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PointController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Point Mahasiswa'
        ];
        return view('admin.point.index', $data);
    }
}
