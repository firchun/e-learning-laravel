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
                            <th>Avatar</th>
                            <th>Name</th>
                            <th>NPM/NIM</th>
                        </tr>
                    </thead>

                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Avatar</th>
                            <th>Name</th>
                            <th>NPM/NIM</th>
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
                ajax: '{{ url('users-datatable', 'Mahasiswa') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'avatar',
                        name: 'avatar'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'identity',
                        name: 'identity'
                    },
                ]
            });
            $('.refresh').click(function() {
                $('#datatable-users').DataTable().ajax.reload();
            });
        });
    </script>
@endpush
