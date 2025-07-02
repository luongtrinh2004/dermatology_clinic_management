<!DOCTYPE html>
<html lang="en">

@section('title', 'Quản lý Hóa Đơn - Admin')

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

        <!-- Phần Thống Kê -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-info text-white text-center">
                    <div class="card-body">
                        <h5 class="card-title">Số Hóa Đơn</h5>
                        <h2>{{ $totalInvoices }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary text-white text-center">
                    <div class="card-body">
                        <h5 class="card-title">Số Hồ Sơ Bệnh Án</h5>
                        <h2>{{ $totalMedicalRecords }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white text-center">
                    <div class="card-body">
                        <h5 class="card-title">Số Bác Sĩ Hiện Có</h5>
                        <h2>{{ $totalDoctors }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white text-center">
                    <div class="card-body">
                        <h5 class="card-title">Tổng Doanh Thu</h5>
                        <h2>{{ number_format($totalRevenue, 0) }} VNĐ</h2>
                    </div>
                </div>
            </div>
        </div>


        <!-- Form tìm kiếm hóa đơn -->
        <form method="GET" action="{{ route('admin.invoices.index') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control"
                    placeholder="Nhập tên bệnh nhân, số điện thoại, ID hồ sơ..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </form>



        <!-- Form Tạo Hóa Đơn -->
        <h3 class="mb-3">Thêm Hóa Đơn</h3>
        <form method="POST" action="{{ route('admin.invoices.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <label for="medical_record_id">ID Hồ Sơ Bệnh Án</label>
                    <select name="medical_record_id" id="medical_record_id" class="form-control" required>
                        <option value="">-- Chọn Hồ Sơ Bệnh Án --</option>
                        @foreach($medicalRecords as $record)
                        <option value="{{ $record->id }}" data-name="{{ $record->name }}"
                            data-exam_date="{{ $record->exam_date }}" data-phone="{{ $record->phone }}"
                            data-service="{{ $record->service }}" data-prescription="{{ $record->prescription }}">
                            ID: {{ $record->id }} - {{ $record->name }} (Ngày khám: {{ $record->exam_date }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="invoice_date">Ngày Lập Hóa Đơn</label>
                    <input type="date" name="invoice_date" id="invoice_date" class="form-control"
                        value="{{ now()->format('Y-m-d') }}" required>
                </div>

                <div class="col-md-6">
                    <label for="total_amount">Tổng Số Tiền</label>
                    <div class="input-group">
                        <input type="number" step="any" name="total_amount" id="total_amount" class="form-control"
                            required>
                        <span class="input-group-text">VNĐ</span>
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="status">Trạng Thái Thanh Toán</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="Chưa thanh toán">Chưa thanh toán</option>
                        <option value="Đã thanh toán">Đã thanh toán</option>
                    </select>
                </div>

                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-success w-100">Thêm Hóa Đơn</button>
                </div>
            </div>
        </form>


        <!-- Danh Sách Hóa Đơn -->
        <h3 class="mt-5">Danh Sách Hóa Đơn</h3>
        @if($invoices->isEmpty())
        <div class="alert alert-info text-center">Không có hóa đơn nào.</div>
        @else
        <div class="table-responsive">
            <table class="table table-bordered mt-4">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Ngày Lập</th>
                        <th>Tên Bệnh Nhân</th>
                        <th>ID Hồ Sơ</th>
                        <th>Ngày Khám</th>
                        <th>Số Điện Thoại</th>
                        <th>Dịch Vụ + Thuốc</th>
                        <th>Tổng Tiền</th>
                        <th>Trạng Thái</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                    <tr id="invoiceRow-{{ $invoice->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $invoice->invoice_date }}</td>
                        <td>{{ $invoice->patient_name }}</td>
                        <td>{{ $invoice->medical_record_id }}</td>
                        <td>{{ $invoice->exam_date }}</td>
                        <td>{{ $invoice->phone }}</td>
                        <td>{{ $invoice->services_medicines }}</td>
                        <td>{{ number_format($invoice->total_amount, 0) }} VNĐ</td>
                        <td>
                            @if($invoice->status === 'Chưa thanh toán')
                            <span class="badge bg-warning">Chưa Thanh Toán</span>
                            @else
                            <span class="badge bg-success">Đã Thanh Toán</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $invoice->id }}">Sửa</button>
                            <form method="POST" action="{{ route('admin.invoices.destroy', $invoice->id) }}"
                                class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa hóa đơn này?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    <!-- Form chỉnh sửa sẽ được hiển thị khi bấm nút "Sửa" -->
                    <tr id="editRow-{{ $invoice->id }}" style="display: none;">
                        <td colspan="10">
                            <form method="POST" action="{{ route('admin.invoices.update', $invoice->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="date" name="invoice_date" class="form-control"
                                            value="{{ $invoice->invoice_date }}" required>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="services_medicines" class="form-control"
                                            value="{{ $invoice->services_medicines }}">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="total_amount" class="form-control"
                                            value="{{ $invoice->total_amount }}" required>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="status" class="form-control">
                                            <option value="Chưa thanh toán"
                                                {{ $invoice->status == 'Chưa thanh toán' ? 'selected' : '' }}>Chưa thanh
                                                toán</option>
                                            <option value="Đã thanh toán"
                                                {{ $invoice->status == 'Đã thanh toán' ? 'selected' : '' }}>Đã thanh
                                                toán
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mt-2">
                                        <button type="submit" class="btn btn-success">Lưu</button>
                                    </div>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <script>
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            let id = this.getAttribute('data-id');
            let editRow = document.getElementById(`editRow-${id}`);

            if (editRow.style.display === 'none' || editRow.style.display === '') {
                editRow.style.display = 'table-row';
            } else {
                editRow.style.display = 'none';
            }
        });
    });
    </script>

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