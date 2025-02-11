@extends('layouts.backend.admin')

@section('content')
    <div class="product-wrap">
        <div class="product-list">
            @if (Auth::user()->role == 'Admin')
                <div class="btn-group mb-3" role="group">
                    <button class="btn create-new btn-primary" type="button" data-toggle="modal" data-target="#create">
                        <span>
                            <i class="bi bi-plus"></i>
                            <span class="d-none d-sm-inline-block">Tambah Mata Kuliah</span>
                        </span>
                    </button>
                </div>
            @elseif(Auth::user()->role == 'Mahasiswa')
                <div class="btn-group mb-3" role="group">
                    <button class="btn pilih-matkul btn-primary" type="button" data-toggle="modal"
                        data-target="#pilih_matkul">
                        <span>
                            <i class="bi bi-plus"></i>
                            <span class="d-none d-sm-inline-block">Pilih Mata Kuliah</span>
                        </span>
                    </button>
                </div>
            @endif
            <div class="mb-3">
                <input type="text" name="search" id="search" class="form-control" placeholder="Cari Matakuliah">
            </div>
            <ul class="row">

                {{-- Dynamic Item List --}}
                <div id="matkul-list">

                </div>
            </ul>
        </div>
    </div>
    @include('admin.matkul.components.modal')
@endsection

@include('admin.matkul.script')
