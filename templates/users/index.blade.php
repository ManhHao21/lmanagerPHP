@extends('layouts.layouts')

@section('contents')
    <div class="dashboard-wrapper">
        <div class="dashboard-ecommerce">
            <div class="container-fluid dashboard-content">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-header">
                            <h2 class="pageheader-title">Quản lý tài khoản người dùng</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Trang chủ</a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">
                                            <a href="/ASM/user" class="breadcrumb-link">Quản lý người dùng</a>
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
                            <form method="GET" action="/ASM/user" class="mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" name="keyword" class="form-control"
                                            placeholder="Tìm theo tên hoặc email" value="{{ $keyword }}">
                                    </div>
                                    <div class="col-md-3">
                                        <select name="role" class="form-control">
                                            <option value="">-- Chọn vai trò --</option>
                                            <option value="admin" {{ $role == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="user" {{ $role == 'user' ? 'selected' : '' }}>User</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                                        <a href="/ASM/user" class="btn btn-warning">Reset</a>
                                    </div>
                                </div>
                            </form>
                            <a href="/ASM/user/create" class="btn btn-primary flex justify-content-end">Tạo mới</a>
                        </d>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-light">
                                        <tr class="border-0">
                                            <th class="border-0">#</th>
                                            <th class="border-0">Tên người dùng</th>
                                            <th class="border-0">Email</th>
                                            <th class="border-0">Vai trò</th>
                                            <th class="border-0">Ngày tạo</th>
                                            <th class="border-0">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $index => $user)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $user['name'] }}</td>
                                                <td>{{ $user['email'] }}</td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $user['role'] == 'admin' ? 'danger' : 'primary' }}">
                                                        {{ ucfirst($user['role']) }}
                                                    </span>
                                                </td>
                                                <td>{{ date('d-m-Y', strtotime($user['created_at'])) }}</td>
                                                <td>
                                                    <a href="/ASM/user/edit/{{ $user['id'] }}"
                                                        class="btn btn-warning btn-sm">Sửa</a>
                                                    <a href="/ASM/user/delete/{{ $user['id'] }}"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if (count($users) == 0)
                                            <tr>
                                                <td colspan="7" class="text-center">Không có người dùng nào.</td>
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
