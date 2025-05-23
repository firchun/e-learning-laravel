@extends('layouts.front')

@section('content')
    <!-- banner -->
    <section class="section pb-0">
        <div class="container ">
            <h3 class="mb-4">Daftar Dosen</h3>
            <div class="search-wrapper mb-3">
                <input type="text" name="search" id="search" class="form-control form-control-lg"
                    placeholder="Cari Dosen">
            </div>
            <div class="row justify-content-center" id="dosen-list">
                @forelse ($dosen as $index => $item)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm border-0 hover-shadow" style="transition: 0.3s;">
                            <div class="card-body">
                                <h5 class="card-title">{{ $item->name }}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">NIP: {{ $item->identity }}</h6>
                                <p class="card-text badge badge-danger">Mengampu {{ $item->total_matkul }} matakuliah.</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center"><img class="img-fluid" style="height:200px;"
                                src="{{ asset('frontend') }}/images/no-search-found.png">
                            <h3>Dosen tidak ditemukan</h3>
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
                    url: '/dosen/cari',
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
                                            <h6 class="card-subtitle mb-2 text-muted">NIP: ${item.identity}</h6>
                                            <p class="card-text badge badge-danger">Mengampu ${item.total_matkul} matakuliah.</p>
                                        </div>
                                    </div>
                                </div>`;
                            });
                        } else {
                            html =
                                `<div class="col-12">
                                    <div class="text-center"><img class="img-fluid" style="height:200px;" src="{{ asset('frontend') }}/images/no-search-found.png">
                                        <h3>Dosen tidak ditemukan</h3>
                                    </div>
                                </div>`;
                        }

                        $('#dosen-list').html(html);
                    },
                    error: function() {
                        console.log('Gagal memuat data dosen.');
                    }
                });
            });
        });
    </script>
@endpush
