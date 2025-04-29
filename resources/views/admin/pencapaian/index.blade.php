@extends('layouts.backend.admin')

@section('content')
<div class="container my-4">
 <!-- Section Total Point -->
<div class="row">
  <div class="col-md-6 col-lg-8">
    <div class="row">
      @forelse($matkulProgress as $matkul)
          <div class="col-md-6 col-lg-4 mb-4">
              <div class="card shadow-sm h-100 " style="border-radius: 20px;">
                  <div class="card-body d-flex flex-column justify-content-between">
                      <div>
                          <h5 class="card-title">{{ $matkul->nama_matkul }}</h5>
                          <p class="card-text text-muted mb-2">Progress: {{ $matkul->progress ?? 0 }}%</p>
                          <div class="progress" style="height: 8px;">
                              <div class="progress-bar bg-success" role="progressbar" style="width: {{ $matkul->progress ?? 0 }}%;" aria-valuenow="{{ $matkul->progress ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                      </div>
                      {{-- Optional tambahan: tombol detail --}}
                      <div class="mt-3">
                          <a href="#" class="btn btn-primary btn-sm w-100">Lihat Detail</a>
                      </div>
                  </div>
              </div>
          </div>
      @empty
          <div class="col-12">
              <p class="text-muted">Belum ada progress tercatat.</p>
          </div>
      @endforelse
    </div>
  </div>
  <div class="col-md-6 col-lg-4 px-2 border-left ">
    <div class="card shadow-sm" style="border-radius: 20px;">
      <div class="card-body text-center">
        <h4 class="card-title">Total Poin Anda</h4>
        <p class="display-4 text-success font-weight-bold">{{ $totalPoint ?? 0 }} <i class="bi bi-star-fill"></i></p>
      </div>
    </div>
  </div>
</div>

</div>
@endsection