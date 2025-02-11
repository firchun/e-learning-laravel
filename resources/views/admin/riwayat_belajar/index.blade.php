@extends('layouts.backend.admin')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card-box mb-30">
                <div class="card-body">
                    <h2>{{ $title }}</h2>
                </div>
                <table id="datatable-users" class="table table-hover display">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Matakuliah</th>
                            <th>Materi</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>

                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Matakuliah</th>
                            <th>Materi</th>
                            <th>Tanggal</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(function() {
            $('#datatable-users').DataTable({
                processing: true,
                serverSide: false,
                responsive: false,
                ajax: '{{ url('riwayat-datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'matkul.nama_matkul',
                        name: 'matkul.nama_matkul'
                    },
                    {
                        data: 'materi.judul',
                        name: 'materi.judul'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                ]
            });
            $('.refresh').click(function() {
                $('#datatable-users').DataTable().ajax.reload();
            });
        });
    </script>
@endpush
