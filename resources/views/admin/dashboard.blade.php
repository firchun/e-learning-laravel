@extends('layouts.backend.admin')

@section('content')
    <div class="title pb-20">
        <h2 class="h3 mb-0">Dashboard Overview</h2>
    </div>
    <div class="alert alert-primary" role="alert">
        Selamat datang di dashboard, {{ Auth::user()->name }}!
    </div>
    @if (Auth::user()->role == 'Admin')
        <div class="row justify-content-center">
            @include('admin.dashboard_component.card1', [
                'count' => $dosen,
                'title' => 'Dosen',
                'subtitle' => 'Total dosen',
                'color' => 'primary',
                'icon' => 'user',
            ])
            @include('admin.dashboard_component.card1', [
                'count' => $mahasiswa,
                'title' => 'Mahasiswa',
                'subtitle' => 'Total mahasiswa',
                'color' => 'primary',
                'icon' => 'user',
            ])
            @include('admin.dashboard_component.card1', [
                'count' => $matkul,
                'title' => 'Matakuliah',
                'subtitle' => 'Total Matakuliah',
                'color' => 'warning',
                'icon' => 'book',
            ])

        </div>
    @else
        <div class="row justify-content-center">

            @include('admin.dashboard_component.card1', [
                'count' => $matkulDosen,
                'title' => 'Matakuliah',
                'subtitle' => 'Total Matakuliah',
                'color' => 'warning',
                'icon' => 'book',
            ])
            @include('admin.dashboard_component.card1', [
                'count' => $matkulMahasiswa,
                'title' => 'Mahasiswa',
                'subtitle' => 'Total Mahasiswa',
                'color' => 'danger',
                'icon' => 'user',
            ])

        </div>
    @endif
@endsection
