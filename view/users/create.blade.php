@extends('layouts.layouts')

@section('contents')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center bg-primary text-white">
                    <h4>Tạo tài khoản mới</h4>
                </div>
                <div class="card-body">
                    <!-- Hiển thị thông báo lỗi -->
                    @if (isset($errors))
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="/admin/user/store">
                        <div class="mb-3">
                            <label class="form-label">Tên người dùng:</label>
                            <input type="text" name="name" class="form-control" >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email:</label>
                            <input type="email" name="email" class="form-control" >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mật khẩu:</label>
                            <input type="password" name="password" class="form-control" >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Vai trò:</label>
                            <select name="role" class="form-control">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Tạo tài khoản</button>
                            <a href="/admin/user" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
