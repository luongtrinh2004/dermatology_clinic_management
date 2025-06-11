@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <h2 class="mb-3 text-primary">{{ $service->name }}</h2>

        <p class="mb-4">{{ $service->description }}</p>

        <a href="{{ route('spa.appointment', $service->id) }}" class="btn btn-success">
            <i class="fas fa-calendar-check"></i> Đặt lịch ngay
        </a>
    </div>
@endsection