@extends('layouts.backend.admin')

@section('content')
    @include('layouts.backend.alert')

    <div class="container mt-4 mb-30">
        <div class="row">
            <div class="col-12">
                <div class="card mb-30">
                    <div class="card-body">
                        <h4 class="text-primary">{{ $ujian->keterangan }} ({{ $ujian->jenis }})</h4>
                        <p><strong>Tanggal:</strong> {{ $ujian->tanggal_ujian }}</p>
                        <p><strong>Waktu Tersisa:</strong> <span id="timer"
                                class="fw-bold text-danger">Menghitung...</span></p>

                    </div>
                </div>
            </div>
            <!-- Kolom Soal -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">

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
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="jawaban[{{ $soal->id }}]" value="{{ $key }}">
                                                    <label class="form-check-label">
                                                        {{ $key }}. {{ $value }}
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
                        </form>
                    </div>
                </div>
            </div>

            <!-- Kolom Nomor Soal -->
            <div class="col-md-4">
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

    <!-- Script -->
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

        function hitungWaktu() {
            const sekarang = new Date();
            if (sekarang >= waktuSelesai) {
                alert("Waktu ujian telah habis!");
                document.querySelector("#ujianForm").submit();
            } else {
                const sisa = Math.floor((waktuSelesai - sekarang) / 1000);
                const menit = Math.floor(sisa / 60);
                const detik = sisa % 60;
                document.querySelector("#timer").innerText = `Sisa waktu: ${menit} menit ${detik} detik`;
                setTimeout(hitungWaktu, 1000);
            }
        }

        hitungWaktu();
    </script>

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
    </style>
@endsection
