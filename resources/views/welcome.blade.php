@extends('layouts.front')

@section('content')
    <!-- banner -->
    <section class="section pb-0">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-7 text-center text-lg-left">
                    <h1 class="mb-4">Belajar Seru dengan Pendekatan Gamifikasi</h1>
                    <p class="mb-4">Jelajahi berbagai mata kuliah dengan sistem pembelajaran interaktif berbasis
                        gamifikasi. Kumpulkan poin, raih lencana, dan capai level baru sambil mengembangkan potensimu di era
                        digital. Mulai petualangan belajarmu sekarang!</p>
                    <a href="{{ route('register') }}" class="btn btn-primary">Daftar & Mulai Belajar</a>

                </div>
                <div class="col-lg-4 d-lg-block d-none">
                    <img src="{{ asset('frontend/') }}/images/banner.jpg" alt="illustration" class="img-fluid">
                </div>
            </div>
        </div>
    </section>
    <!-- /banner -->
    <!-- topics -->
    <section class="section pb-0">
        <div class="container ">
            <h2 class="section-title">Cari Matakuliah di sini...</h2>
            <div class="product-wrap mt-4">
                <div class="product-list">
                    <div class="search-wrapper mb-3">
                        <input type="text" name="search" id="search"
                            class="form-control form-control-lg  border border-danger" placeholder="Cari Matakuliah">
                    </div>
                    <div class="row" id="matkul-list">
                        {{-- Dynamic Item List --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /topics -->

    <!-- call to action -->
    <!-- call to action -->
    <section class="section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4 text-center d-lg-block d-none">
                    <img src="{{ asset('frontend/') }}/images/cta-illustration.jpg" class="img-fluid"
                        alt="Ilustrasi gamifikasi">
                </div>
                <div class="col-lg-8 text-lg-left text-center">
                    <h2 class="mb-3">Sistem Pembelajaran Lebih Seru dengan Gamifikasi</h2>
                    <p>Platform ini dirancang menggunakan pendekatan <strong>gamifikasi</strong>, yang menggabungkan elemen
                        permainan seperti poin, lencana, dan tantangan agar proses belajar lebih menarik, interaktif, dan
                        memotivasi.
                        Cocok untuk semua gaya belajar!</p>
                </div>
            </div>
        </div>
    </section>
    <!-- /call to action -->
@endsection
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
                        $('#matkul-list').empty();
                        if (response.length === 0) {
                            $('#matkul-list').html(`
                                <div class="col-12">
                                    <div class="text-center"><img class="img-fluid" style="height:200px;" src="{{ asset('frontend') }}/images/no-search-found.png">
                                        <h3>No Search Found</h3>
                                    </div>
                                </div>
                            `);
                            return;
                        }

                        response.forEach(matkul => {
                            $('#matkul-list').append(`
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                    <div class="card match-height h-100 shadow-sm border-0">
                                        <div class="card-body">
                                            <i class="card-icon ti-book mb-4"></i>
                                            <h3 class="card-title h5">${matkul.nama_matkul}</h3>
                                            <p class="card-text">Kode: ${matkul.kode ?? '-'}</p>
                                            <a href="/matkul/${matkul.id}" class="stretched-link" title="Lihat Detail"></a>
                                        </div>
                                    </div>
                                </div>
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
                const query = $(this).val(); // Ambil nilai input
                loadMatkul(query); // Panggil fungsi dengan parameter pencarian
            });

        });
    </script>
@endpush
