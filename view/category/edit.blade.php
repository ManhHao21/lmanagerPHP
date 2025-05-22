@extends('layouts.layouts')

@section('title', 'Chỉnh sửa danh mục')

@section('contents')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center bg-warning text-white">
                    <h4>Chỉnh sửa danh mục</h4>
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

                    <form method="POST" action="/admin/category/update/{{ $category['id'] }}">
                        <div class="mb-3">
                            <label class="form-label">Tên danh mục:</label>
                            <input type="text" name="name" class="form-control" value="{{ $category['name'] }}" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Cập nhật</button>
                            <a href="/admin/category" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
