@extends('layouts.app')

@section('title', 'Quản lý Lịch Hẹn Spa')

@section('content')
<div class="container py-4">
    <h1 class="text-center mb-4" style="font-family: 'Poppins', sans-serif;">Quản lý Lịch Hẹn Spa</h1>

    {{-- Hiển thị thông báo --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Thành công!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Lỗi!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Tìm kiếm --}}
    <form method="GET" action="{{ route('spa.appointments.index') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm lịch hẹn..."
                value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
        </div>
    </form>

    {{-- Form thêm/sửa --}}
    @if(isset($editAppointment))
    <h3 class="mb-3">Sửa Lịch Hẹn</h3>
    <form method="POST" action="{{ route('spa.appointments.update', $editAppointment->id) }}">
        @csrf
        @method('PUT')
        @else
        <h3 class="mb-3">Thêm Lịch Hẹn</h3>
        <form method="POST" action="{{ route('spa.appointments.store') }}">
            @csrf
            @endif

            <div class="row">
                <div class="col-md-4 mb-2">
                    <label for="name" class="form-label">Tên Khách Hàng</label>
                    <input type="text" name="name" class="form-control" value="{{ $editAppointment->name ?? '' }}"
                        required>
                </div>

                <div class="col-md-4 mb-2">
                    <label for="phone" class="form-label">Số Điện Thoại</label>
                    <input type="text" name="phone" class="form-control" value="{{ $editAppointment->phone ?? '' }}"
                        required>
                </div>

                <div class="col-md-4 mb-2">
                    <label for="service_id" class="form-label">Dịch Vụ</label>
                    <select name="service_id" class="form-control" required>
                        <option value="">-- Chọn Dịch Vụ --</option>
                        @foreach($services as $service)
                        <option value="{{ $service->id }}"
                            {{ isset($editAppointment) && $editAppointment->service_id == $service->id ? 'selected' : '' }}>
                            {{ $service->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-2">
                    <label for="date" class="form-label">Ngày Hẹn</label>
                    <input type="date" name="date" class="form-control" value="{{ $editAppointment->date ?? '' }}"
                        required>
                </div>

                <div class="col-md-4 mb-2">
                    <label for="time" class="form-label">Giờ Hẹn</label>
                    <input type="time" name="time" class="form-control" value="{{ $editAppointment->time ?? '' }}"
                        required>
                </div>

                <div class="col-md-4 mb-2">
                    <label for="note" class="form-label">Ghi Chú</label>
                    <input type="text" name="note" class="form-control" value="{{ $editAppointment->note ?? '' }}">
                </div>

                <div class="col-md-4 mb-2">
                    <button type="submit"
                        class="btn {{ isset($editAppointment) ? 'btn-warning' : 'btn-success' }} w-100 mt-4">
                        {{ isset($editAppointment) ? 'Lưu Thay Đổi' : 'Thêm Lịch Hẹn' }}
                    </button>
                </div>
            </div>
        </form>

        {{-- Danh sách lịch hẹn --}}
        <div class="table-responsive mt-4">
            <table class="table table-bordered table-hover">
                <thead class="table-success text-center">
                    <tr>
                        <th>#</th>
                        <th>Khách hàng</th>
                        <th>SĐT</th>
                        <th>Dịch vụ</th>
                        <th>Ngày</th>
                        <th>Giờ</th>
                        <th>Ghi chú</th>
                        <th>Thời gian tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $index => $appointment)
                    <tr>
                        <td class="text-center">{{ $appointments->firstItem() + $index }}</td>
                        <td>{{ $appointment->name }}</td>
                        <td>{{ $appointment->phone }}</td>
                        <td>{{ $appointment->service->name ?? 'Không xác định' }}</td>
                        <td class="text-center">{{ $appointment->date }}</td>
                        <td class="text-center">{{ $appointment->time }}</td>
                        <td>{{ $appointment->note }}</td>
                        <td class="text-center">{{ $appointment->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-center">
                            <a href="{{ route('spa.appointments.index', ['edit_id' => $appointment->id]) }}"
                                class="btn btn-warning btn-sm">Sửa</a>

                            <form method="POST" action="{{ route('spa.appointments.destroy', $appointment->id) }}"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa lịch hẹn này?')">
                                    Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Chưa có lịch hẹn nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Phân trang --}}
            <div class="d-flex justify-content-center">
                {{ $appointments->links() }}
            </div>
        </div>
</div>
@endsection