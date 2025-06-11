@extends('layouts.app')

@section('content')
    {{-- FontAwesome + OwlCarousel --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <style>
        .slider-container {
            position: relative;
            margin-bottom: 40px;
        }

        .owl-carousel .item img {
            width: 100%;
            height: 100vh;
            object-fit: cover;
        }

        .owl-nav {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
            padding: 0 20px;
        }

        .owl-nav button {
            background: rgba(0, 0, 0, 0.5) !important;
            color: #fff !important;
            border: none;
            padding: 10px 20px !important;
            border-radius: 50%;
            font-size: 20px !important;
        }

        .intro-section {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 60px 20px;
            flex-wrap: wrap;
            gap: 40px;
            background-color: #fff;
        }

        .intro-section img {
            max-width: 500px;
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .intro-text {
            max-width: 600px;
        }

        .intro-text h2 {
            font-size: 28px;
            color: #4a9c9f;
            margin-bottom: 16px;
            font-family: 'Georgia', serif;
        }

        .intro-text p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 12px;
        }

        .spa-title {
            text-align: center;
            font-size: 36px;
            color: #4a9c9f;
            font-family: 'Georgia', serif;
            margin-bottom: 40px;
        }

        .spa-box {
            max-width: 1100px;
            margin: auto;
            padding: 20px;
        }

        .category-row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 60px;
            gap: 30px;
        }

        .category-img,
        .category-content {
            width: 48%;
            box-sizing: border-box;
        }

        .category-img img {
            width: 100%;
            height: 450px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .category-content {
            height: 450px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .category-content::-webkit-scrollbar {
            width: 6px;
        }

        .category-content::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 4px;
        }

        .category-content h4 {
            font-size: 22px;
            font-weight: bold;
            color: #4a9c9f;
            font-family: 'Georgia', serif;
            margin-bottom: 16px;
        }

        .service-title {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 15px;
            color: #247b83;
            background: #e7f7f8;
            padding: 12px 15px;
            border: 1px solid #b7dbdd;
            border-radius: 6px;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .service-title.active::after {
            content: "−";
        }

        .service-body {
            background: #f7f7f7;
            padding: 15px 20px;
            border-radius: 0 0 6px 6px;
            margin-bottom: 15px;
            display: none;
        }

        .service-buttons {
            margin-top: 10px;
        }

        .service-buttons a {
            display: inline-block;
            background-color: #4a9c9f;
            color: #fff;
            padding: 10px 22px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-right: 10px;
        }

        .service-buttons a:hover {
            background-color: #3b7f82;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .service-buttons a i {
            margin-right: 6px;
        }

        .service-buttons .btn-outline-secondary {
            background-color: transparent;
            color: #4a9c9f;
            border: 1px solid #4a9c9f;
        }

        .service-buttons .btn-outline-secondary:hover {
            background-color: #4a9c9f;
            color: white;
        }

        .floating-buttons {
            position: fixed;
            top: 35%;
            right: 20px;
            display: flex;
            flex-direction: column;
            z-index: 999;
            gap: 12px;
        }

        .floating-buttons a {
            width: 50px;
            height: 50px;
            background-color: #7bd4d2;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 22px;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .floating-buttons img {
            width: 24px;
            height: 24px;
        }

        @media (max-width: 768px) {
            .category-row {
                flex-direction: column;
            }

            .category-img,
            .category-content {
                width: 100%;
            }

            .intro-section {
                flex-direction: column;
            }
        }
    </style>

    {{-- Slider --}}
    <div class="slider-container">
        <div id="home-slider" class="owl-carousel owl-theme">
            <div class="item"><img src="{{ asset('img/slide1.png') }}" alt="Slide 1"></div>
            <div class="item"><img src="{{ asset('img/slide2.png') }}" alt="Slide 2"></div>
            <div class="item"><img src="{{ asset('img/slide3.png') }}" alt="Slide 3"></div>
        </div>
    </div>

    {{-- Giới thiệu --}}
    <div class="intro-section">
        <img src="{{ asset('img/lamspa.png') }}" alt="Lam Spa" />
        <div class="intro-text">
            <h2>Câu Chuyện Nhà Lam</h2>
            <p>Thành lập năm 2019, Lam Spa khởi đầu câu chuyện của mình nằm ở một góc yên tĩnh, dưới những tán cây xanh
                trong khu vực nhộn nhịp của Phú Nhuận...</p>
            <p>Chúng tôi luôn hướng đến sự kết nối giữa ngoại tại và nội tại của mỗi bản thân, và sự kết nối giữa con người
                với con người.</p>
        </div>
    </div>

    {{-- Dịch vụ --}}
    <div class="spa-box">
        <h2 class="spa-title">DỊCH VỤ SPA</h2>
        @foreach($spaCategories as $index => $category)
            <div class="category-row {{ $index % 2 == 1 ? 'flex-row-reverse' : '' }}">
                <div class="category-img">
                    <img src="{{ asset('img/' . $category->image) }}" alt="{{ $category->title }}">
                </div>
                <div class="category-content">
                    <h4>{{ $category->title }}</h4>
                    @forelse($category->services as $service)
                        <div class="service-block">
                            <div class="service-title" onclick="toggleService(this)">
                                {{ $service->name }}
                            </div>
                            <div class="service-body">
                                <p>{{ $service->description }}</p>
                                <div class="service-buttons">
                                    <a href="{{ route('spa.appointment', $service->id) }}">
                                        <i class="fas fa-calendar-check"></i> Đặt lịch
                                    </a>
                                    <a href="{{ route('spa.service.show', $service->id) }}"
                                        class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-info-circle"></i> Chi tiết
                                    </a>

                                </div>
                            </div>
                        </div>
                    @empty
                        <p>Không có dịch vụ nào.</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>

    {{-- Nút nổi --}}
    <div class="floating-buttons">
        <a href="https://zalo.me/0816260406" target="_blank"><img src="{{ asset('img/zalo-icon.png') }}" alt="Zalo"></a>
        <a href="tel:#"><i class="fas fa-phone"></i></a>
        <a href="https://www.facebook.com/luong.trinh.283901" target="_blank"><i class="fab fa-facebook-f"></i></a>
    </div>

    <script>
        function toggleService(el) {
            const body = el.nextElementSibling;
            const isOpen = body.style.display === 'block';

            document.querySelectorAll('.service-body').forEach(b => b.style.display = 'none');
            document.querySelectorAll('.service-title').forEach(t => t.classList.remove('active'));

            if (!isOpen) {
                body.style.display = 'block';
                el.classList.add('active');
            }
        }

        $(document).ready(function () {
            $("#home-slider").owlCarousel({
                items: 1,
                loop: true,
                nav: true,
                dots: true,
                autoplay: true,
                autoplayTimeout: 5000,
                autoplayHoverPause: true,
                navText: ["<", ">"]
            });
        });
    </script>
@endsection