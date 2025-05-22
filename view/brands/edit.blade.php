@extends('layouts.layouts')

@section('title', 'Chỉnh sửa thương hiệu')

@section('contents')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center bg-warning text-white">
                        <h4>Chỉnh sửa thương hiệu</h4>
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

                        <form method="POST" action="/admin/brand/update/{{ $brand['id'] }}">
                            <div class="mb-3">
                                <label class="form-label">Tên thương hiệu:</label>
                                <input type="text" name="name" class="form-control" value="{{ $brand['name'] }}"
                                    required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success">Cập nhật</button>
                                <a href="/admin/brand" class="btn btn-secondary">Quay lại</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
