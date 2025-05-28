@extends('layouts.app')

@section('title', 'Danh sách thuốc')

@section('content')
    <div class="container py-4">
        <h1 class="text-center mb-4">Danh sách thuốc</h1>

        <!-- Tìm kiếm -->
        <form method="GET" action="{{ route('medicines.index') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm thuốc..."
                    value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </form>

        <!-- Danh sách -->
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Không có thuốc nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection