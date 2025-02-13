@extends('layouts.backend.admin')
@section('content')
    <div class="section mb-30">
        <div class="row">
            <div class="col-12 mb-3">
                @if (Auth::user()->role == 'Mahasiswa')
                    @if ($riwayat->count() == 0)
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 100%;" aria-valuenow="100"
                                aria-valuemin="0" aria-valuemax="100">
                                Belum ada materi yang di buka
                            </div>
                        </div>
                    @else
                        <div class="progress border border-primary" style="height: 20px;">
                            <div class="progress-bar {{ $riwayat->count() >= $materi->count() / 2 ? 'bg-success' : 'bg-warning' }}"
                                role="progressbar"
                                style="width: {{ $materi->count() > 0 ? ($riwayat->count() / $materi->count()) * 100 : 0 }}%;"
                                aria-valuenow="{{ $materi->count() > 0 ? ($riwayat->count() / $materi->count()) * 100 : 0 }}"
                                aria-valuemin="0" aria-valuemax="100">
                                {{ $materi->count() > 0 ? round(($riwayat->count() / $materi->count()) * 100, 2) : 0 }}%
                                Diselesaikan
                            </div>
                        </div>
                    @endif
                @endif
            </div>
            <!-- Kolom Utama -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="text-primary"><i class="fas fa-book-reader"></i> {{ $title }}</h4>
                        <hr>

                        <!-- Detail Materi -->
                        @if (Auth::user()->role == 'Mahasiswa')
                            <div class="mb-4">
                                <h5><i class="fas fa-star text-warning"></i> Point Belajar</h5>
                                <p class="mb-2">
                                    Total poin: <span class="badge bg-success fs-6">{{ $totalPoin ?? 0 }}</span>
                                </p>

                                <hr>
                            </div>

                            <div class="mb-4">
                                <h5><i class="fas fa-history"></i> Materi Terakhir Dibuka</h5>
                                @if ($materiTerakhir)
                                    <p>
                                        <strong> -> {{ $materiTerakhir->materi->judul }}</strong> <br>
                                        <small class="text-muted">Waktu terakhir diakses:
                                            <strong>{{ $materiTerakhir->created_at->format('d F Y') }}</strong>
                                        </small>
                                    </p>
                                @else
                                    <p class="text-muted">Belum ada materi yang diakses.</p>
                                @endif
                                <hr>
                            </div>
                        @else
                            <div class="mb-4">
                                <h5><i class="fas fa-star text-warning"></i> Mahasiswa</h5>
                                <p class="mb-2">
                                    Jumlah Mahasiswa : <span
                                        class="badge bg-success fs-6">{{ App\Models\Matkul::getMahasiswa($matkul->id) ?? 0 }}
                                        Mahasiswa</span>
                                </p>

                                <hr>
                            </div>
                        @endif
                        <div>
                            <h5><i class="fas fa-graduation-cap"></i> Jumlah SKS</h5>
                            <p>Total SKS: <span class="badge bg-info ">{{ $matkul->sks_matkul }} SKS</span></p>

                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('matkul') }}" class="btn btn-lg btn-secondary">
                            <i class="fa fa-arrow-left"></i>
                            Kembali</a>
                        <a href="{{ route('matkul.open-materi', $matkul->kode_matkul) }}" class="btn  btn-primary">
                            <i class="fa fa-book"></i>
                            Buka
                            Materi</a>
                        @if (Auth::user()->role == 'Dosen')
                            <a href="#" data-toggle="modal" data-target="#modalMahasiswa" class="btn btn-info"
                                data-matkul-id="{{ $matkul->id }}">
                                <i class="fa fa-users"></i>
                                Mahasiswa
                            </a>
                            <a href="{{ route('matkul.materi', $matkul->kode_matkul) }}" class="btn  btn-warning">
                                <i class="fa fa-book"></i>
                                Update
                                Materi</a>
                            <a href="#" class="btn btn-warning" id="btnUjian" onclick="showJenisUjianModal()">
                                <i class="fa fa-book"></i>
                                Ujian</a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Kolom Daftar Isi -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="text-primary"><i class="fas fa-list"></i> Daftar Materi</h4>
                        <hr>
                        @foreach ($materi as $item)
                            <div class="p-2 border rounded mb-2 bg-light">
                                <h6 class="mb-1"><i class="fas fa-file-alt"></i> {{ $item->judul }}</h6>
                                @if (Auth::user()->role == 'Mahasiswa')
                                    <p class="mb-1">
                                        <small>Status:
                                            <strong
                                                class="{{ App\Models\MateriMatkul::getRiwayat($item->id) == 1 ? 'text-success' : 'text-danger' }}">
                                                <i
                                                    class="fas {{ App\Models\MateriMatkul::getRiwayat($item->id) == 1 ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                                {{ App\Models\MateriMatkul::getRiwayat($item->id) == 1 ? 'Sudah Dibuka' : 'Belum Dibuka' }}
                                            </strong>
                                        </small>
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pilih Jenis Ujian -->
    <div class="modal fade" id="modalJenisUjian" tabindex="-1" aria-labelledby="modalJenisUjianLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalJenisUjianLabel">Pilih Jenis Ujian</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i
                            class="bi bi-x"></i></button>
                </div>
                <div class="modal-body text-center">
                    <button type="button" class="btn btn-primary" id="btnTambahUTS"
                        onclick="cekKetersediaanUjian('UTS')">Tambah UTS</button>
                    <button type="button" class="btn btn-warning" id="btnTambahUAS"
                        onclick="cekKetersediaanUjian('UAS')">Tambah UAS</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form Ujian -->
    <div class="modal fade" id="modalFormUjian" tabindex="-1" aria-labelledby="modalFormUjianLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFormUjianLabel">Tambah Ujian</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i
                            class="bi bi-x"></i></button>
                </div>
                <div class="modal-body">
                    <form id="formUjian">
                        <input type="hidden" id="id_matkul" name="id_matkul" value="{{ $matkul->id }}" readonly>
                        <input type="hidden" id="jenis_ujian" name="jenis" readonly>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan:</label>
                            <textarea id="keterangan" name="keterangan" class="form-control"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_ujian" class="form-label">Tanggal Ujian:</label>
                            <input class="form-control" type="date" id="tanggal_ujian" name="tanggal_ujian" required>
                        </div>

                        <div class="mb-3">
                            <label for="waktu_mulai" class="form-label">Waktu Mulai:</label>
                            <input class="form-control" type="datetime-local" id="waktu_mulai" name="waktu_mulai"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="waktu_selesai" class="form-label">Waktu Selesai:</label>
                            <input class="form-control" type="datetime-local" id="waktu_selesai" name="waktu_selesai"
                                required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Daftar Mahasiswa -->
    <div class="modal fade" id="modalMahasiswa" tabindex="-1" role="dialog" aria-labelledby="modalMahasiswaLabel"
        aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalMahasiswaLabel">Daftar Mahasiswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i
                            class="bi bi-x"></i>
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Tabel Mahasiswa -->
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mahasiswa</th>
                            </tr>
                        </thead>
                        <tbody id="mahasiswa-list">
                            <!-- Data mahasiswa akan dimuat di sini -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $('#modalMahasiswa').on('show.bs.modal', function(e) {
            // Ambil matkulId dari tombol yang memicu modal
            var matkulId = $(e.relatedTarget).data('matkul-id');

            // AJAX request untuk mengambil data mahasiswa
            $.ajax({
                url: '/get-mahasiswa/' + matkulId,
                method: 'GET',
                success: function(response) {
                    console.log(response);
                    var mahasiswaList = response.mahasiswa; // Asumsi response berisi data mahasiswa
                    var tableBody = $('#mahasiswa-list');
                    tableBody.empty(); // Kosongkan tabel sebelumnya

                    mahasiswaList.forEach(function(mahasiswa, index) {
                        tableBody.append(
                            '<tr><td>' + (index + 1) + '</td><td>' + mahasiswa.mahasiswa
                            .name +
                            '<br><small>' + mahasiswa.mahasiswa
                            .identity + '</small></td></tr>'
                        );
                    });
                },
                error: function() {
                    alert('Gagal memuat data mahasiswa.');
                }
            });
        });

        // Tampilkan modal untuk memilih jenis ujian
        function showJenisUjianModal() {
            $('#modalJenisUjian').modal('show');
        }

        // Cek ketersediaan ujian sebelum menampilkan modal form ujian
        function cekKetersediaanUjian(jenis) {
            var matkulId = "{{ $matkul->id }}";
            $.ajax({
                url: '/cek-ujian/' + matkulId + '/' + jenis,
                method: 'GET',
                success: function(response) {
                    if (response.tersedia) {
                        alert('Ujian ' + jenis + ' sudah tersedia.');
                        window.location.href = '/ujian/create/' + matkulId;
                    } else {
                        showUjianModal(jenis);
                    }
                },
                error: function() {
                    alert('Gagal memeriksa ketersediaan ujian.');
                }
            });
        }

        // Tampilkan modal ujian dengan jenis yang dipilih
        function showUjianModal(jenis) {
            document.getElementById('jenis_ujian').value = jenis;
            document.getElementById('modalFormUjianLabel').innerText = `Tambah ${jenis}`;
            $('#modalJenisUjian').modal('hide');
            $('#modalFormUjian').modal('show');
        }

        // Submit form ujian
        $('#formUjian').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            $.ajax({
                url: "{{ route('ujian.store') }}",
                method: "POST",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        alert('Ujian berhasil disimpan!');
                        window.location.href = 'ujian/create/' + response.id;
                    } else {
                        alert('Gagal menyimpan ujian.');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Terjadi kesalahan saat menyimpan ujian.');
                }
            });
        });

        // Pengecekan ketersediaan ujian saat halaman dimuat
        $(document).ready(function() {
            var matkulId = "{{ $matkul->id }}";
            $.ajax({
                url: '/cek-ujian/' + matkulId + '/UTS',
                method: 'GET',
                success: function(response) {
                    if (response.tersedia) {
                        $('#btnTambahUTS').text('Buka UTS').attr('onclick',
                            'window.location.href="/ujian/create/' + matkulId + '"');
                    }
                },
                error: function() {
                    console.error('Gagal memeriksa ketersediaan UTS.');
                }
            });

            $.ajax({
                url: '/cek-ujian/' + matkulId + '/UAS',
                method: 'GET',
                success: function(response) {
                    if (response.tersedia) {
                        $('#btnTambahUAS').text('Buka UAS').attr('onclick',
                            'window.location.href="/ujian/create/' + matkulId + '"');
                    }
                },
                error: function() {
                    console.error('Gagal memeriksa ketersediaan UAS.');
                }
            });
        });
    </script>
@endpush
