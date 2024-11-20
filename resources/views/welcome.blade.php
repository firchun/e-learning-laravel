@extends('layouts.auth.app')

@section('content')
    <div class="product-wrap mt-4">
        <div class="product-list">
            <div class="mb-3">
                <input type="text" name="search" id="search" class="form-control" placeholder="Cari Matakuliah">
            </div>
            <ul class="row">

                {{-- Dynamic Item List --}}
                <div id="matkul-list">

                </div>
            </ul>
        </div>
    </div>
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
                        $('.product-list ul').empty(); // Kosongkan list sebelum menambah data baru
                        if (response.length === 0) {
                            $('.product-list ul').html(
                                '<div class="col-12 mx-4"><p class="text-center">Tidak ada mata kuliah ditemukan.</p></div>'
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
                const query = $(this).val(); // Ambil nilai input
                loadMatkul(query); // Panggil fungsi dengan parameter pencarian
            });



        });
    </script>
@endpush
