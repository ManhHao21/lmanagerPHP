@extends('layouts.layouts')

@section('title', 'Chỉnh sửa nhà cung cấp')

@section('contents')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center bg-warning text-white">
                        <h4>Chỉnh sửa nhà cung cấp</h4>
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

                        <form method="POST" action="/admin/supplier/update/{{ $supplier['id'] }}">
                            <div class="mb-3">
                                <label class="form-label">Tên nhà cung cấp:</label>
                                <input type="text" name="name" class="form-control" value="{{$supplier['Name']}}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email:</label>
                                <input type="text" name="email" class="form-control" value="{{$supplier['Email']}}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone:</label>
                                <input type="text" name="phone" class="form-control" value="{{$supplier['Phone']}}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address:</label>
                                <input type="text" name="address" class="form-control" value="{{$supplier['Address']}}" required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success">Thêm nhà cung cấp</button>
                                <a href="/admin/supplier" class="btn btn-secondary">Quay lại</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
