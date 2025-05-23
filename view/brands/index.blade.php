@extends('layouts.layouts')

@section('contents')
    <div class="dashboard-wrapper">
        <div class="dashboard-ecommerce">
            <div class="container-fluid dashboard-content">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-header">
                            <h2 class="pageheader-title">Quản lý  thương hiệu</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Trang chủ</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">
                                            <a href="/admin/category" class="breadcrumb-link">Quản lý thương hiệu</a>
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="card w-100">
                        <div class="card-header flex">
                            <form method="GET" action="/admin/category" class="mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" name="keyword" class="form-control"
                                            placeholder="Tìm theo tên thương hiệu" value="{{ $keyword }}">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                                        <a href="/admin/category" class="btn btn-warning">Reset</a>
                                    </div>
                                </div>
                            </form>
                            <a href="/admin/brand/create" class="btn btn-primary flex justify-content-end">Thêm thương hiệu</a>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-light">
                                        <tr class="border-0">
                                            <th class="border-0">#</th>
                                            <th class="border-0">Tên thương hiệu</th>
                                            <th class="border-0">Ngày tạo</th>
                                            <th class="border-0">Ngày cập nhật</th>
                                            <th class="border-0">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($brands as $index => $brand)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $brand['name'] }}</td>
                                                <td>{{ date('d-m-Y', strtotime($brand['created_at'])) }}</td>
                                                <td>{{ date('d-m-Y', strtotime($brand['updated_at'])) }}</td>
                                                <td>
                                                    <a href="/admin/brand/edit/{{ $brand['id'] }}"
                                                        class="btn btn-warning btn-sm">Sửa</a>
                                                    <a href="/admin/brand/delete/{{ $brand['id'] }}"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Bạn có chắc muốn xóa thương hiệu này?')">Xóa</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if (count($brands) == 0)
                                            <tr>
                                                <td colspan="5" class="text-center">Không có thương hiệu nào.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        Copyright © 2024. All rights reserved.
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="text-md-right footer-links d-none d-sm-block">
                            <a href="#">About</a>
                            <a href="#">Support</a>
                            <a href="#">Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
