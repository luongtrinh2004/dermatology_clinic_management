@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center text-primary mb-4">Đặt Lịch Hẹn Spa</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('spa.appointments.store') }}" method="POST">
            @csrf

            <div class="form-group mb-3">
                <label for="name">Họ và tên:</label>
                <input type="text" name="name" class="form-control" required placeholder="Nhập tên của bạn">
            </div>

            <div class="form-group mb-3">
                <label for="phone">Số điện thoại:</label>
                <input type="text" name="phone" class="form-control" required placeholder="0123xxx...">
            </div>

            <div class="form-group mb-3">
                <label for="service_id">Dịch vụ spa:</label>
                <select name="service_id" class="form-control" required>
                    <option value="">-- Chọn dịch vụ --</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ (isset($service_id) && $service_id == $service->id) ? 'selected' : '' }}>
                            {{ $service->name }}
                        </option>
                    @endforeach
                </select>

            </div>

            <div class="form-group mb-3">
                <label for="date">Ngày:</label>
                <input type="date" name="date" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="time">Giờ:</label>
                <input type="time" name="time" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="note">Ghi chú (tùy chọn):</label>
                <textarea name="note" class="form-control" rows="3"></textarea>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success">Xác nhận đặt lịch</button>
                <a href="{{ route('spa.appointments.index') }}" class="btn btn-secondary">Quản lý lịch hẹn</a>
            </div>
        </form>
    </div>
@endsection