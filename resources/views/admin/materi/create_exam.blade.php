@extends('layouts.backend.admin')
@push('css')
    <style>
        textarea {
            resize: both !important;
        }
    </style>
@endpush
@section('content')
    <div class="section mb-30">
        <form id="create-ujian-form">
            @csrf
            <div class="d-flex">
                <a href="{{ route('matkul.daftar-isi', $matkul->kode_matkul) }}" class="btn btn-secondary m-3"><i
                        class="bi bi-arrow-left"></i> Kembali</a>
                <button type="button" id="add-question" class="btn btn-warning my-3" data-toggle="modal"
                    data-target="#questionModal"><i class="bi bi-plus"></i> Tambah Pertanyaan</button>
                <a href="" class="btn btn-primary m-3"><i class="bi bi-monitor"></i> Preview</a>
            </div>
            <div class="card">
                <div class="card-body">

                    <table id="questions-table" class="table table-striped mt-3">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pertanyaan</th>
                                <th>Jenis</th> 
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data pertanyaan akan diisi oleh JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

        </form>
    </div>

    <!-- Modal Tambah Pertanyaan -->
    <div class="modal fade" id="questionModal" tabindex="-1" aria-labelledby="questionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="questionModalLabel">Tambah Pertanyaan</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i
                            class="bi bi-x"></i></button>
                </div>
                <div class="modal-body">
                    <form id="question-form">
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="hidden" name="id_ujian" value="{{ $ujian->id }}">
                                <div class="mb-3">
                                    <label for="text_pertanyaan">Pertanyaan:</label>
                                    <textarea class="form-control" id="text_pertanyaan" name="text_pertanyaan" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="jenis_pertanyaan">Jenis Pertanyaan:</label>
                                    <select class="form-control" id="jenis_pertanyaan" name="jenis_pertanyaan" required>
                                        <option value="essay">Essay</option>
                                        <option value="pilihan_ganda">Pilihan Ganda</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="pilihan-container" id="pilihan-container" style="display: none;">
                                    <label for="pilihan">Pilihan:</label>
                                    <div class="row">
                                        <div class="col-12">
                                            <textarea class="form-control" id="pilihan_a" name="pilihan[a]" placeholder="Pilihan A"></textarea>
                                            <textarea class="form-control" id="pilihan_b" name="pilihan[b]" placeholder="Pilihan B"></textarea>
                                            <textarea class="form-control" id="pilihan_c" name="pilihan[c]" placeholder="Pilihan C"></textarea>
                                        </div>
                                        <div class="col-12">
                                            <textarea class="form-control" id="pilihan_d" name="pilihan[d]" placeholder="Pilihan D"></textarea>
                                            <textarea class="form-control" id="pilihan_e" name="pilihan[e]" placeholder="Pilihan E"></textarea>
                                        </div>
                                    </div>
                                    <div class="jawaban-benar-container" id="jawaban-benar-container"
                                        style="display: none;">
                                        <label for="jawaban_benar">Jawaban Benar:</label>
                                        <select class="form-control" id="jawaban_benar" name="jawaban_benar">
                                            <option value="a">A</option>
                                            <option value="b">B</option>
                                            <option value="c">C</option>
                                            <option value="d">D</option>
                                            <option value="e">E</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <button type="submit" class="btn btn-primary mt-2">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let questionIndex = 1;
            const questionsTable = $('#questions-table').DataTable({
                ajax: {
                    url: '{{ route('ujian.get-pertanyaan', $ujian->id) }}',
                    dataSrc: ''
                },
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'text_pertanyaan'
                    },
                    {
                        data: 'jenis_pertanyaan'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `<button type="button" class="btn btn-danger btn-sm remove-question" data-id="${row.id}">Hapus</button>`;
                        }
                    }
                ]
            });

            // Menambahkan pertanyaan baru dari modal
            document.getElementById('question-form').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                fetch('{{ route('ujian.store-pertanyaan') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            $('#questionModal').modal('hide');
                            this.reset();
                            questionsTable.ajax.reload();
                        } else {
                            alert('Gagal menyimpan pertanyaan.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });

            // Mengatur event listener untuk jenis pertanyaan di modal
            document.getElementById('jenis_pertanyaan').addEventListener('change', function() {
                const jenis = this.value;
                const pilihanContainer = document.getElementById('pilihan-container');
                const jawabanContainer = document.getElementById('jawaban-benar-container');

                if (jenis === 'pilihan_ganda') {
                    pilihanContainer.style.display = 'block';
                    jawabanContainer.style.display = 'block';
                } else {
                    pilihanContainer.style.display = 'none';
                    jawabanContainer.style.display = 'none';
                }
            });

            // Menghapus pertanyaan dari tabel
            $('#questions-table tbody').on('click', '.remove-question', function() {
                const row = questionsTable.row($(this).parents('tr'));
                const data = row.data();
                if (data.id) {
                    // Hapus dari server jika sudah ada di database
                    fetch(`/ujian/pertanyaan/${data.id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                row.remove().draw();
                            } else {
                                alert('Gagal menghapus pertanyaan.');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                } else {
                    // Hapus dari tabel saja jika belum ada di database
                    row.remove().draw();
                }
            });

            // Menyimpan data ujian dan pertanyaan
            document.getElementById('create-ujian-form').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const questionsData = questionsTable.rows().data().toArray();
                formData.append('questions', JSON.stringify(questionsData));

                fetch('{{ route('ujian.store-pertanyaan') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Ujian dan pertanyaan berhasil disimpan!');
                            window.location.href =
                                '{{ route('matkul.daftar-isi', $matkul->kode_matkul) }}';
                        } else {
                            alert('Terjadi kesalahan: ' + data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    </script>
@endpush
