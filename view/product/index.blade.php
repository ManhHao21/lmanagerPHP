@extends('layouts.layouts')

@section('contents')
<div class="dashboard-wrapper">
    <div class="dashboard-ecommerce">
        <div class="container-fluid dashboard-content">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="page-header">
                        <h2 class="pageheader-title">Quản lý sản phẩm</h2>
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Trang chủ</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        <a href="/admin/product" class="breadcrumb-link">Quản lý sản phẩm</a>
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
                        <form method="GET" action="/admin/product" class="mb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <input type="text" name="keyword" class="form-control"
                                        placeholder="Tìm theo tên sản phẩm" value="{{ $keyword }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="category_id" class="form-control">
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category['id'] }}" {{ $category_id == $category['id'] ? 'selected' : '' }}>
                                            {{ $category['name'] }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                                    <a href="/admin/product" class="btn btn-warning">Reset</a>
                                </div>
                            </div>
                        </form>
                        <a href="/admin/product/create" class="btn btn-primary flex justify-content-end">Thêm sản phẩm</a>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr class="border-0">
                                        <th class="border-0">#</th>
                                        <th class="border-0">Hình ảnh</th>
                                        <th class="border-0">Tên sản phẩm</th>
                                        <th class="border-0">Danh mục</th>
                                        <th class="border-0">Giá</th>
                                        <th class="border-0">Ngày tạo</th>
                                        <th class="border-0">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $index => $product)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <img src="/uploads/{{ urlencode($product['image']) }}" alt="Hình ảnh sản phẩm"
                                                class="img-thumbnail" width="80" height="80">

                                        </td>
                                        <td>{{ $product['name'] }}</td>
                                        <td>{{ $product['category_name'] }}</td>
                                        <td>{{ number_format($product['price'], 0, ',', '.') }} VND</td>
                                        <td>{{ date('d-m-Y', strtotime($product['created_at'])) }}</td>
                                        <td>
                                            <a href="/admin/product/edit/{{ $product['id'] }}"
                                                class="btn btn-warning btn-sm">Sửa</a>
                                            <a href="/admin/product/delete/{{ $product['id'] }}"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">Xóa</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @if (count($products) == 0)
                                    <tr>
                                        <td colspan="7" class="text-center">Không có sản phẩm nào.</td>
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