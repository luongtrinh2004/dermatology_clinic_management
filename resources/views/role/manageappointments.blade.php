<!DOCTYPE html>
<html lang="en">

@section('title', 'Quản lý Lịch Hẹn')

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
                <a href="{{ url('/') }}" class="d-flex align-items-center">
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
        <h1 class="text-center mb-4" style="font-family: 'Poppins', sans-serif;">Quản lý Lịch Hẹn</h1>

        <!-- Form tìm kiếm lịch hẹn -->
        <form method="GET" action="{{ route('admin.appointments.index') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm lịch hẹn..."
                    value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </form>

        <!-- Form thêm/sửa lịch hẹn -->
        @if($editAppointment)
        <h3 class="mb-3">Sửa Lịch Hẹn</h3>
        <form method="POST" action="{{ route('admin.appointments.update', $editAppointment->id) }}">
            @csrf
            <input type="hidden" name="_method" value="POST">

            @else
            <h3 class="mb-3">Thêm Lịch Hẹn</h3>
            <form method="POST" action="{{ route('admin.appointments.store') }}">
                @csrf
                @endif

                <div class="row">
                    <!-- Dịch vụ -->
                    <div class="col-md-4 mb-2">
                        <label for="specialty" class="form-label">Dịch Vụ</label>
                        <select name="specialty" id="specialty" class="form-control" required>
                            <option value="">-- Chọn Dịch Vụ --</option>
                            @foreach($specialties as $specialty)
                            <option value="{{ $specialty }}"
                                {{ isset($editAppointment) && $editAppointment->specialty == $specialty ? 'selected' : '' }}>
                                {{ $specialty }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label for="doctor_id" class="form-label">Chọn Bác Sĩ</label>
                        <select name="doctor_id" id="doctor_id" class="form-control" required>
                            <option value="">-- Chọn mục Dịch Vụ trước --</option>
                        </select>
                    </div>

                    <!-- Script AJAX -->
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script>
                    $(document).ready(function() {
                        $('#specialty').change(function() {
                            var specialty = $(this).val();
                            $('#doctor_id').html(
                                '<option value="">-- Đang tải danh sách bác sĩ... --</option>');

                            if (specialty) {
                                $.ajax({
                                    url: '/get-doctors-by-specialty',
                                    type: 'GET',
                                    data: {
                                        specialty: specialty
                                    },
                                    success: function(data) {
                                        $('#doctor_id').html(
                                            '<option value="">-- Chọn Bác Sĩ --</option>'
                                        );
                                        $.each(data, function(index, doctor) {
                                            $('#doctor_id').append(
                                                '<option value="' +
                                                doctor.id + '">' + doctor.name +
                                                '</option>');
                                        });
                                    },
                                    error: function() {
                                        $('#doctor_id').html(
                                            '<option value="">-- Không có bác sĩ nào --</option>'
                                        );
                                    }
                                });
                            } else {
                                $('#doctor_id').html(
                                    '<option value="">-- Chọn mục Dịch Vụ trước --</option>');
                            }
                        });
                    });
                    </script>

                    <!-- Ngày hẹn -->
                    <div class="col-md-4 mb-2">
                        <label for="appointment_date" class="form-label">Ngày hẹn</label>
                        <input type="date" name="appointment_date" id="appointment_date" class="form-control"
                            value="{{ $editAppointment->appointment_date ?? '' }}" required>
                    </div>

                    <!-- Tên bệnh nhân -->
                    <div class="col-md-4 mb-2">
                        <label for="name" class="form-label">Tên bệnh nhân</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ $editAppointment->name ?? '' }}" required>
                    </div>

                    <!-- Email -->
                    <div class="col-md-4 mb-2">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control"
                            value="{{ $editAppointment->email ?? '' }}" required>
                    </div>

                    <!-- Số điện thoại -->
                    <div class="col-md-4 mb-2">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="text" name="phone" id="phone" class="form-control"
                            value="{{ $editAppointment->phone ?? '' }}" required>
                    </div>

                    <!-- Tuổi -->
                    <div class="col-md-4 mb-2">
                        <label for="age" class="form-label">Tuổi</label>
                        <input type="number" name="age" id="age" class="form-control"
                            value="{{ $editAppointment->age ?? '' }}" required>
                    </div>

                    <!-- CCCD -->
                    <div class="col-md-4 mb-2">
                        <label for="cccd" class="form-label">CCCD</label>
                        <input type="text" name="cccd" id="cccd" class="form-control"
                            value="{{ $editAppointment->cccd ?? '' }}" required>
                    </div>

                    <!-- Mô tả -->
                    <div class="col-md-4 mb-2">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea name="description" id="description"
                            class="form-control">{{ $editAppointment->description ?? '' }}</textarea>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label for="shift" class="form-label">Chọn Ca Làm Việc</label>
                        <select name="shift" id="shift" class="form-control" required>
                            <option value="">-- Vui lòng chọn ngày trước --</option>
                            @if(isset($editAppointment))
                            <option value="morning" {{ $editAppointment->shift == 'morning' ? 'selected' : '' }}>08:00 -
                                12:00</option>
                            <option value="afternoon" {{ $editAppointment->shift == 'afternoon' ? 'selected' : '' }}>
                                14:00 - 18:00</option>
                            @endif
                        </select>
                    </div>
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script>
                    $(document).ready(function() {
                        $('#appointment_date').change(function() {
                            var selectedDate = $(this).val();
                            var doctorId = $('#doctor_id').val();
                            $('#shift').html('<option value="">-- Đang tải ca làm việc... --</option>');

                            if (selectedDate && doctorId) {
                                $.ajax({
                                    url: '/get-working-hours',
                                    type: 'GET',
                                    data: {
                                        doctor_id: doctorId,
                                        date: selectedDate
                                    },
                                    success: function(data) {
                                        $('#shift').html(
                                            '<option value="">-- Chọn Ca Làm Việc --</option>'
                                        );
                                        if (data.morning || data.afternoon) {
                                            if (data.morning) {
                                                $('#shift').append(
                                                    '<option value="morning">08:00 - 12:00</option>'
                                                );
                                            }
                                            if (data.afternoon) {
                                                $('#shift').append(
                                                    '<option value="afternoon">14:00 - 18:00</option>'
                                                );
                                            }
                                        } else {
                                            $('#shift').html(
                                                '<option value="">-- Không có ca làm việc --</option>'
                                            );
                                        }
                                    },
                                    error: function() {
                                        $('#shift').html(
                                            '<option value="">-- Lỗi khi tải dữ liệu --</option>'
                                        );
                                    }
                                });
                            } else {
                                $('#shift').html(
                                    '<option value="">-- Vui lòng chọn ngày trước --</option>');
                            }
                        });
                    });
                    </script>
                    <!-- Nút Gửi -->
                    <div class="col-md-4 mb-2">
                        <button type="submit"
                            class="btn {{ isset($editAppointment) ? 'btn-warning' : 'btn-success' }} w-100"
                            style="    margin-top: 31px;">
                            {{ isset($editAppointment) ? 'Lưu Thay Đổi' : 'Thêm Lịch Hẹn' }}
                        </button>
                    </div>
                </div>
            </form>

            <!-- Danh sách lịch hẹn -->
            <table class="table table-bordered mt-4" style="width: 105%;margin-left: -30px">
                <thead>
                    <tr>
                        <th>#</th>

                        <th>Bác Sĩ</th>
                        <th>Bệnh Nhân</th>
                        <th>Email</th>
                        <th>Mô Tả</th>
                        <th>Điện Thoại</th>
                        <th>Ngày Hẹn</th>
                        <th>Ca Làm Việc</th>
                        <th>Trạng Thái</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>{{ optional($appointment->doctor)->name ?? 'Không xác định' }}</td>
                        <td>{{ $appointment->name }}</td>
                        <td>{{ $appointment->email }}</td>
                        <td>{{ $appointment->description }}</td>
                        <td>{{ $appointment->phone }}</td>
                        <td>{{ $appointment->appointment_date }}</td>
                        <td>
                            @if($appointment->shift === 'morning')
                            <span class="badge bg-primary">08:00 - 12:00</span>
                            @elseif($appointment->shift === 'afternoon')
                            <span class="badge bg-info">14:00 - 18:00</span>
                            @else
                            <span class="badge bg-secondary">Không xác định</span>
                            @endif
                        </td>
                        <td>
                            @if($appointment->status === 'pending')
                            <span class="badge bg-warning">Chờ duyệt</span>
                            @elseif($appointment->status === 'approved')
                            <span class="badge bg-success">Đã duyệt</span>
                            @else
                            <span class="badge bg-danger">Đã từ chối</span>
                            @endif
                        </td>
                        <td>
                            <!-- Nút Duyệt -->
                            <form method="POST" action="{{ route('admin.appointments.approve', $appointment->id) }}"
                                class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success btn-sm">Duyệt</button>
                            </form>

                            <!-- Nút Từ chối -->
                            <form method="POST" action="{{ route('admin.appointments.reject', $appointment->id) }}"
                                class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-dark btn-sm">Từ chối</button>
                            </form>

                            <!-- Nút Sửa -->
                            <a href="{{ route('admin.appointments.index', ['edit_id' => $appointment->id]) }}"
                                class="btn btn-warning btn-sm">Sửa</a>

                            <!-- Nút Xóa -->
                            <form method="POST" action="{{ route('admin.appointments.destroy', $appointment->id) }}"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa lịch hẹn này?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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