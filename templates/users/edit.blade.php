@extends('layouts.layouts')

@section('title', 'Chỉnh sửa người dùng')

@section('contents')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center bg-warning text-white">
                    <h4>Chỉnh sửa tài khoản</h4>
                </div>
                <div class="card-body">
                    @if (isset($errors))
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="/ASM/user/update/{{ $user['id'] }}">
                        <div class="mb-3">
                            <label class="form-label">Tên người dùng:</label>
                            <input type="text" name="name" class="form-control" value="{{ $user['name'] }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email:</label>
                            <input type="email" name="email" class="form-control" value="{{ $user['email'] }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mật khẩu (để trống nếu không đổi):</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Vai trò:</label>
                            <select name="role" class="form-control">
                                <option value="user" {{ $user['role'] == 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ $user['role'] == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Cập nhật</button>
                            <a href="/ASM/user" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
