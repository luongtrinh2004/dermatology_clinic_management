@extends('layouts.app')

@section('title', 'Đặt lại mật khẩu')

@section('content')
<div class="container py-5">
    <h2 class="text-center">Đặt lại mật khẩu</h2>
    <form method="POST" action="{{ route('password.direct.update') }}">
        @csrf

        <!-- Token không cần thiết ở đây, để trống hoặc ẩn -->
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <label for="email" class="form-label">Địa chỉ email</label>
            <input id="email" type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   name="email" value="{{ $email ?? old('email') }}" required autofocus>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu mới</label>
            <input id="password" type="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   name="password" required>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password-confirm" class="form-label">Xác nhận mật khẩu mới</label>
            <input id="password-confirm" type="password" 
                   class="form-control" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-primary">
            Đặt lại mật khẩu
        </button>
    </form>
</div>
@endsection
