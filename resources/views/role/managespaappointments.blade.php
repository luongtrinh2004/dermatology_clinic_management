@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center text-success mb-4">Quản Lý Lịch Hẹn Spa</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($appointments->count())
            <table class="table table-bordered table-striped">
                <thead class="table-success text-center">
                    <tr>
                        <th>#</th>
                        <th>Khách hàng</th>
                        <th>Số điện thoại</th>
                        <th>Dịch vụ</th>
                        <th>Ngày</th>
                        <th>Giờ</th>
                        <th>Ghi chú</th>
                        <th>Thời gian tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $index => $appointment)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $appointment->name }}</td>
                            <td>{{ $appointment->phone }}</td>
                            <td>{{ $appointment->service->name ?? 'Không xác định' }}</td>
                            <td class="text-center">{{ $appointment->date }}</td>
                            <td class="text-center">{{ $appointment->time }}</td>
                            <td>{{ $appointment->note }}</td>
                            <td class="text-center">{{ $appointment->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                {{-- Optional: Thêm chức năng sửa, xóa --}}
                                {{--
                                <a href="{{ route('spa.appointments.edit', $appointment->id) }}" class="btn btn-sm
                                btn-warning">Sửa</a>
                                <form action="{{ route('spa.appointments.destroy', $appointment->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa lịch hẹn này?')">Xóa</button>
                                </form>
                                --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-info text-center">Chưa có lịch hẹn spa nào.</div>
        @endif

        <div class="text-center mt-4">
            <a href="{{ route('spa.appointments.create') }}" class="btn btn-primary">Tạo lịch hẹn mới</a>
        </div>
    </div>
@endsection