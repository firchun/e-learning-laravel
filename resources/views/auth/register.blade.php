@extends('layouts.auth.app')

@section('content')
    @php
        $title = 'register';
    @endphp
    <div class=" align-items-center row">
        <div class="col-12">
            @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>
            @elseif (Session::has('danger'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    {{ Session::get('danger') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible" role="alert">
                    @foreach ($errors->all() as $item)
                        <ul>
                            <li>{{ $item }}</li>
                        </ul>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>
            @endif

        </div>
        <div class="col-md-6 col-lg-7">
            <img src="{{ asset('backend_theme') }}/vendors/images/register-page-img.png" alt="" />
        </div>
        <div class="col-md-6 col-lg-5">
            <div class="login-box bg-white box-shadow border-radius-10">
                <div class="wizard-content">
                    <form class="tab-wizard2 wizard-circle p-3" method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="login-title mb-4">
                            <h2 class="text-center text-primary">Register To {{ env('APP_NAME') }}</h2>
                        </div>
                        <div class="form-group ">
                            <label class="col-form-label">NPM*</label>
                            <div class="">
                                <input type="number" class="form-control" name="identity" required />
                            </div>
                            @error('identity')
                                <span class="text-danger" role="alert">
                                    <small>{{ $message }}</small>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group ">
                            <label class=" col-form-label">Nama Lengkap*</label>
                            <div class="">
                                <input type="text" class="form-control" name="name" required />
                            </div>
                            @error('name')
                                <span class="text-danger" role="alert">
                                    <small>{{ $message }}</small>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group ">
                            <label class=" col-form-label">Password*</label>
                            <div class="">
                                <input type="password" class="form-control" name="password" />
                            </div>
                            @error('password')
                                <span class="text-danger" role="alert">
                                    <small>{{ $message }}</small>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group ">
                            <label class=" col-form-label">Confirm Password*</label>
                            <div class="">
                                <input type="password" class="form-control" name="password_confirmation" required
                                    id="password-confirm" />
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg btn-block">Register</button>
                        <div class="font-16 weight-600 pt-10 pb-10 text-center" data-color="#707373">
                            OR
                        </div>
                        <div class="input-group mb-0">
                            <a class="btn btn-outline-primary btn-lg btn-block" href="{{ route('login') }}">Already your
                                account?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
