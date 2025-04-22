@extends('layouts.front')

@section('content')
    <!-- banner -->
    <section class="section pb-0">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-7 text-center text-lg-left">
                    <h1 class="mb-4">Belajar Seru dengan Pendekatan <span
                            style="background-color: red;color:white;">Gamifikasi</span>
                    </h1>
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
                    <div class="row justify-content-center" id="matkul-list">
                        {{-- Dynamic Item List --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /topics -->

    <!-- call to action -->
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            const loadingSpinner =
                '<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>';

            function loadMatkul(query = '', page = 1) {
                $('#matkul-list').html(loadingSpinner);
                $.ajax({
                    url: '/api/matkul/getall-home',
                    type: 'GET',
                    data: {
                        search: query,
                        page: page
                    },
                    success: function(response) {
                        $('#matkul-list').empty();

                        if (response.data.length === 0) {
                            $('#matkul-list').html(`
                            <div class="col-12 text-center">
                                <img class="img-fluid" style="height:200px;" src="{{ asset('frontend') }}/images/no-search-found.png">
                                <h3>Matakuliah tidak ditemukan</h3>
                            </div>
                        `);
                            return;
                        }

                        response.data.forEach(matkul => {
                            $('#matkul-list').append(`
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-body">
                                        <i class="card-icon ti-book mb-4"></i>
                                        <h3 class="card-title h5">${matkul.nama_matkul}</h3>
                                        <p class="card-text">Dosen: ${matkul.nama_dosen ?? '-'}</p>
                                        <a href="/matkul/${matkul.id}" class="stretched-link"></a>
                                    </div>
                                </div>
                            </div>
                        `);
                        });

                        // Pagination
                        let paginationHTML =
                            `<div class="col-12"><nav><ul class="pagination justify-content-center">`;
                        if (response.prev_page_url) {
                            paginationHTML +=
                                `<li class="page-item"><a class="page-link" href="#" data-page="${response.current_page - 1}">&laquo;</a></li>`;
                        }

                        for (let i = 1; i <= response.last_page; i++) {
                            paginationHTML += `<li class="page-item ${i === response.current_page ? 'active' : ''}">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>`;
                        }

                        if (response.next_page_url) {
                            paginationHTML +=
                                `<li class="page-item"><a class="page-link" href="#" data-page="${response.current_page + 1}">&raquo;</a></li>`;
                        }

                        paginationHTML += `</ul></nav></div>`;
                        $('#matkul-list').append(paginationHTML);
                    },
                    error: function() {
                        console.log('Gagal memuat mata kuliah.');
                    }
                });
            }

            // Load pertama kali
            loadMatkul();

            // Cari
            $('#search').on('input', function() {
                const query = $(this).val();
                loadMatkul(query);
            });

            // Handle klik halaman
            $(document).on('click', '.pagination .page-link', function(e) {
                e.preventDefault();
                const page = $(this).data('page');
                const query = $('#search').val();
                loadMatkul(query, page);
            });
        });
    </script>
@endpush
