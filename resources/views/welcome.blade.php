@extends('layouts.auth.app')

@section('content')
    <div class="product-wrap">
        <div class="product-list">
            <ul class="row justify-content-center">
                <div class="col-12 mb-4">
                    <form>
                        <div class="form-group mb-0 d-flex">
                            <input type="text" class="form-control search-input" placeholder="Cari Mata Kuliah di sini....">
                        </div>
                    </form>
                </div>
                <li class="col-lg-4 col-md-6 col-sm-12">
                    <div class="product-box">
                        <div class="producct-img"><img src="{{ asset('backend_theme/') }}/vendors/images/product-img3.jpg"
                                alt=""></div>
                        <div class="product-caption">
                            <h4><a href="#">Contrast Lace-Up Sneakers</a></h4>
                            <div class="price">
                                <small>Dosen Pengampu :</small><br>
                                <b class="text-dark">Firmansyah Diana</b>
                            </div>
                            <a href="#" class="btn btn-outline-primary">Buka Mata Kuliah</a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
@endsection
