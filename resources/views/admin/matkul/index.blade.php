@extends('layouts.backend.admin')

@section('content')
    <div class="product-wrap">
        <div class="product-list">
            <ul class="row">
                <div class="col-12 mb-4">
                    <div class=" btn-group " role="group">
                        <button class="btn btn-secondary refresh btn-default" type="button">
                            <span>
                                <i class="bi bi-arrow-clockwise me-sm-1"> </i>
                                <span class="d-none d-sm-inline-block"></span>
                            </span>
                        </button>
                        <button class="btn  create-new btn-primary" type="button" data-bs-toggle="modal"
                            data-bs-target="#create">
                            <span>
                                <i class="bi bi-plus me-sm-1"> </i>
                                <span class="d-none d-sm-inline-block">Tambah Mata Kuliah</span>
                            </span>
                        </button>
                    </div>
                </div>

                <li class="col-lg-4 col-md-6 col-sm-12">
                    <div class="product-box">
                        <div class="producct-img"><img src="{{ asset('backend_theme/') }}/vendors/images/product-img3.jpg"
                                alt=""></div>
                        <div class="product-caption">
                            <h4><a href="#">Test</a></h4>
                            <a href="#" class="btn btn-outline-primary"><i class="bi bi-pencil-square"></i> Edit</a>
                            <a href="#" class="btn btn-outline-danger"><i class="bi bi-trash bi-lg"></i></a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    @include('admin.matkul.components.modal')
@endsection
@include('admin.matkul.script')
