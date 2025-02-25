@extends('layouts.backend.admin')
@push('css')
    <style>
        .scrollable {
            height: 70vh;
            overflow-y: auto;
            overflow-x: hidden;
        }
    </style>
@endpush

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-12">
                <a class="btn btn-warning mb-2" href="{{ route('matkul.daftar-isi', $matkul->kode_matkul) }}">
                    <i class="bi bi-house"></i>
                    Daftar Isi
                </a>
            </div>
            <div class="col-lg-8 scrollable">

                <div id="list_materi">
                    <div id="load-spinner" class="text-center my-3 d-none">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p>Memuat materi...</p>
                    </div>
                </div>

            </div>
            <div class="col-lg-4 ">
                <div class="row justify-content-center mb-3">
                    <div class="col-auto">

                        <button id="prev-button" class="btn btn-outline-secondary" onclick="prevPage()" disabled>
                            <i class="bi bi-arrow-left"></i> Kembali
                        </button>
                        <button id="next-button" class="btn btn-outline-primary" onclick="nextPage()" disabled>
                            Selanjutnya <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>
                <div class="scrollable">

                    <div class="" id="list_title">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        let editorInstance;
        let currentPage = 0; // Halaman saat ini
        let materiList = []; // Daftar materi yang diterima dari server

        // Load daftar materi
        function loadMateri() {
            $('#load-spinner').removeClass('d-none'); // Tampilkan spinner
            $.ajax({
                url: "{{ route('materi.index') }}", // Endpoint untuk mengambil daftar materi
                method: "GET",
                success: function(response) {
                    if (response.length === 0) {
                        $('#list_materi').html(`
                    <div class="text-center mt-3">
                        <p class="text-muted">Belum ada materi yang tersedia.</p>
                    </div>
                `);
                        return;
                    }

                    materiList = response;
                    currentPage = 0;
                    saveRiwayat();
                    renderMateri();
                    renderTitles();
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                },
                complete: function() {
                    $('#load-spinner').addClass('d-none');
                }
            });
        }

        // Render konten materi berdasarkan halaman
        function renderMateri() {
            if (materiList.length === 0) return;

            const materi = materiList[currentPage];
            const html = `
        <div class="card mb-3" style="border-radius:20px;">
            <div class="card-body">
                <h5 class="card-title">
                    #${currentPage + 1} | ${materi.judul}
                </h5>
                <hr>
                ${materi.image ? `<img src="/storage/${materi.image}" alt="${materi.judul}" class="img-fluid mb-3 rounded" style="max-height: 300px; object-fit: cover;">` : ''}
                <p class="card-text">${materi.isi_materi}</p>
                ${materi.file ? `<div class="mt-3">
                   <a href="/storage/${materi.file}" class="btn btn-warning btn-sm" download>
                             <i class="bi bi-download"></i> Download File
                      </a>
                </div>` : ''}
            </div>
        </div>
    `;

            $('#list_materi').html(html);
            updatePaginationButtons(); // Perbarui tombol navigasi
        }

        // Render daftar semua judul
        function renderTitles() {
            let titleHtml = '';
            materiList.forEach((materi, index) => {
                titleHtml += `
            <div class="card mb-2" style="border-radius:20px; cursor: pointer;" onclick="goToPage(${index})">
                <div class="card-body">
                    <h6 class="${currentPage === index ? 'text-primary' : ''}">
                        #${index + 1} | <b>${materi.judul}</b>
                    </h6>
                </div>
            </div>
        `;
            });
            $('#list_title').html(titleHtml);
        }

        // Perbarui tombol navigasi
        function updatePaginationButtons() {
            const prevButton = $('#prev-button');
            const nextButton = $('#next-button');

            prevButton.prop('disabled', currentPage === 0); // Nonaktifkan jika halaman pertama
            nextButton.prop('disabled', currentPage === materiList.length - 1); // Nonaktifkan jika halaman terakhir
        }

        // Navigasi ke halaman berikutnya
        function nextPage() {
            if (currentPage < materiList.length - 1) {
                currentPage++;
                saveRiwayat();
                renderMateri();
                renderTitles(); // Perbarui highlight pada daftar judul
            }
        }

        // Navigasi ke halaman sebelumnya
        function prevPage() {
            if (currentPage > 0) {
                currentPage--;
                saveRiwayat();
                renderMateri();
                renderTitles(); // Perbarui highlight pada daftar judul
            }
        }

        // Navigasi ke halaman tertentu
        function goToPage(index) {
            currentPage = index;
            saveRiwayat();
            renderMateri();
            renderTitles(); // Perbarui highlight pada daftar judul
        }
        // Simpan riwayat materi
        function saveRiwayat() {
            const materi = materiList[currentPage];
            const data = {
                id_matkul: materi.id_matkul, // Ganti sesuai dengan struktur data dari server
                id_materi: materi.id
            };

            $.ajax({
                url: "{{ route('materi.store-riwayat') }}",
                method: "POST",
                data: data,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}" // Tambahkan token CSRF untuk keamanan
                },
                success: function(response) {
                    // alert('Riwayat berhasil disimpan:', message);
                },
                error: function(xhr) {
                    // alert('Gagal menyimpan riwayat:', xhr.responseText);
                }
            });
        }

        // Panggil loadMateri saat halaman selesai dimuat
        $(document).ready(function() {
            loadMateri();
        });
    </script>
@endpush
