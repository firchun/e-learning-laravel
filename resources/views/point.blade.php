@extends('layouts.front')

@section('content')
    <section class="section pb-0">
        <div class="container">
            <h3 class="mb-4">Leaderboard</h3>
            <div class="search-wrapper mb-3">
                <input type="text" name="search" id="search" class="form-control form-control-lg"
                    placeholder="Cari Mahasiswa">
            </div>
            <div class="list-group" id="dosen-list">
                @forelse ($mahasiswa as $index => $item)
                    <div
                        class="list-group-item d-flex justify-content-between align-items-center flex-wrap rounded shadow border border-danger">
                        <div class="d-flex align-items-center">
                            <h4 class="mb-0 mr-3 text-primary" style="width: 40px;">{{ $index + 1 }}</h4>
                            <div>
                                <h5 class="mb-1">{{ $item->name }}</h5>
                                <small class="text-muted">NPM: {{ $item->identity }}</small>
                            </div>
                        </div>
                        <span class="badge badge-danger badge-pill p-2 px-3">{{ $item->total_point }} Point</span>
                    </div>
                @empty
                    <div class="text-center">
                        <img class="img-fluid" style="height:200px;"
                            src="{{ asset('frontend') }}/images/no-search-found.png">
                        <h3>Mahasiswa tidak ditemukan</h3>
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
                    url: '/mahasiswa/cari',
                    type: 'GET',
                    data: {
                        search: query
                    },
                    success: function(response) {
                        let html = '';
                        let medal = '';
                        // if (index === 0) medal = '🥇';
                        // else if (index === 1) medal = '🥈';
                        // else if (index === 2) medal = '🥉';
                        if (response.length > 0) {
                            response.forEach((item, index) => {
                                html += `
                              <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap rounded shadow border border-danger">
                                  <div class="d-flex align-items-center">
                                      <h4 class="mb-0 mr-3 text-primary" style="width: 40px;">${index + 1} </h4>
                                      <div>
                                          <h5 class="mb-1">${item.name}</h5>
                                          <small class="text-muted">NPM: ${item.identity}</small>
                                      </div>
                                  </div>
                                  <span class="badge badge-danger badge-pill p-2 px-3">${item.total_point} Point</span>
                              </div>
                          `;
                            });
                        } else {
                            html = `
                          <div class="text-center">
                              <img class="img-fluid" style="height:200px;" src="{{ asset('frontend') }}/images/no-search-found.png">
                              <h3>Mahasiswa tidak ditemukan</h3>
                          </div>`;
                        }

                        $('#dosen-list').html(html);
                    },
                    error: function() {
                        console.log('Gagal memuat data leaderboard.');
                    }
                });
            });
        });
    </script>
@endpush
