@push('js')
    <script>
        $(document).ready(function() {
            // Simpan referensi ke elemen loading
            const loadingSpinner =
                '<div class="spinner-border text-primary spinner-border-sm text-center" role="status"><span class="sr-only">Loading...</span></div>';

            // Fungsi untuk memuat semua mata kuliah
            function loadMatkul(query = '') {
                $('.product-list ul').html(loadingSpinner);
                $.ajax({
                    url: '/api/matkul/getall',
                    type: 'GET',
                    data: {
                        search: query
                    },
                    success: function(response) {
                        $('.product-list ul').empty();
                        if (response.length === 0) {
                            $('.product-list ul').html(
                                '<div class="col-12"><p class="text-center">Tidak ada mata kuliah ditemukan.</p></div>'
                            );
                            return;
                        }
                        response.forEach(matkul => {
                            $('.product-list ul').append(`
                            <li class="col-lg-4 col-md-6 col-sm-12" style="max-width: auto !important;">
                                <div class="product-box">
                                    <div class="producct-img">
                                        <img src="{{ asset('backend_theme/') }}/vendors/images/product-img3.jpg" alt="">
                                    </div>
                                    <div class="product-caption">
                                        <h4><a href="#">${matkul.nama_matkul}</a></h4>
                                        @if (Auth::user()->role == 'Admin')
                                            <a href="#" class="btn btn-outline-success edit-dosen" data-id="${matkul.id}"><i class="bi bi-people"></i> Dosen</a>
                                            <a href="#" class="btn btn-outline-primary edit-matkul" data-id="${matkul.id}"><i class="bi bi-pencil-square"></i> Edit</a>
                                            <a href="#" class="btn btn-outline-danger delete-matkul" data-id="${matkul.id}"><i class="bi bi-trash bi-lg"></i></a>
                                        @else
                                            <a href="{{ url('/matkul/materi') }}/${matkul.kode_matkul}" class="btn btn-outline-success " ><i class="bi bi-book"></i> Buka materi</a>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        `);
                        });
                    },
                    error: function() {
                        alert('Gagal memuat mata kuliah. Silakan coba lagi.');
                    }
                });
            }

            // Panggil fungsi loadMatkul() saat halaman pertama kali dimuat
            loadMatkul();
            $('#search').on('input', function() {
                const query = $(this).val();
                loadMatkul(query);
            });

            // Tambah Mata Kuliah
            $('#save-matkul').on('click', function() {
                let formData = $('#create-form').serialize();
                $('#save-matkul').html(loadingSpinner);
                $.ajax({
                    url: '/api/matkul/create',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#save-matkul').html('Simpan');
                        $('#create').modal('hide');
                        loadMatkul();
                    },
                    error: function() {
                        $('#save-matkul').html('Simpan');
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                    }
                });
            });

            // Edit Mata Kuliah
            $(document).on('click', '.edit-matkul', function() {
                let id = $(this).data('id');
                $.ajax({
                    url: `/api/matkul/${id}/edit`,
                    type: 'GET',
                    success: function(response) {
                        $('#edit-form input[name="nama_matkul"]').val(response.nama_matkul);
                        $('#edit-form input[name="sks_matkul"]').val(response.sks_matkul);
                        $('#edit-modal').modal('show');
                        $('#update-matkul').data('id', id);
                    },
                    error: function() {
                        alert('Gagal memuat data. Silakan coba lagi.');
                    }
                });
            });
            // Edit dosen
            $(document).on('click', '.edit-dosen', function() {
                let id = $(this).data('id');
                $('#dosen-modal').modal('show');
                $('#update-matkul').data('id', id);
                $('#dosen-form input[name="id_matkul"]').val(id);
                if ($.fn.DataTable.isDataTable('#datatable-dosen')) {
                    $('#datatable-dosen').DataTable().destroy();
                }
                $('#datatable-dosen').DataTable({
                    processing: true,
                    serverSide: false,
                    responsive: false,
                    ajax: '{{ url('dosen-matkul-datatable') }}/' + id,
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },

                        {
                            data: 'dosen.name',
                            name: 'dosen.name'
                        },
                        {
                            data: 'dosen.identity',
                            name: 'dosen.identity'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        }
                    ],
                    dom: 'tp',
                });

                $('#save-dosen').off('click').on('click', function() {
                    let formData = $('#dosen-form').serialize();
                    $('#save-dosen').html(loadingSpinner);
                    $.ajax({
                        url: '/dosen-matkul/store',
                        type: 'POST',
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $('#save-dosen').html('Simpan');
                            $('#datatable-dosen').DataTable().ajax.reload();
                        },
                        error: function() {
                            $('#save-dosen').html('Simpan');
                            alert('Terjadi kesalahan. Silakan coba lagi.');
                        }
                    });
                });
                // Hapus Mata Kuliah
                $(document).off('click', '.delete-dosen').on('click', '.delete-dosen', function() {
                    if (!confirm('Apakah Anda yakin ingin menghapus dosen ini?')) return;
                    let id = $(this).data('id');
                    let btn = $(this); // Simpan referensi tombol
                    btn.html(loadingSpinner);
                    $.ajax({
                        url: `/dosen-matkul/${id}/delete`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function() {
                            btn.html('<i class="bi bi-trash"></i>');
                            $('#datatable-dosen').DataTable().ajax.reload();
                        },
                        error: function() {
                            btn.html('<i class="bi bi-trash"></i>');
                            alert('Terjadi kesalahan. Silakan coba lagi.');
                        }
                    });
                });

            });

            $('#update-matkul').on('click', function() {
                let id = $(this).data('id');
                let formData = $('#edit-form').serialize();
                $('#update-matkul').html(loadingSpinner);
                $.ajax({
                    url: `/api/matkul/${id}/update`,
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        $('#update-matkul').html('Update');
                        $('#edit-modal').modal('hide');
                        loadMatkul();
                    },
                    error: function() {
                        $('#update-matkul').html('Update');
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                    }
                });
            });

            // Hapus Mata Kuliah
            $(document).on('click', '.delete-matkul', function() {
                if (!confirm('Apakah Anda yakin ingin menghapus mata kuliah ini?')) return;
                let id = $(this).data('id');
                $(this).html(loadingSpinner);
                $.ajax({
                    url: `/api/matkul/${id}/delete`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        loadMatkul();
                    },
                    error: function() {
                        loadMatkul();
                        $(this).html('<i class="bi bi-trash bi-lg"></i>');
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                    }
                });
            });
        });
    </script>
@endpush
