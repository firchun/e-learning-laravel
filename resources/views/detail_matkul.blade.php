@extends('layouts.front')

@section('content')
    <section class="section container">
        <h3 class="mb-3">{{ $title }}</h3>
        <div class="p-5 shadow rounded">
            <div class="row">
                <div class="col-md-4">
                    <img src="{{ asset('frontend') }}/images/getting-started.jpg" alt="" class="img-fluid">
                </div>
                <div class="col-md-8">
                    <h3 class="text-primary">{{ $matkul->nama_matkul }}</h3>
                    <p>{{ $matkul->description }}</p>
                    <p><strong class="text-primary">Dosen Pengampu :</strong>
                        @if ($matkul->dosen->isNotEmpty())
                            {{ $matkul->dosen->pluck('name')->join(', ') }}
                        @else
                            Belum ada dosen pengampu.
                        @endif
                    </p>
                    <p><strong class="text-primary">SKS : </strong> {{ $matkul->sks_matkul }}</p>
                    <strong class="text-primary">Mahasiswa : </strong><span
                        class="badge badge-danger px-2 py-1">{{ $matkul->mahasiswa->count() }}</span><br>
                    @if ($matkul->mahasiswa->isNotEmpty())
                        <small
                            class="p-1 border border-danger rounded mx-1">{{ $matkul->mahasiswa->pluck('name')->join(', ') }}</small>
                    @else
                        Belum ada Mahasiswa
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
