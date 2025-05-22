@extends('layouts.layouts')

@section('contents')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center bg-primary text-white">
                    <h4>Thêm Thương hiệu mới</h4>
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

                    <form method="POST" action="/admin/brand/store">
                        <div class="mb-3">
                            <label class="form-label">Tên Thương hiệu:</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Thêm Thương hiệu</button>
                            <a href="/admin/brand" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
