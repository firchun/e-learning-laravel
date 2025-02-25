@extends('layouts.backend.admin')

@section('content')
    @include('layouts.backend.alert')

    <div class="container mb-30">
        <div class="card shadow-sm">
            <div class="card-header text-center">
                <h2>Hasil Ujian: {{ $ujian->keterangan }} ({{ $ujian->jenis }})</h2>
                <p><strong>Tanggal:</strong> {{ $ujian->tanggal_ujian }}</p>
                <p><strong>Nilai Anda:</strong> <span class="badge bg-primary fs-5">{{ number_format($nilai, 2) }}</span></p>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Pertanyaan</th>
                            <th>Jawaban Anda</th>
                            <th>Jawaban Benar</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jawaban as $index => $j)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $j->pertanyaan->text_pertanyaan }}</td>
                                <td>{{ $j->jawaban }}</td>
                                <td>{{ $j->pertanyaan->jawaban_benar }}</td>
                                <td>
                                    @if ($j->is_benar)
                                        <span class="badge bg-success">Benar</span>
                                    @else
                                        <span class="badge bg-danger">Salah</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-center">
                <a href="{{ route('ujian', $ujian->id) }}" class="btn btn-secondary">Kembali ke Ujian</a>
            </div>
        </div>
    </div>

    <style>
        .badge {
            font-size: 1rem;
            padding: 10px;
        }
    </style>
@endsection
