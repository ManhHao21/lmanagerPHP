@extends('layouts.layouts')

@section('contents')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
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
                            <div class="row">
                                <!-- Cột trái -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tên sản phẩm:</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Giá:</label>
                                        <input type="number" name="price" class="form-control" required min="1"
                                            step="0.01">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Giá khuyến mãi:</label>
                                        <input type="number" name="PromotionPrice" class="form-control" min="0"
                                            step="0.01" placeholder="Có thể để trống nếu không có">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">VAT (%):</label>
                                        <input type="number" name="Vat" class="form-control" min="0"
                                            max="100" step="1" placeholder="Phần trăm VAT">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Số lượng:</label>
                                        <input type="number" name="Quantity" class="form-control" min="0"
                                            step="1" placeholder="Số lượng sản phẩm">
                                    </div>

                                    <div class="mb-3 form-check">
                                        <input type="checkbox" name="Hot" value="1" class="form-check-input"
                                            id="hotCheck">
                                        <label class="form-check-label" for="hotCheck">Sản phẩm hot</label>
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
                                        <label class="form-label">Mô tả ngắn:</label>
                                        <textarea name="description" class="form-control" rows="3" required></textarea>
                                    </div>
                                </div>

                                <!-- Cột phải -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Chi tiết:</label>
                                        <textarea name="Detail" class="form-control" rows="5"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Slug (URL thân thiện):</label>
                                        <input type="text" name="slug" class="form-control"
                                            placeholder="vd: ten-san-pham">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Trạng thái:</label>
                                        <select name="status" class="form-control">
                                            <option value="">Chọn trạng thái</option>
                                            <option value="0">Ẩn</option>
                                            <option value="1">Hiện</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Lượt xem:</label>
                                        <input type="number" name="ViewCount" class="form-control" min="0"
                                            step="1" value="0">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Thương hiệu:</label>
                                        <select name="BrandID" class="form-control" required>
                                            <option value="">-- Chọn thương hiệu --</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand['id'] }}"
                                                    {{ isset($product) && $product['BrandID'] == $brand['id'] ? 'selected' : '' }}>
                                                    {{ $brand['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="mb-3">
                                        <label class="form-label">Nhà cung cấp:</label>
                                        <select name="SupplierID" class="form-control" required>
                                            <option value="">-- Chọn nhà cung cấp --</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier['id'] }}"
                                                    {{ isset($product) && $product['SupplierID'] == $supplier['id'] ? 'selected' : '' }}>
                                                    {{ $supplier['Name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="mb-3">
                                        <label class="form-label">Hình ảnh:</label>
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-3">
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


<script>
    // Hàm chuyển chuỗi thành slug
    function str_slug(str) {
        return str
            .toLowerCase()
            .trim()
            .normalize('NFD') // tách dấu
            .replace(/[\u0300-\u036f]/g, '') // bỏ dấu
            .replace(/[^a-z0-9 -]/g, '') // bỏ ký tự đặc biệt
            .replace(/\s+/g, '-') // thay khoảng trắng thành dấu -
            .replace(/-+/g, '-'); // bỏ dấu - liên tiếp
    }

    // Lắng nghe sự kiện input trên ô name
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.querySelector('input[name="name"]');
        const slugInput = document.querySelector('input[name="slug"]');

        if (nameInput && slugInput) {
            nameInput.addEventListener('input', function() {
                slugInput.value = str_slug(nameInput.value);
            });
        }
    });
</script>
