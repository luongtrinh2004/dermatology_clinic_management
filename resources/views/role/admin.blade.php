<!DOCTYPE html>
<html lang="en">
@section('title', 'Admin Dashboard')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <header>
        <div class="py-3" style="background-color: #e0f7fa; border-bottom: 1px solid #ccc;">
            <div class="container d-flex justify-content-between align-items-center flex-wrap gap-3">
                <!-- Logo -->
                <a href="{{ url('/admin/dashboard') }}" class="d-flex align-items-center">
                    <img src="/img/logo.webp" alt="Logo" style="height: 50px;">
                </a>

                <!-- Search -->
                <div class="d-flex align-items-center flex-grow-1 mx-3" style="max-width: 400px;">
                    <input type="text" class="form-control" placeholder="Tìm kiếm..." style="border-radius: 25px;">
                    <button class="btn btn-primary ms-2" style="border-radius: 25px;">
                        <i class="bi bi-search"></i>
                    </button>
                </div>

                <!-- Actions -->
                <a href="/appointments/create" class="btn btn-primary btn-sm rounded-pill px-3">Đặt lịch khám</a>
                <a href="#" class="btn btn-info btn-sm rounded-pill px-3 text-white">1900 886648</a>
                <a href="#" class="btn btn-warning btn-sm rounded-pill px-3 text-white">Hướng dẫn khách hàng</a>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}" class="d-inline-block">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3">Đăng xuất</button>
                </form>

                <!-- Language -->
                <div class="dropdown">
                    <button class="btn btn-light btn-sm rounded-circle dropdown-toggle" id="languageDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="/img/vietnam.png" alt="VN" style="height: 20px;">
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Vietnamese</a></li>
                        <li><a class="dropdown-item" href="#">English</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    {{-- Nội dung chính --}}
    <main class="content">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h1 class="fw-bold" style="color: #0056b3;">Welcome Admin!</h1>
            </div>

            <div class="row g-4">
                @php
                $cards = [
                ['label' => 'Quản lý bác sĩ', 'url' => '/admin/doctors'],
                ['label' => 'Quản lý lịch làm việc', 'url' => '/admin/workingschedule'],
                ['label' => 'Quản lý hồ sơ bệnh nhân', 'url' => '/admin/medicalrecords'],
                ['label' => 'Quản lý lịch hẹn khám', 'url' => '/admin/appointments'],
                ['label' => 'Quản lý lịch hẹn Spa', 'url' => '/spa/appointments'],
                ['label' => 'Quản lý hỗ trợ khách hàng', 'url' => '/admin/supports'],
                ['label' => 'Quản lý dịch vụ y tế', 'url' => '/admin/manageservices'],
                ['label' => 'Quản lý dịch vụ Spa', 'url' => '/admin/managespaservices'],
                ['label' => 'Quản lý kho thuốc', 'url' => '/admin/medicines'],
                ['label' => 'Quản lý hóa đơn', 'url' => '/admin/invoices'],
                ];
                @endphp

                @foreach ($cards as $card)
                <div class="col-md-3 col-sm-6">
                    <div class="card h-100">
                        <div class="card-body text-center d-flex flex-column justify-content-between">
                            <h5 class="card-title">{{ $card['label'] }}</h5>
                            <a href="{{ url($card['url']) }}" class="btn btn-primary mt-3">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="footer mt-5 pt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3 footer-col">
                    <a href="#" class="footer-logo mb-3">
                        <img src="{{ asset('img/phenikaamec.webp') }}" alt="PHENIKAA MEC">
                    </a>
                    <p><strong>Bệnh viện Đại Học Phenikaa</strong></p>
                    <p>📍 Kiều Mai, Phương Canh, Nam Từ Liêm, Hà Nội</p>
                    <p>📜 Giấy phép số 386/BYT</p>
                    <p>📞 <a href="tel:1900886648">1900.88.66.48</a> - <a href="tel:02422226688">02422226688</a></p>
                    <p>📧 <a href="mailto:support@phenikaamec.com">support@phenikaamec.com</a></p>
                </div>

                <div class="col-md-3 footer-col">
                    <h5 class="footer-title">HỆ THỐNG PHÒNG KHÁM</h5>
                    <p><strong>Đa Khoa Hoàng Ngân</strong><br>📍 167 Hoàng Ngân, Hà Nội<br>📞 02422226699</p>
                    <p><strong>Răng Hàm Mặt</strong><br>📍 167 Hoàng Ngân, Hà Nội<br>📞 0978625499</p>
                </div>

                <div class="col-md-3 footer-col">
                    <h5 class="footer-title">LIÊN KẾT NHANH</h5>
                    <ul class="list-unstyled ps-0">
                        <li><a href="#">Chương trình Bác sĩ hợp tác</a></li>
                        <li><a href="#">Chuyên khoa</a></li>
                        <li><a href="#">Dịch vụ</a></li>
                        <li><a href="#">Bệnh học</a></li>
                    </ul>
                </div>

                <div class="col-md-3 footer-col">
                    <h5 class="footer-title">TẢI APP PHENIKAA MEC</h5>
                    <div class="qr-box mb-2">
                        <a href="#"><img src="{{ asset('img/qr.png') }}" alt="QR Code"></a>
                    </div>
                    <div class="social-icons">
                        <a href="#"><img src="{{ asset('img/iconfb.webp') }}" alt="Facebook"></a>
                        <a href="#"><img src="{{ asset('img/iconyoutube.webp') }}" alt="YouTube"></a>
                        <a href="#"><img src="{{ asset('img/icontiktok.webp') }}" alt="TikTok"></a>
                    </div>
                </div>
            </div>

            <hr class="footer-divider">
            <div class="text-center py-3">
                <p class="mb-1">&copy; {{ date('Y') }} Bệnh viện Đại học Phenikaa</p>
                <p><a href="#">Điều khoản sử dụng</a> | <a href="#">Chính sách bảo mật</a></p>
            </div>
        </div>
    </footer>

    {{-- CSS Footer --}}
    <style>
    .footer {
        background-color: #b3e5fc;
        color: #003366;
        font-family: 'Poppins', sans-serif;
        padding: 50px 0 30px;
    }

    .footer-col {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .footer-logo img {
        max-width: 180px;
        display: block;
    }

    .footer-title {
        font-size: 16px;
        font-weight: 600;
        color: #0056b3;
        margin-bottom: 12px;
    }

    .footer a {
        color: #003366;
        font-size: 14px;
        text-decoration: none;
    }

    .footer a:hover {
        color: #0056b3;
        text-decoration: underline;
    }

    .footer p {
        font-size: 14px;
        margin-bottom: 6px;
    }

    .qr-box {
        background: white;
        padding: 10px;
        border: 2px solid #003366;
        border-radius: 5px;
    }

    .social-icons {
        display: flex;
        gap: 10px;
    }

    .social-icons img {
        width: 30px;
        height: 30px;
        transition: transform 0.2s;
    }

    .social-icons img:hover {
        transform: scale(1.1);
    }

    .footer-divider {
        margin: 30px 0 15px;
        border-top: 1px solid #0056b3;
    }

    @media (max-width: 768px) {
        .footer .row {
            text-align: center;
        }

        .footer-col {
            align-items: center;
        }

        .qr-box,
        .social-icons {
            justify-content: center;
        }
    }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>