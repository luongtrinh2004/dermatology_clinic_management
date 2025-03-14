@extends('layouts.app')

@section('title', 'Quên mật khẩu')

@section('content')
<div class="container py-5">
    <h2 class="text-center">Quên mật khẩu</h2>

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Địa chỉ email</label>
            <input id="email" type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   name="email" required autofocus>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            Gửi liên kết đặt lại mật khẩu
        </button>
    </form>
</div>
@endsection
