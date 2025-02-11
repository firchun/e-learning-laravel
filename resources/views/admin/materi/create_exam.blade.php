@extends('layouts.backend.admin')
@section('content')
    <div class="section mb-30">
        <form id="create-ujian-form">
            @csrf
            <div class="row">
                <div class="col-lg-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h4>Informasi Ujian</h4>
                            <input type="hidden" value="{{ $matkul->id }}" readonly>

                            <div class="mb-3">
                                <label for="keterangan">Keterangan:</label>
                                <textarea id="keterangan" name="keterangan" class="form-control"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="jenis">Jenis Ujian:</label>
                                <select id="jenis" name="jenis" required class="form-control">
                                    <option value="UTS">UTS</option>
                                    <option value="UAS">UAS</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="tanggal_ujian">Tanggal Ujian:</label>
                                <input class="form-control" type="date" id="tanggal_ujian" name="tanggal_ujian" required>
                            </div>

                            <div class="mb-3">
                                <label for="waktu_mulai">Waktu Mulai:</label>
                                <input class="form-control" type="datetime-local" id="waktu_mulai" name="waktu_mulai"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="waktu_selesai">Waktu Selesai:</label>
                                <input class="form-control" type="datetime-local" id="waktu_selesai" name="waktu_selesai"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div id="questions-container">
                        <div class="card mb-3 question-card" id="question-card-1">
                            <div class="card-body">
                                <div class="question-item">
                                    <h5>Pertanyaan 1</h5>
                                    <div>
                                        <label for="text_pertanyaan_1">Pertanyaan:</label>
                                        <textarea class="form-control" id="text_pertanyaan_1" name="questions[0][text_pertanyaan]" required></textarea>
                                    </div>
                                    <div>
                                        <label for="jenis_pertanyaan_1">Jenis Pertanyaan:</label>
                                        <select class="form-control jenis-pertanyaan" id="jenis_pertanyaan_1"
                                            name="questions[0][jenis_pertanyaan]" data-index="0" required>
                                            <option value="essay">Essay</option>
                                            <option value="pilihan_ganda">Pilihan Ganda</option>
                                        </select>
                                    </div>
                                    <div class="pilihan-container" id="pilihan-container-0" style="display: none;">
                                        <label for="pilihan_0">Pilihan:</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input class="form-control form-control-sm" type="text" id="pilihan_a_0"
                                                    name="questions[0][pilihan][a]" placeholder="Pilihan A">
                                                <input class="form-control form-control-sm" type="text" id="pilihan_b_0"
                                                    name="questions[0][pilihan][b]" placeholder="Pilihan B">
                                                <input class="form-control form-control-sm" type="text" id="pilihan_c_0"
                                                    name="questions[0][pilihan][c]" placeholder="Pilihan C">
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-control form-control-sm" type="text" id="pilihan_d_0"
                                                    name="questions[0][pilihan][d]" placeholder="Pilihan D">
                                                <input class="form-control form-control-sm" type="text" id="pilihan_e_0"
                                                    name="questions[0][pilihan][e]" placeholder="Pilihan E">
                                            </div>
                                        </div>
                                        <div class="jawaban-benar-container" id="jawaban-benar-container-0"
                                            style="display: none;">
                                            <label for="jawaban_benar_0">Jawaban Benar:</label>
                                            <select class="form-control form-control-sm" id="jawaban_benar_0"
                                                name="questions[0][jawaban_benar]">
                                                <option value="a">A</option>
                                                <option value="b">B</option>
                                                <option value="c">C</option>
                                                <option value="d">D</option>
                                                <option value="e">E</option>
                                            </select>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-danger btn-sm mt-2 remove-question"
                                        data-index="0">Hapus Pertanyaan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add-question" class="btn btn-warning mt-2">Tambah Pertanyaan</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let questionIndex = 1;

            // Fungsi untuk memperbarui indeks setelah menambah atau menghapus pertanyaan
            const updateQuestionIndices = () => {
                const questionCards = document.querySelectorAll('.question-card');
                questionCards.forEach((card, index) => {
                    const newIndex = index + 1; // Indeks dimulai dari 1
                    card.id = `question-card-${newIndex}`;
                    card.querySelector('h5').textContent = `Pertanyaan ${newIndex}`;
                    card.querySelectorAll('[id], [name]').forEach((element) => {
                        if (element.id) {
                            element.id = element.id.replace(/\d+$/, newIndex);
                        }
                        if (element.name) {
                            element.name = element.name.replace(/\d+/, newIndex - 1);
                        }
                        if (element.dataset.index) {
                            element.dataset.index = newIndex - 1;
                        }
                    });
                });
                questionIndex = questionCards.length; // Update questionIndex dengan jumlah pertanyaan saat ini
            };

            // Menambahkan pertanyaan baru
            document.getElementById('add-question').addEventListener('click', function() {
                const container = document.getElementById('questions-container');
                const newQuestion = `
                    <div class="card mb-3 question-card" id="question-card-${questionIndex + 1}">
                        <div class="card-body">
                            <div class="question-item">
                                <h5>Pertanyaan ${questionIndex + 1}</h5>
                                <div>
                                    <label for="text_pertanyaan_${questionIndex}">Pertanyaan:</label>
                                    <textarea class="form-control" id="text_pertanyaan_${questionIndex}" name="questions[${questionIndex}][text_pertanyaan]" required></textarea>
                                </div>
                                <div>
                                    <label for="jenis_pertanyaan_${questionIndex}">Jenis Pertanyaan:</label>
                                    <select class="form-control jenis-pertanyaan" id="jenis_pertanyaan_${questionIndex}" name="questions[${questionIndex}][jenis_pertanyaan]" data-index="${questionIndex}" required>
                                        <option value="essay">Essay</option>
                                        <option value="pilihan_ganda">Pilihan Ganda</option>
                                    </select>
                                </div>
                                <div class="pilihan-container" id="pilihan-container-${questionIndex}" style="display: none;">
                                    <label for="pilihan_${questionIndex}">Pilihan:</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input class="form-control" type="text" id="pilihan_a_${questionIndex}" name="questions[${questionIndex}][pilihan][a]" placeholder="Pilihan A">
                                            <input class="form-control" type="text" id="pilihan_b_${questionIndex}" name="questions[${questionIndex}][pilihan][b]" placeholder="Pilihan B">
                                            <input class="form-control" type="text" id="pilihan_c_${questionIndex}" name="questions[${questionIndex}][pilihan][c]" placeholder="Pilihan C">
                                        </div>
                                        <div class="col-md-6">
                                            <input class="form-control" type="text" id="pilihan_d_${questionIndex}" name="questions[${questionIndex}][pilihan][d]" placeholder="Pilihan D">
                                            <input class="form-control" type="text" id="pilihan_e_${questionIndex}" name="questions[${questionIndex}][pilihan][e]" placeholder="Pilihan E">
                                        </div>
                                    </div>
                                    <div class="jawaban-benar-container" id="jawaban-benar-container-${questionIndex}" style="display: none;">
                                        <label for="jawaban_benar_${questionIndex}">Jawaban Benar:</label>
                                        <select class="form-control" id="jawaban_benar_${questionIndex}" name="questions[${questionIndex}][jawaban_benar]">
                                            <option value="a">A</option>
                                            <option value="b">B</option>
                                            <option value="c">C</option>
                                            <option value="d">D</option>
                                            <option value="e">E</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm mt-2 remove-question" data-index="${questionIndex}">Hapus Pertanyaan</button>
                            </div>
                        </div>
                    </div>`;
                container.insertAdjacentHTML('beforeend', newQuestion);
                questionIndex++;
            });

            // Mengatur event listener untuk jenis pertanyaan
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('jenis-pertanyaan')) {
                    const index = e.target.dataset.index;
                    const jenis = e.target.value;
                    const pilihanContainer = document.getElementById(`pilihan-container-${index}`);
                    const jawabanContainer = document.getElementById(`jawaban-benar-container-${index}`);

                    if (jenis === 'pilihan_ganda') {
                        pilihanContainer.style.display = 'block';
                        jawabanContainer.style.display = 'block';
                    } else {
                        pilihanContainer.style.display = 'none';
                        jawabanContainer.style.display = 'none';
                    }
                }
            });

            // Menghapus pertanyaan
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-question')) {
                    const index = parseInt(e.target.dataset.index, 10);
                    const questionCard = document.getElementById(`question-card-${index}`);

                    if (questionCard) {
                        questionCard.remove();
                        updateQuestionIndices();
                    } else {
                        console.error(`Pertanyaan dengan index ${index} tidak ditemukan.`);
                    }
                }
            });

            // Menyimpan data ujian dan pertanyaan
            document.getElementById('create-ujian-form').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                fetch('{{ route('ujian.store') }}', {
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
                            window.location.href = '/ujian';
                        } else {
                            alert('Terjadi kesalahan: ' + data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    </script>
@endpush
