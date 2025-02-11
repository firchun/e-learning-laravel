@extends('layouts.backend.admin')
@push('css')
    <style>
        .ck-editor__editable {
            min-height: 300px;
        }

        .scrollable {
            height: 70vh;
            overflow-y: auto;
            overflow-x: hidden;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-12">
                <form id="form-materi" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_matkul" id="id_matkul" value="{{ $matkul->id }}">
                    <div class="mb-3">
                        <label for="">Judul Materi</label>
                        <input type="text" name="judul" id="judul" class="form-control" placeholder="Judul Materi"
                            required>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label>Sematkan Gambar</label>
                            <input type="file" class="form-control" name="image" id="image" accept="image/*">
                        </div>
                        <div class="col">
                            <label>Sematkan File</label>
                            <input type="file" class="form-control" name="file" id="file"
                                accept=".pdf,.doc,.docx,.xlsx">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="">Isi Materi</label>
                        <textarea name="isi_materi" id="editor"></textarea>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">
                            Tambah Materi
                            <span id="save-spinner" class="spinner-border spinner-border-sm text-light d-none"
                                role="status"></span>
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-lg-6 scrollable">
                <div id="list_materi">
                    <div id="load-spinner" class="text-center my-3 d-none">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p>Memuat materi...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Tambah Kuis -->
    <div class="modal fade" id="modalTambahKuis" tabindex="-1" aria-labelledby="modalTambahKuisLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahKuisLabel">Tambah Kuis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formTambahKuis">

                        <input type="hidden" id="currentId" class="form-control" readonly>

                        <input type="hidden" id="nextId" class="form-control" readonly>

                        <div class="mb-3">
                            <label for="judulKuis" class="form-label">Skor Kuis</label>
                            <input type="number" id="judulKuis" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsiKuis" class="form-label">Deskripsi Kuis</label>
                            <textarea id="deskripsiKuis" class="form-control" rows="3" placeholder="Masukkan deskripsi kuis"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="simpanKuis()">Simpan Kuis</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        let editorInstance;

        // Inisialisasi CKEditor
        ClassicEditor
            .create(document.querySelector('#editor'))
            .then(editor => {
                editorInstance = editor;
            })
            .catch(error => {
                console.error(error);
            });

        // Load daftar materi
        function loadMateri() {
            $('#load-spinner').removeClass('d-none'); // Tampilkan spinner
            $.ajax({
                url: "{{ route('materi.index') }}", // Endpoint untuk mengambil daftar materi
                method: "GET",
                success: function(response) {
                    let html = '';
                    if (response.length === 0) {
                        html = `
                    <div class="text-center mt-3">
                        <p class="text-muted">Belum ada materi yang tersedia.</p>
                    </div>
                `;
                    }
                    response.forEach((materi, index) => {
                        const currentId = materi.id;
                        const nextId = index < response.length - 1 ? response[index + 1].id : null;
                        html += `
                        <div class="card mb-3" style="border-radius:20px;">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <button class="btn text-danger btn-outline-light btn-sm" onclick="hapusMateri(${materi.id})">
                                        <i class="bi bi-trash"></i>
                                    </button> 
                                    #${index + 1} | ${materi.judul}
                                </h5>
                                <hr>
                                ${materi.image ? `<img src="/storage/${materi.image}" alt="${materi.judul}" class="img-fluid mb-3 rounded" style="max-height: 200px; object-fit: cover;">` : ''}
                                <p class="card-text">${materi.isi_materi}</p>
                                ${materi.file ? `<div class="mt-3">
                                                     <a href="/storage/${materi.file}" class="btn btn-warning btn-sm" download>
                                                             <i class="bi bi-download"></i> Download File
                                                    </a>
                                                 </div> ` : ''}
                            </div>
                        </div>
                        <div class="mb-3 d-flex btn-group">
                           <button 
                                class="btn btn-primary" 
                                data-id="${currentId}" 
                                data-next-id="${nextId}" 
                                data-toggle="modal" 
                                data-target="#modalTambahKuis" 
                                onclick="prepareModal(${currentId}, ${nextId})">
                                <i class="bi bi-plus"></i> Tambah Kuis
                            </button>
                        </div>
                    `;
                    });
                    $('#list_materi').html(html);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                },
                complete: function() {
                    $('#load-spinner').addClass('d-none'); // Sembunyikan spinner
                }
            });
        }

        function prepareModal(currentId, nextId) {
            // Isi input modal dengan ID saat ini dan berikutnya
            document.getElementById('currentId').value = currentId;
            document.getElementById('nextId').value = nextId || 'Tidak ada';
        }


        // Submit form materi
        $('#form-materi').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this); // Ambil data dari form
            formData.append('isi_materi', editorInstance.getData()); // Tambahkan isi CKEditor ke FormData

            $('#save-spinner').removeClass('d-none'); // Tampilkan spinner simpan

            $.ajax({
                url: "{{ route('materi.store') }}",
                method: "POST",
                data: formData,
                contentType: false, // Jangan atur Content-Type secara manual
                processData: false, // Jangan proses data FormData
                success: function(response) {
                    alert('Materi berhasil disimpan!');
                    $('#judul').val('');
                    $('#image').val('');
                    $('#file').val('');
                    editorInstance.setData('');
                    loadMateri();
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                },
                complete: function() {
                    $('#save-spinner').addClass('d-none'); // Sembunyikan spinner simpan
                }
            });
        });

        function hapusMateri(id) {
            if (confirm('Apakah Anda yakin ingin menghapus materi ini?')) {
                const button = $(`button[onclick="hapusMateri(${id})"]`);
                button.prop('disabled', true); // Nonaktifkan tombol hapus sementara
                button.html(
                    '<div class="spinner-border spinner-border-sm text-danger" role="status"></div>'
                ); // Spinner di tombol hapus

                $.ajax({
                    url: `/materi/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        loadMateri();
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    },
                    complete: function() {
                        button.prop('disabled', false); // Aktifkan kembali tombol hapus
                        button.html('<i class="bi bi-trash"></i>'); // Kembalikan ikon hapus
                    }
                });
            }
        }

        // Panggil loadMateri saat halaman selesai dimuat
        $(document).ready(function() {
            loadMateri();
        });
    </script>
@endpush
