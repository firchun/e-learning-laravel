@extends('layouts.blank')

@section('content')
    @include('layouts.backend.alert')

    <div class="container mt-4 mb-30">
        <div class="row">
            <div class="col-12">
                <div class="card mb-30">
                    <div class="card-body">
                        <h4 class="text-primary">{{ $ujian->matkul->nama_matkul }} ({{ $ujian->jenis }})</h4>
                        <p><strong>Keterangan :</strong> {{ $ujian->keterangan }}</p>
                    </div>
                </div>
            </div>
            <!-- Kolom Soal -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <!-- Modal Informasi Ujian -->
                        <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel"
                            aria-hidden="true" data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="infoModalLabel">Informasi Ujian</h5>

                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Mata Kuliah:</strong> {{ $ujian->matkul->nama_matkul }}</p>
                                        <p><strong>Jenis Ujian:</strong> {{ $ujian->jenis }}</p>
                                        <p><strong>Waktu Ujian:</strong> {{ date('H:i', strtotime($ujian->waktu_mulai)) }} -
                                            {{ date('H:i', strtotime($ujian->waktu_selesai)) }}</p>
                                        <p><strong>Jumlah Soal:</strong> {{ count($ujian->pertanyaanUjian) }} soal</p>
                                        <p><strong>Keterangan:</strong> {{ $ujian->keterangan }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-success" id="startExam"
                                            data-dismiss="modal">Mulai Ujian</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form id="ujianForm" action="{{ route('ujian.submit', $ujian->id) }}" method="POST">
                            @csrf
                            <div id="soal-container">
                                @foreach ($ujian->pertanyaanUjian as $index => $soal)
                                    <div class="soal" data-index="{{ $index }}"
                                        style="display: {{ $index == 0 ? 'block' : 'none' }};">
                                        <p><strong>{{ $index + 1 }}. {{ $soal->text_pertanyaan }}</strong></p>

                                        @if ($soal->jenis_pertanyaan == 'pilihan_ganda')
                                            @php $pilihan = json_decode($soal->pilihan, true); @endphp
                                            @foreach ($pilihan as $key => $value)
                                                <div class="form-check custom-radio mb-2 p-3 border rounded shadow-sm d-flex align-items-center"
                                                    style="transition: all 0.3s; cursor: pointer;"
                                                    onclick="selectOption(this)">
                                                    <input class="form-check-input me-2" type="radio"
                                                        name="jawaban[{{ $soal->id }}]" value="{{ $key }}"
                                                        id="radio{{ $soal->id }}{{ $key }}"
                                                        onchange="highlightSelected(this)">
                                                    <label class="form-check-label w-100"
                                                        for="radio{{ $soal->id }}{{ $key }}">
                                                        <strong>{{ $key }}.</strong> {{ $value }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        @else
                                            <textarea class="form-control mt-2" name="jawaban[{{ $soal->id }}]" rows="3" required></textarea>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" id="prev" class="btn btn-secondary" disabled>❮
                                    Sebelumnya</button>
                                <button type="button" id="next" class="btn btn-primary">Selanjutnya ❯</button>
                                <button type="submit" id="submit" class="btn btn-success"
                                    style="display: none;">Selesai</button>
                            </div>
                            <!-- Tambahkan Modal Konfirmasi -->
                            <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered ">
                                    <div class="modal-content bg-warning">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmModalLabel">Konfirmasi</h5>
                                            <button type="button" class="btn-close" data-dismiss="modal"
                                                aria-label="Close">x</button>
                                        </div>
                                        <div class="modal-body">
                                            Apakah Anda yakin ingin mengakhiri ujian? Pastikan semua jawaban telah diisi
                                            dengan benar.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Batal</button>
                                            <button type="button" class="btn btn-danger" id="confirmSubmit">Ya,
                                                Selesai</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Kolom Nomor Soal -->

            <div class="col-md-4">
                <p><strong>Waktu Tersisa:</strong> <br>
                <h2 id="timer" class="fw-bold text-danger">Menghitung...</h2>
                </p>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Nomor Soal</h5>
                        <div class="nomor-container">
                            @foreach ($ujian->pertanyaanUjian as $index => $soal)
                                <button class="nomor btn btn-outline-primary" data-index="{{ $index }}">
                                    {{ $index + 1 }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <!-- Script -->

    <script>
        document.querySelector("#submit").addEventListener("click", function(event) {
            event.preventDefault(); // Mencegah submit langsung
            let modal = new bootstrap.Modal(document.getElementById('confirmModal'));
            modal.show();
        });

        document.querySelector("#confirmSubmit").addEventListener("click", function() {
            document.querySelector("#ujianForm").submit(); // Submit form setelah konfirmasi
        });
    </script>
    <script>
        let currentSoal = 0;
        const totalSoal = {{ count($ujian->pertanyaanUjian) }};
        const waktuSelesai = new Date("{{ $ujian->tanggal_ujian }}T{{ $ujian->waktu_selesai }}");

        document.querySelector("#next").addEventListener("click", () => {
            document.querySelectorAll(".soal")[currentSoal].style.display = "none";
            currentSoal++;
            document.querySelectorAll(".soal")[currentSoal].style.display = "block";
            updateNavigation();
        });

        document.querySelector("#prev").addEventListener("click", () => {
            document.querySelectorAll(".soal")[currentSoal].style.display = "none";
            currentSoal--;
            document.querySelectorAll(".soal")[currentSoal].style.display = "block";
            updateNavigation();
        });

        document.querySelectorAll(".nomor").forEach(button => {
            button.addEventListener("click", function() {
                document.querySelectorAll(".soal")[currentSoal].style.display = "none";
                currentSoal = this.dataset.index;
                document.querySelectorAll(".soal")[currentSoal].style.display = "block";
                updateNavigation();
            });
        });

        function updateNavigation() {
            document.querySelector("#prev").disabled = currentSoal == 0;
            document.querySelector("#next").style.display = currentSoal == totalSoal - 1 ? "none" : "inline";
            document.querySelector("#submit").style.display = currentSoal == totalSoal - 1 ? "inline" : "none";

            document.querySelectorAll(".nomor").forEach(btn => btn.classList.remove("btn-primary"));
            document.querySelector(`.nomor[data-index="${currentSoal}"]`).classList.add("btn-primary");
        }

        let waktuMulai;
        let waktuAkhir;
        let timerInterval;

        document.addEventListener("DOMContentLoaded", function() {
            let infoModal = new bootstrap.Modal(document.getElementById('infoModal'), {
                backdrop: 'static',
                keyboard: false
            });
            infoModal.show();

            document.querySelector("#startExam").addEventListener("click", function() {
                waktuMulai = new Date(); // Waktu mulai saat tombol ditekan
                waktuAkhir = new Date(waktuMulai.getTime() + 60 * 60 * 1000); // Tambah 1 jam
                hitungWaktu(); // Mulai menghitung waktu
            });
        });

        function hitungWaktu() {
            if (timerInterval) clearInterval(timerInterval); // Pastikan tidak ada interval timer sebelumnya

            timerInterval = setInterval(() => {
                const sekarang = new Date();
                if (sekarang >= waktuAkhir) {
                    document.querySelector("#timer").innerText = "Waktu habis!";
                    document.querySelector("#ujianForm").submit(); // Auto-submit saat waktu habis
                    clearInterval(timerInterval);

                    // Nonaktifkan navigasi dan input jawaban
                    document.querySelectorAll("input, textarea, button").forEach(el => el.disabled = true);
                    return;
                }

                const sisa = Math.floor((waktuAkhir - sekarang) / 1000);
                const menit = Math.floor(sisa / 60);
                const detik = sisa % 60;
                document.querySelector("#timer").innerText = `${menit} menit ${detik} detik`;
            }, 1000);
        }
        hitungWaktu();
    </script>
    <script>
        function highlightSelected(input) {
            // Hapus warna kuning dari semua opsi dalam satu grup
            document.querySelectorAll(`input[name="${input.name}"]`).forEach(radio => {
                radio.parentElement.style.backgroundColor = "";
            });

            // Tambahkan warna kuning ke opsi yang dipilih
            input.parentElement.style.backgroundColor = "yellow";
        }

        function selectOption(div) {
            let radio = div.querySelector('input[type="radio"]');
            if (!radio.checked) {
                radio.checked = true;
                highlightSelected(radio);
            }
        }
    </script>
@endpush
@push('css')
    <!-- Styling -->
    <style>
        .nomor-container {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .nomor {
            width: 45px;
            height: 45px;
            font-size: 16px;
        }

        .navigation {
            margin-top: 20px;
        }

        .form-check.custom-radio:hover {
            background-color: #f8f9fa;
            transform: scale(1.02);
        }

        .form-check-input:checked {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
@endpush
