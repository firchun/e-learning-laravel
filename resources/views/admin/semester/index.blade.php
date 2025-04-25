@extends('layouts.backend.admin')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#createSemesterModal">
                <i class="bi bi-plus"></i>
                <span class="d-none d-sm-inline-block">Tambah Semester</span></button>
            <div class="card-box mb-30">
                <div class="card-body">
                    <h2>Semester</h2>
                </div>
                <table id="datatable-semesters" class="table table-hover display">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Tahun Ajaran</th>
                            <th>Semester</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Tahun Ajaran</th>
                            <th>Semester</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Create Semester -->
    <div class="modal fade" id="createSemesterModal" tabindex="-1" aria-labelledby="createSemesterModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createSemesterModalLabel">Create New Semester</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createSemesterForm" method="POST" action="{{ route('semesters.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="tahun_ajaran">Tahun Ajaran</label>
                            <input type="text" class="form-control" id="tahun_ajaran_create" name="tahun_ajaran"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="semester">Semester</label>
                            <select class="form-control" id="semester_create" name="semester" required>
                                <option value="Ganjil">Ganjil</option>
                                <option value="Genap">Genap</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status_create" name="status" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- modal edit --}}
    <!-- Modal Edit Semester -->
    <div class="modal fade" id="editSemesterModal" tabindex="-1" aria-labelledby="editSemesterModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editSemesterForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Semester</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="form-group">
                            <label>Tahun Ajaran</label>
                            <input type="text" class="form-control" name="tahun_ajaran" id="tahun_ajaran_edit" required>
                        </div>
                        <div class="form-group">
                            <label>Semester</label>
                            <select class="form-control" name="semester" id="semester_edit" required>
                                <option value="Ganjil">Ganjil</option>
                                <option value="Genap">Genap</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="status" id="status_edit" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            var table = $('#datatable-semesters').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('semesters.datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'tahun_ajaran',
                        name: 'tahun_ajaran'
                    },
                    {
                        data: 'semester',
                        name: 'semester'
                    },
                    {
                        data: 'status',
                        name: 'status',

                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Submit form create semester
            $('#createSemesterForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#createSemesterModal').modal('hide');
                        $('#createSemesterForm')[0].reset();
                        table.ajax.reload();
                        toastr.success('Semester berhasil ditambahkan.');
                    },
                    error: function(xhr) {
                        toastr.error(
                            'Gagal menyimpan data. Pastikan semua field terisi dengan benar.'
                        );
                    }
                });
            });
            // Edit button click
            $(document).on('click', '.edit-semester', function() {
                var id = $(this).data('id');
                $.get('/semesters/show/' + id, function(data) {
                    $('#edit_id').val(data.id);
                    $('#tahun_ajaran_edit').val(data.tahun_ajaran);
                    $('#semester_edit').val(data.semester);
                    $('#status_edit').val(data.status);
                    $('#editSemesterForm').attr('action', '/semesters/update/' + id);
                    $('#editSemesterModal').modal('show');
                });
            });

            // Submit form edit
            $('#editSemesterForm').on('submit', function(e) {
                e.preventDefault();
                let form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        $('#editSemesterModal').modal('hide');
                        table.ajax.reload();
                        toastr.success('Semester berhasil diperbarui.');
                    },
                    error: function() {
                        toastr.error('Gagal memperbarui data.');
                    }
                });
            });

            // Delete button click
            $(document).on('click', '.delete-semester', function() {
                if (confirm('Yakin ingin menghapus semester ini?')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: '/semesters/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            table.ajax.reload();
                            toastr.success('Semester berhasil dihapus.');
                        },
                        error: function() {
                            toastr.error('Gagal menghapus semester.');
                        }
                    });
                }
            });
        });
    </script>
@endpush
