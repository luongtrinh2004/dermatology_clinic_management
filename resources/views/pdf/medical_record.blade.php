<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bệnh Án - {{ $cccd }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; }
        .container { width: 100%; margin: 20px auto; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .record-section { page-break-before: always; } /* Tạo trang mới cho mỗi bản ghi */
    </style>
</head>
<body>
    <div class="container">
        <h1>Danh sách Bệnh Án</h1>
        @foreach($records as $index => $record)
            <div class="record-section">
                <h2>Bệnh Án #{{ $index + 1 }}</h2>
                <table>
                    <tr><th>Trường</th><th>Giá trị</th></tr>
                    <tr><td>Tên</td><td>{{ $record['name'] ?? 'N/A' }}</td></tr>
                    <tr><td>Email</td><td>{{ $record['email'] ?? 'N/A' }}</td></tr>
                    <tr><td>Số điện thoại</td><td>{{ $record['phone'] ?? 'N/A' }}</td></tr>
                    <tr><td>Tuổi</td><td>{{ $record['age'] ?? 'N/A' }}</td></tr>
                    <tr><td>CCCD</td><td>{{ $record['cccd'] ?? 'N/A' }}</td></tr>
                    <tr><td>Dịch vụ</td><td>{{ $record['service'] ?? 'N/A' }}</td></tr>
                    <tr><td>Ngày khám</td><td>{{ $record['exam_date'] ?? $record['date'] ?? 'N/A' }}</td></tr>
                    <tr><td>Chi phí</td><td>{{ $record['cost'] ?? 'N/A' }} VNĐ</td></tr>
                    <tr><td>Trạng thái</td><td>{{ $record['status'] ?? 'N/A' }}</td></tr>
                    <tr><td>Chẩn đoán</td><td>{{ $record['diagnosis'] ?? $record['cd'] ?? 'N/A' }}</td></tr>
                    <tr><td>Toa thuốc</td><td>{{ $record['prescription'] ?? $record['medicine'] ?? 'N/A' }}</td></tr>
                    <tr><td>Ghi chú</td><td>{{ $record['notes'] ?? $record['note'] ?? 'N/A' }}</td></tr>
                </table>
            </div>
        @endforeach
    </div>
</body>
</html>