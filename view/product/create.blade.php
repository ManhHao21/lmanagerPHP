@extends('layouts.layouts')

@section('contents')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center bg-primary text-white">
                    <h4>Thêm sản phẩm mới</h4>
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

                    <form method="POST" action="/admin/product/store" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Tên sản phẩm:</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Giá:</label>
                            <input type="number" name="price" class="form-control" required min="1">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Danh mục:</label>
                            <select name="category_id" class="form-control" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả:</label>
                            <textarea name="description" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hình ảnh:</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Thêm sản phẩm</button>
                            <a href="/admin/product" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection