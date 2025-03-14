<!DOCTYPE html>
<html lang="en">


@section('title', 'Quản lý Hóa Đơn')

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
                <a href="{{ url('/admindoctor/dashboard') }}" class="d-flex align-items-center">
                    <img src="/img/logo.webp" alt="Logo" style="height: 50px;">
                </a>

                <!-- Search -->
                <div class="d-flex align-items-center" style="max-width: 400px; width: 100%;">
                    <input type="text" class="form-control" placeholder="Tìm kiếm..." style="border-radius: 25px;">
                    <button class="btn btn-primary ms-2" style="border-radius: 25px;">
                        <i class="bi bi-search"></i>
                    </button>
                </div>

                <!-- Actions -->
                <a href="/appointments/create" class="btn btn-primary btn-sm rounded-pill px-3"
                    style="background-color: #007bff; border-color: #007bff;">Đặt lịch khám</a>
                <a href="#" class="btn btn-info btn-sm rounded-pill px-3" style="color: white;">1900 886648</a>
                <a href="#" class="btn btn-warning btn-sm rounded-pill px-3" style="color: white;">Hướng dẫn khách
                    hàng</a>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}" class="d-inline-block">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3">Đăng xuất</button>
                </form>

                <!-- Language Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-light btn-sm rounded-circle dropdown-toggle" id="languageDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="/img/vietnam.png" alt="VN" style="height: 20px;"> <!-- Icon cờ -->
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                        <li><a class="dropdown-item" href="#">Vietnamese</a></li>
                        <li><a class="dropdown-item" href="#">English</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <div class="container py-4">
        <h1 class="text-center mb-4">Quản lý Hóa Đơn</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Form tạo hóa đơn -->
        <h3 class="mb-3">Tạo Hóa Đơn</h3>
        <form method="POST" action="{{ route('admindoctor.invoices.store') }}">
            @csrf
            @if(isset($medicalRecord))
                <input type="hidden" name="medical_record_id" value="{{ $medicalRecord->id }}">

                <div class="mb-3">
                    <label class="form-label">ID Hồ Sơ</label>
                    <input type="text" class="form-control" value="{{ $medicalRecord->id }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tên Bệnh Nhân</label>
                    <input type="text" class="form-control" value="{{ $medicalRecord->name }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ngày Khám</label>
                    <input type="date" class="form-control" value="{{ $medicalRecord->exam_date }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Số Điện Thoại</label>
                    <input type="text" class="form-control" value="{{ $medicalRecord->phone }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Chi Phí</label>
                    <input type="text" class="form-control" value="{{ number_format($medicalRecord->cost, 0) }} VNĐ"
                        readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ngày Làm Hồ Sơ</label>
                    <input type="date" class="form-control" value="{{ $medicalRecord->created_at->format('Y-m-d') }}"
                        readonly>
                </div>
            @endif

            <div class="mb-3">
                <label for="invoice_date" class="form-label">Ngày Lập Hóa Đơn</label>
                <input type="date" name="invoice_date" id="invoice_date" class="form-control" required
                    value="{{ now()->format('Y-m-d') }}">
            </div>
            <div class="mb-3">
                <label for="total_amount" class="form-label">Tổng Số Tiền</label>
                <input type="number" name="total_amount" id="total_amount" class="form-control" step="0.01" required
                    value="{{ old('total_amount', $medicalRecord->cost ?? '') }}">
            </div>
            <div class="mb-3">
                <label for="services_medicines" class="form-label">Dịch Vụ + Thuốc</label>
                <textarea name="services_medicines" id="services_medicines" class="form-control"
                    rows="3">{{ old('services_medicines', $servicesMedicines ?? '') }}</textarea>
            </div>



            <div class="mb-3">
                <label for="status" class="form-label">Trạng Thái Thanh Toán</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="Chưa thanh toán">Chưa thanh toán</option>
                    <option value="Đã thanh toán">Đã thanh toán</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success w-100">Tạo Hóa Đơn</button>
        </form>

        <!-- Danh sách hóa đơn -->
        <h3 class="mt-5">Danh Sách Hóa Đơn</h3>
        @if(isset($invoices) && $invoices->isEmpty())
            <div class="alert alert-info">Chưa có hóa đơn nào.</div>
        @elseif(isset($invoices))
            <div class="table-responsive">
                <table class="table table-bordered mt-3">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Ngày Lập Hóa Đơn</th>
                            <th>Tên Bệnh Nhân</th>
                            <th>Ngày Khám</th>
                            <th>Số Điện Thoại</th>
                            <th>Chi Phí</th>
                            <th>Ngày Làm Hồ Sơ</th>
                            <th>Tổng Số Tiền</th>
                            <th>Dịch Vụ + Thuốc</th>
                            <th>Trạng Thái</th>
                            <th>ID Hồ Sơ</th>
                            <th>Hành Động</th> <!-- Cột mới -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $invoice->invoice_date }}</td>
                                <td>{{ $invoice->patient_name }}</td>
                                <td>{{ $invoice->exam_date }}</td>
                                <td>{{ $invoice->phone }}</td>
                                <td>{{ number_format($invoice->cost, 0) }} VNĐ</td>
                                <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                                <td>{{ number_format($invoice->total_amount, 0) }} VNĐ</td>
                                <td>{{ $invoice->services_medicines }}</td>
                                <td>{{ $invoice->status }}</td>
                                <td>{{ $invoice->medical_record_id }}</td>
                                <td>

                                    <!-- Nút In Hóa Đơn -->
                                    <a href="{{ route('admindoctor.invoices.print', $invoice->id) }}"
                                        class="btn btn-secondary btn-sm" target="_blank">
                                        <i class="bi bi-printer"></i> In
                                    </a>
                                    <!-- Nút Xóa -->
                                    <form action="{{ route('admindoctor.invoices.destroy', $invoice->id) }}" method="POST"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa hóa đơn này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
            {{ $invoices->links() }}
        @endif

    </div>


    <footer class="footer">
        <div class="container">
            <div class="row">
                <!-- Cột 1: Thông tin bệnh viện -->
                <div class="col-md-3 footer-col">
                    <a href="#" class="footer-logo">
                        <img src="{{ asset('img/phenikaamec.webp') }}" alt="PHENIKAA MEC">
                    </a>

                    <p><strong>Bệnh viện Đại Học Phenikaa</strong></p>
                    <p>📍 Đường Kiều Mai, P. Phương Canh, Nam Từ Liêm, Hà Nội</p>
                    <p>📜 Giấy phép hoạt động số 386/BYT</p>
                    <p>📞 Hotline: <a href="tel:1900886648">1900.88.66.48</a> - <a
                            href="tel:02422226688">02422226688</a></p>
                    <p>📧 Email: <a href="mailto:support@phenikaamec.com">support@phenikaamec.com</a></p>
                </div>

                <!-- Cột 2: Hệ thống phòng khám -->
                <div class="col-md-3 footer-col">
                    <h5 class="footer-title">HỆ THỐNG PHÒNG KHÁM</h5>
                    <p><strong>Phòng Khám Đa Khoa - Hoàng Ngân</strong></p>
                    <p>📍 Số 167 Hoàng Ngân, Hà Nội</p>
                    <p>📞 <a href="tel:02422226699">02422226699</a></p>
                    <p>⏰ Giờ làm việc: 7h30 - 17h00</p>

                    <p><strong>Phòng Khám Răng Hàm Mặt</strong></p>
                    <p>📍 Số 167 Hoàng Ngân, Hà Nội</p>
                    <p>📞 <a href="tel:0978625499">0978625499</a></p>
                    <p>⏰ Giờ làm việc: 8h00 - 18h00</p>
                </div>

                <!-- Cột 3: Liên kết nhanh -->
                <div class="col-md-3 footer-col">
                    <h5 class="footer-title">LIÊN KẾT NHANH</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Chương trình Bác sĩ hợp tác</a></li>
                        <li><a href="#">Chuyên khoa</a></li>
                        <li><a href="#">Dịch vụ</a></li>
                        <li><a href="#">Bệnh học</a></li>
                    </ul>
                </div>

                <!-- Cột 4: Ứng dụng & Mạng xã hội -->
                <div class="col-md-3 footer-col">
                    <h5 class="footer-title">TẢI APP PHENIKAA MEC</h5>
                    <div class="qr-box">
                        <a href="#"><img src="{{ asset('img/qr.png') }}" alt="Facebook"></a>
                    </div>

                    <div class="social-icons">
                        <a href="#"><img src="{{ asset('img/iconfb.webp') }}" alt="Facebook"></a>
                        <a href="#"><img src="{{ asset('img/iconyoutube.webp') }}" alt="YouTube"></a>
                        <a href="#"><img src="{{ asset('img/icontiktok.webp') }}" alt="TikTok"></a>
                    </div>
                </div>
            </div>

            <hr class="footer-divider">

            <div class="text-center">
                <p>&copy; {{ date('Y') }} thuộc về Bệnh viện Đại học Phenikaa</p>
                <p><a href="#">Điều khoản sử dụng</a> | <a href="#">Chính sách bảo mật</a></p>
            </div>
        </div>
    </footer>

    <style>
        /* Font chữ từ Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

        /* Footer Styles */
        .footer {
            background-color: #b3e5fc;
            color: #003366;
            font-family: 'Poppins', sans-serif;
            padding: 40px 10%;
        }


        .alert {
            text-align: center;
            width: 100%;
            margin: 20px auto;
            /* Căn giữa theo chiều ngang */
            padding: 15px;
            font-size: 18px;
        }


        .footer-col {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .footer-logo img {
            max-width: 180px;
            /* Giới hạn kích thước logo */
            display: block;
            margin-bottom: 10px;
            /* Tạo khoảng cách với nội dung */
        }

        .footer-title {
            font-size: 16px;
            font-weight: 600;
            color: #0056b3;
            margin-bottom: 12px;
        }

        .footer a {
            color: #003366;
            text-decoration: none;
            font-size: 14px;
            font-weight: 400;
        }

        .footer a:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        .footer p {
            font-size: 14px;
            font-weight: 400;
        }

        .footer .list-unstyled li {
            margin-bottom: 6px;
        }

        .qr-box {
            background: white;
            padding: 10px;
            text-align: center;
            font-weight: 500;
            border: 2px solid #003366;
            border-radius: 5px;
        }

        /* Mạng xã hội */
        .social-icons {
            display: flex;
            gap: 10px;
            margin-top: 12px;
        }

        .social-icons img {
            width: 30px;
            height: 30px;
            transition: transform 0.2s ease-in-out;
        }

        .social-icons img:hover {
            transform: scale(1.1);
        }

        .footer-divider {
            margin: 20px 0;
            border-top: 1px solid #0056b3;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .footer .row {
                text-align: center;
            }

            .footer-col {
                align-items: center;
            }


        }
    </style>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>