@extends('layouts.backend.admin')

@section('content')
    <div class="btn-group mb-3" role="group">
        <button class="btn create-new btn-primary" type="button" data-toggle="modal" data-target="#create">
            <span>
                <i class="bi bi-plus"></i>
                <span class="d-none d-sm-inline-block">Tambah Materi</span>
            </span>
        </button>
    </div>
@endsection
