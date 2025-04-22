@extends('layouts.front')

@section('content')
    <!-- banner -->
    <section class="section pb-0">
        <div class="container ">
            <h3 class="mb-4">Point Mahasiswa</h3>
            <div class="search-wrapper mb-3">
                <input type="text" name="search" id="search" class="form-control form-control-lg"
                    placeholder="Cari Mahasiswa">
            </div>
            <div class="row" id="dosen-list">
                @forelse ($dosen as $index => $item)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm border-0 hover-shadow" style="transition: 0.3s;">
                            <div class="card-body">
                                <h5 class="card-title">{{ $item->name }}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">NPM: {{ $item->identity }}</h6>
                                <p class="card-text"><span class=" badge badge-danger"> {{ $item->total_point }}</span>
                                    Point</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center"><img class="img-fluid" style="height:200px;"
                                src="{{ asset('frontend') }}/images/no-search-found.png">
                            <h3>Mahasiswa tidak ditemukan</h3>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            $('#search').on('input', function() {
                const query = $(this).val();
                $.ajax({
                    url: '/point/cari',
                    type: 'GET',
                    data: {
                        search: query
                    },
                    success: function(response) {
                        let html = '';
                        if (response.length > 0) {
                            response.forEach((item, index) => {
                                html += `
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100 shadow-sm border-0 hover-shadow" style="transition: 0.3s;">
                                        <div class="card-body">
                                            <h5 class="card-title">${item.name}</h5>
                                            <h6 class="card-subtitle mb-2 text-muted">NPM: ${item.identity}</h6>
                                            <p class="card-text "><span class="badge badge-danger"> ${item.total_point} Point <span></p>
                                        </div>
                                    </div>
                                </div>`;
                            });
                        } else {
                            html =
                                `<div class="col-12">
                                    <div class="text-center"><img class="img-fluid" style="height:200px;" src="{{ asset('frontend') }}/images/no-search-found.png">
                                        <h3>Mahasiswa tidak ditemukan</h3>
                                    </div>
                                </div>`;
                        }

                        $('#dosen-list').html(html);
                    },
                    error: function() {
                        alert('Gagal memuat data dosen.');
                    }
                });
            });
        });
    </script>
@endpush
