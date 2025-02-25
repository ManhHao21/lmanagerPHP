@extends('layouts.layouts')

@section('title', 'Chỉnh sửa sản phẩm')

@section('contents')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center bg-warning text-white">
                    <h4>Chỉnh sửa sản phẩm</h4>
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

                    <form method="POST" action="/ASM/product/update/{{ $product['id'] }}" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Tên sản phẩm:</label>
                            <input type="text" name="name" class="form-control" value="{{ $product['name'] }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Danh mục:</label>
                            <select name="category_id" class="form-control" required>
                                @foreach ($categories as $category)
                                <option value="{{ $category['id'] }}" {{ $product['category_id'] == $category['id'] ? 'selected' : '' }}>
                                    {{ $category['name'] }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Giá:</label>
                            <input type="number" name="price" class="form-control" value="{{ $product['price'] }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả:</label>
                            <textarea name="description" class="form-control" required>{{ $product['description'] }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hình ảnh hiện tại:</label><br>
                            <img src="/uploads/{{ urlencode($product['image']) }}" alt="Hình ảnh sản phẩm"
                                class="img-thumbnail" width="80" height="80">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Chọn ảnh mới (nếu có):</label>
                            <input type="file" name="image" class="form-control">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Cập nhật</button>
                            <a href="/ASM/product" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection