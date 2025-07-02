<!DOCTYPE html>
<html lang="en">
@section('title', 'Quản lý Hồ Sơ Bệnh Án')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <header>
        <!-- Header content remains unchanged -->
    </header>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Thành công!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Lỗi!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="container py-4">
        <h1 class="text-center mb-4" style="font-family: 'Poppins', sans-serif;">Quản lý Hồ Sơ Bệnh Án</h1>

        <!-- Form tìm kiếm hồ sơ bệnh án -->
        <form method="GET" action="{{ route('admindoctor.medicalrecords.index') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên hoặc CCCD..."
                    value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </form>

        <!-- Form thêm/sửa hồ sơ bệnh án -->
        @if(isset($editMedicalRecord))
        <h3 class="mb-3">Sửa Hồ Sơ Bệnh Án</h3>
        <form method="POST" action="{{ route('admindoctor.medicalrecords.update', $editMedicalRecord->id) }}">
            @csrf
            @method('PUT')
            @else
                <h3 class="mb-3">Thêm Hồ Sơ Bệnh Án</h3>
                <form method="POST" action="{{ route('admindoctor.medicalrecords.store') }}">
                    @csrf
            @endif
                <div class="row">
                    <!-- Chọn Bác Sĩ -->
                    <div class="col-md-4 mb-2">
                        <label for="doctor_id" class="form-label">Chọn Bác Sĩ</label>
                        <select name="doctor_id" id="doctor_id" class="form-control" required>
                            <option value="">-- Chọn Bác Sĩ --</option>
                            @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}"
                                {{ (isset($editMedicalRecord) && $editMedicalRecord->doctor_id == $doctor->id) ? 'selected' : '' }}>
                                {{ $doctor->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tên Bệnh Nhân -->
                    <div class="col-md-4 mb-2">
                        <label for="name" class="form-label">Tên Bệnh Nhân</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ $editMedicalRecord->name ?? old('name') }}" required>
                    </div>

                    <!-- Email -->
                    <div class="col-md-4 mb-2">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control"
                            value="{{ $editMedicalRecord->email ?? old('email') }}" required>
                    </div>

                    <!-- Số Điện Thoại -->
                    <div class="col-md-4 mb-2">
                        <label for="phone" class="form-label">Số Điện Thoại</label>
                        <input type="text" name="phone" id="phone" class="form-control"
                            value="{{ $editMedicalRecord->phone ?? old('phone') }}" required>
                    </div>

                    <!-- Tuổi -->
                    <div class="col-md-4 mb-2">
                        <label for="age" class="form-label">Tuổi</label>
                        <input type="number" name="age" id="age" class="form-control"
                            value="{{ $editMedicalRecord->age ?? old('age') }}" required>
                    </div>

                    <!-- CCCD -->
                    <div class="col-md-4 mb-2">
                        <label for="cccd" class="form-label">CCCD</label>
                        <input type="text" name="cccd" id="cccd" class="form-control"
                            value="{{ $editMedicalRecord->cccd ?? old('cccd') }}" required
                            @if(isset($editMedicalRecord)) readonly @endif>
                    </div>

                    <!-- Dịch Vụ -->
                    <div class="col-md-4 mb-2">
                        <label for="service" class="form-label">Dịch Vụ</label>
                        <input type="text" name="service" id="service" class="form-control"
                            value="{{ $editMedicalRecord->service ?? old('service') }}">
                    </div>

                    <!-- Ngày Khám -->
                    <div class="col-md-4 mb-2">
                        <label for="exam_date" class="form-label">Ngày Khám</label>
                        <input type="date" name="exam_date" id="exam_date" class="form-control"
                            value="{{ $editMedicalRecord->exam_date ?? old('exam_date') }}" required>
                    </div>

                    <!-- Chi Phí -->
                    <div class="col-md-4 mb-2">
                        <label for="cost" class="form-label">Chi Phí</label>
                        <div class="input-group">
                            <input type="number" step="any" name="cost" id="cost" class="form-control"
                                value="{{ isset($editMedicalRecord) ? $editMedicalRecord->cost / 1000 : old('cost') }}"
                                required>
                            <span class="input-group-text">.000 VNĐ</span>
                        </div>
                    </div>

                    <!-- Trạng Thái Thanh Toán -->
                    <div class="col-md-4 mb-2">
                        <label for="status" class="form-label">Trạng Thái Thanh Toán</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="unpaid"
                                {{ (isset($editMedicalRecord) && $editMedicalRecord->status == 'unpaid') ? 'selected' : '' }}>
                                Chưa thanh toán
                            </option>
                            <option value="paid"
                                {{ (isset($editMedicalRecord) && $editMedicalRecord->status == 'paid') ? 'selected' : '' }}>
                                Đã thanh toán
                            </option>
                        </select>
                    </div>

                    <!-- Chẩn Đoán -->
                    <div class="col-12 mb-2">
                        <label for="diagnosis" class="form-label">Chẩn Đoán</label>
                        <textarea name="diagnosis" id="diagnosis" class="form-control" rows="3"
                            required>{{ $editMedicalRecord->diagnosis ?? old('diagnosis') }}</textarea>
                    </div>

                    <!-- Toa Thuốc -->
                    <div class="col-12 mb-2">
                        <label for="prescription" class="form-label">Toa Thuốc</label>
                        <textarea name="prescription" id="prescription" class="form-control"
                            rows="3">{{ $editMedicalRecord->prescription ?? old('prescription') }}</textarea>
                    </div>

                    <!-- Ghi Chú -->
                    <div class="col-12 mb-2">
                        <label for="notes" class="form-label">Ghi Chú</label>
                        <textarea name="notes" id="notes" class="form-control"
                            rows="3">{{ $editMedicalRecord->notes ?? old('notes') }}</textarea>
                    </div>

                    <!-- Nút Gửi -->
                    <div class="col-12 mb-2">
                        <button type="submit"
                            class="btn {{ isset($editMedicalRecord) ? 'btn-warning' : 'btn-success' }} w-100">
                            {{ isset($editMedicalRecord) ? 'Lưu Thay Đổi' : 'Thêm Hồ Sơ Bệnh Án' }}
                        </button>
                    </div>
                </div>
            </form>

            <!-- Danh sách hồ sơ bệnh án -->
            <div class="table-responsive">
                <table class="table table-bordered mt-4">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Tên</th>
                            <th>CCCD</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medicalRecords as $record)
                        <tr>
                            <td>{{ $medicalRecords->firstItem() + $loop->index }}</td>
                            <td>{{ $record->name }}</td>
                            <td>{{ $record->cccd }}</td>
                            <td>
                                <button class="btn btn-info btn-sm view-pdf" data-cccd="{{ $record->cccd }}" data-id="{{ $record->id }}">Xem PDF</button>
                                <a href="{{ route('admindoctor.medicalrecords.download', $record->cccd) }}" class="btn btn-success btn-sm">Tải về</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Modal để hiển thị PDF -->
            <div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="pdfModalLabel">Bệnh Án</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <iframe id="pdfFrame" style="width: 100%; height: 500px;" frameborder="0"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <!-- Footer content remains unchanged -->
    </footer>

    <style>
        /* Đảm bảo style.css tồn tại */
        body { font-family: 'Poppins', sans-serif; }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Xử lý nút "Xem PDF"
        $('.view-pdf').on('click', function() {
            const cccd = $(this).data('cccd');
            const modal = new bootstrap.Modal(document.getElementById('pdfModal'));
            $('#pdfFrame').attr('src', `/admindoctor/medicalrecords/view-pdf?cccd=${encodeURIComponent(cccd)}`);
            modal.show();
        });
    </script>
</body>
</html>