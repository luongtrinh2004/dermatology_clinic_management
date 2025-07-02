@extends('layouts.app')

@section('title', 'Quản lý Thuốc (Admin)')

@section('content')
    <div class="container py-4">
        <h1 class="text-center mb-4">Quản lý Thuốc</h1>

        <!-- Tìm kiếm -->
        <form method="GET" action="{{ route('admin.medicines.index') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm thuốc..."
                    value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </form>

        <!-- Thông báo -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Form thêm/sửa thuốc -->
        @if(isset($editMedicine))
            <h3 class="mb-3">Sửa Thuốc</h3>
            <form method="POST" action="{{ route('admin.medicines.update', $editMedicine->id) }}" enctype="multipart/form-data"
                class="mb-4">
        @else
                <h3 class="mb-3">Thêm Thuốc</h3>
                <form method="POST" action="{{ route('admin.medicines.store') }}" enctype="multipart/form-data" class="mb-4">
            @endif
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="name" class="form-control" placeholder="Tên thuốc"
                            value="{{ old('name', $editMedicine->name ?? '') }}" required>
                    </div>
                    <div class="col-md-4">
                        <input type="number" step="0.01" name="price" class="form-control" placeholder="Giá"
                            value="{{ old('price', $editMedicine->price ?? '') }}" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="unit" class="form-control" placeholder="Đơn vị"
                            value="{{ old('unit', $editMedicine->unit ?? '') }}">
                    </div>
                    <div class="col-md-4">
                        <input type="number" name="quantity" class="form-control" placeholder="Số lượng"
                            value="{{ old('quantity', $editMedicine->quantity ?? '') }}" required>
                    </div>
                    <div class="col-md-4">
                        <input type="file" name="image" class="form-control">
                        @if(isset($editMedicine) && $editMedicine->image)
                            <img src="{{ asset($editMedicine->image) }}" class="img-thumbnail mt-2" style="max-height: 60px;">
                        @endif
                    </div>
                    <div class="col-md-12">
                        <textarea name="description" class="form-control"
                            placeholder="Mô tả">{{ old('description', $editMedicine->description ?? '') }}</textarea>
                    </div>
                    <div class="col-md-12">
                        <textarea name="contraindications" class="form-control"
                            placeholder="Chống chỉ định">{{ old('contraindications', $editMedicine->contraindications ?? '') }}</textarea>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn {{ isset($editMedicine) ? 'btn-warning' : 'btn-success' }}">
                            {{ isset($editMedicine) ? 'Lưu thay đổi' : 'Thêm Thuốc' }}
                        </button>
                    </div>
                </div>
            </form>

            <!-- Danh sách thuốc -->
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Ảnh</th>
                            <th>Tên</th>
                            <th>Giá</th>
                            <th>Đơn vị</th>
                            <th>Số lượng</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicines as $medicine)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($medicine->image)
                                        <img src="{{ asset($medicine->image) }}" class="img-thumbnail" style="max-height: 50px;">
                                    @else
                                        <span>Không có ảnh</span>
                                    @endif
                                </td>
                                <td>{{ $medicine->name }}</td>
                                <td>{{ number_format($medicine->price, 0, ',', '.') }} đ</td>
                                <td>{{ $medicine->unit }}</td>
                                <td>{{ $medicine->quantity }}</td>
                                <td class="text-nowrap">
                                    <a href="{{ route('admin.medicines.index', ['edit_id' => $medicine->id]) }}"
                                        class="btn btn-warning btn-sm">Sửa</a>
                                    <form method="POST" action="{{ route('admin.medicines.destroy', $medicine->id) }}"
                                        class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">Không có thuốc nào</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
    </div>
@endsection