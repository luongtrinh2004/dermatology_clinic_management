@extends('layouts.app')

@section('title', 'Danh Sách Bác Sĩ')

@section('content')
<div class="container py-5">
    <h2 class="text-center mb-4"
        style="font-family: 'Poppins', sans-serif; font-size: 36px; font-weight: 700; color: #0056b3;">
        Danh Sách Bác Sĩ
    </h2>

    <!-- Thanh tìm kiếm -->
    <div class="search-container mb-4 text-center">
        <input type="text" id="doctorSearch" class="form-control search-input"
            placeholder="🔍 Nhập tên hoặc chuyên khoa..." onkeypress="handleEnter(event)">
    </div>

    <!-- Kết quả tìm kiếm -->
    <div id="searchResults" class="mt-4"></div>

    @php
    // Nhóm bác sĩ theo chuyên môn
    $specialtyGroups = $doctors->groupBy('specialty');
    @endphp

    <div id="doctorList">
        @foreach($specialtyGroups as $specialty => $doctors)
        <!-- Thanh chuyên môn -->
        <div class="specialty-header">
            <h3 class="specialty-title"><i class="bi bi-stethoscope"></i> {{ $specialty }}</h3>
        </div>

        <div class="row justify-content-center">
            @foreach($doctors as $doctor)
            <div class="col-lg-4 col-md-6 mb-4 doctor-card" data-name="{{ strtolower($doctor->name) }}"
                data-specialty="{{ strtolower($doctor->specialty) }}">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-body text-center p-4">
                        <!-- Ảnh bác sĩ -->
                        <div class="d-flex flex-column align-items-center">
                            <img src="{{ asset($doctor->image) }}" class="rounded-circle mb-3"
                                style="width: 150px; height: 150px; object-fit: cover; border: 5px solid #007bff;"
                                alt="{{ $doctor->name }}">
                        </div>

                        <!-- Thông tin bác sĩ -->
                        <h4 class="doctor-name">{{ $doctor->name }}</h4>
                        <p class="doctor-specialty">Chuyên khoa: {{ $doctor->specialty }}</p>

                        <hr class="doctor-divider">

                        <div class="text-start mx-auto px-3 py-2 rounded info-box" style="max-width: 450px;">
                            <p class="mb-2"><strong>Email:</strong> {{ $doctor->email }}</p>
                            <p class="mb-2"><strong>Số điện thoại:</strong> {{ $doctor->phone }}</p>
                            <p class="mb-2"><strong>Giờ làm việc:</strong></p>
                            @php
                            $workingHours = is_array($doctor->working_hours) ? $doctor->working_hours :
                            json_decode($doctor->working_hours, true) ?? [];
                            @endphp
                            <ul>
                                @foreach($workingHours as $schedule)
                                <li>{{ __('Thứ') }}
                                    {{ $schedule['day'] == 'Monday' ? 'Hai' : 
                                                           ($schedule['day'] == 'Tuesday' ? 'Ba' : 
                                                           ($schedule['day'] == 'Wednesday' ? 'Tư' : 
                                                           ($schedule['day'] == 'Thursday' ? 'Năm' : 
                                                           ($schedule['day'] == 'Friday' ? 'Sáu' : 
                                                           ($schedule['day'] == 'Saturday' ? 'Bảy' : 'Chủ Nhật'))))) }}:
                                    {{ $schedule['shift'] == 'morning' ? '08:00 - 12:00' : '14:00 - 18:00' }}
                                </li>
                                @endforeach
                            </ul>
                            <p class="mb-0"><strong>Mô tả:</strong> {{ $doctor->bio ?: 'Chưa cập nhật' }}</p>
                        </div>

                        <a href="#" class="btn btn-primary btn-sm mt-3">Xem Chi Tiết</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
</div>

<!-- JavaScript tìm kiếm chính xác -->
<script>
function handleEnter(event) {
    if (event.key === "Enter") {
        event.preventDefault();
        searchDoctors();
    }
}

function searchDoctors() {
    let input = document.getElementById('doctorSearch').value.toLowerCase().trim();
    let doctorCards = document.querySelectorAll('.doctor-card');
    let searchResults = document.getElementById('searchResults');
    let doctorList = document.getElementById('doctorList');

    // Ẩn danh sách mặc định
    doctorList.style.display = "none";
    searchResults.innerHTML = "";

    if (input === "") {
        // Nếu tìm kiếm rỗng, hiển thị lại danh sách gốc
        doctorList.style.display = "block";
        return;
    }

    let found = false;
    let resultHTML = '<div class="row justify-content-center">';

    doctorCards.forEach(card => {
        let name = card.getAttribute('data-name');
        let specialty = card.getAttribute('data-specialty');

        if (name.includes(input) || specialty.includes(input)) {
            found = true;
            resultHTML += `<div class="col-lg-4 col-md-6 mb-4">${card.innerHTML}</div>`;
        }
    });

    resultHTML += '</div>';

    if (!found) {
        resultHTML = '<p class="text-center text-danger mt-3">Không tìm thấy kết quả.</p>';
    }

    searchResults.innerHTML = resultHTML;
}
</script>

<!-- CSS giúp tìm kiếm và bố cục hiển thị đẹp hơn -->
<style>
/* Thanh tìm kiếm */
.search-container {
    max-width: 400px;
    margin: 0 auto;
}

.search-input {
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 16px;
    width: 100%;
    transition: 0.3s ease-in-out;
}

.search-input:focus {
    border-color: #007bff;
    box-shadow: 0px 4px 8px rgba(0, 123, 255, 0.2);
    outline: none;
}

/* Tiêu đề nhóm chuyên môn */
.specialty-header {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    padding: 15px 0;
    margin-bottom: 25px;
    text-align: center;
    width: 100%;
    border-radius: 10px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

.specialty-title {
    font-size: 24px;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.specialty-title i {
    font-size: 22px;
}

/* Giao diện bác sĩ */
.doctor-name {
    font-size: 22px;
    font-weight: 600;
    color: #0056b3;
    margin-bottom: 5px;
}

.doctor-specialty {
    font-size: 18px;
    font-weight: 500;
    color: #007bff;
    margin-bottom: 10px;
}

.doctor-divider {
    width: 50%;
    margin: 10px auto;
    border-top: 2px solid #ddd;
}

.info-box {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 10px;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
}
</style>

@endsection