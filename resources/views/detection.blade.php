@extends('layouts.app')

@section('title', 'Nhận Diện Bệnh Bằng AI')

@section('content')
<div class="container-fluid p-0">
    <!-- Ảnh tiêu đề -->
    <div class="text-center mb-4">
        <img src="{{ asset('img/contact.webp') }}" alt="Nhận Diện Bệnh" class="img-fluid w-100"
            style="height: 500px; object-fit: cover; border-radius: 0;">
    </div>
</div>

<!-- Form tải ảnh và hiển thị kết quả -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h2 class="mb-3">Nhận Diện Bệnh Bằng AI</h2>
            <p class="lead">Tải ảnh lên để hệ thống nhận diện bệnh da liễu.</p>
        </div>
    </div>

    <!-- Thông báo lỗi -->
    <div id="error-message" class="alert alert-danger alert-dismissible fade show text-center d-none" role="alert">
        <span id="error-text"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!-- Form tải ảnh -->
    <div class="card shadow-lg p-4">
        <h4 class="text-center mb-3">Tải Ảnh Lên</h4>
        <div class="mb-3">
            <label for="image" class="form-label">Chọn ảnh da để nhận diện</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
        </div>
        <!-- Hiển thị ảnh đã chọn -->
        <div class="mb-3 text-center">
            <img id="preview-image" class="img-fluid" style="max-height: 300px; display: none;" alt="Ảnh đã chọn">
        </div>
        <!-- Ô hiển thị kết quả -->
        <div id="result" class="mb-3 text-center d-none">
            <h5>Kết quả nhận diện: <span id="disease-name" class="fw-bold"></span></h5>
        </div>
        <div class="d-grid">
            <button id="predict-button" class="btn btn-primary btn-lg">Nhận Diện</button>
        </div>
    </div>
</div>

<!-- Chatbot Floating Button -->
<style>
.chatbot-button {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #007bff;
    color: white;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 24px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    transition: transform 0.3s ease-in-out;
}

.chatbot-button:hover {
    transform: scale(1.1);
}

.chatbot-tooltip {
    position: fixed;
    bottom: 90px;
    right: 30px;
    background-color: #007bff;
    color: white;
    padding: 10px 15px;
    border-radius: 5px;
    font-size: 14px;
    display: none;
}

.chatbot-button i {
    font-size: 28px;
}
</style>

<!-- Tooltip -->
<div class="chatbot-tooltip" id="chatbot-tooltip">Chat ngay với PhenikaaMec AI</div>

<!-- Chatbot Button -->
<div class="chatbot-button" id="chatbot-button">
    <i class="fas fa-comment-dots"></i>
</div>

<script>
// Xử lý hiển thị ảnh khi chọn
document.getElementById('image').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const preview = document.getElementById('preview-image');
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
        document.getElementById('result').classList.add('d-none');
        document.getElementById('error-message').classList.add('d-none');
    }
});

// Xử lý gửi ảnh và nhận kết quả qua proxy Laravel
document.getElementById('predict-button').addEventListener('click', async function() {
    const fileInput = document.getElementById('image');
    if (!fileInput.files[0]) {
        showError('Vui lòng chọn một ảnh để nhận diện.');
        return;
    }

    const formData = new FormData();
    formData.append('file', fileInput.files[0]);

    try {
        const response = await fetch('{{ route("detection.predict") }}', {
            method: 'POST',
            body: formData
            // Loại bỏ headers X-CSRF-TOKEN vì CSRF đã bị vô hiệu hóa
        });

        const text = await response.text();
        let result;
        try {
            result = JSON.parse(text);
        } catch (e) {
            showError('Lỗi server: Phản hồi không phải JSON hợp lệ. Nội dung: ' + text.substring(0, 100));
            return;
        }

        if (response.ok) {
            if (result.error) {
                showError(result.error);
            } else {
                document.getElementById('disease-name').textContent = result.disease;
                document.getElementById('result').classList.remove('d-none');
                document.getElementById('error-message').classList.add('d-none');
            }
        } else {
            showError(result.error || 'Không thể nhận diện bệnh. Vui lòng thử lại.');
        }
    } catch (error) {
        showError('Lỗi khi xử lý ảnh: ' + error.message);
    }
});

// Hàm hiển thị thông báo lỗi
function showError(message) {
    const errorMessage = document.getElementById('error-message');
    document.getElementById('error-text').textContent = message;
    errorMessage.classList.remove('d-none');
    document.getElementById('result').classList.add('d-none');
}

// Xử lý chatbot
document.getElementById('chatbot-button').addEventListener('click', function() {
    window.location.href = "{{ route('chatbot.index') }}";
});

document.getElementById('chatbot-button').addEventListener('mouseenter', function() {
    document.getElementById('chatbot-tooltip').style.display = 'block';
});

document.getElementById('chatbot-button').addEventListener('mouseleave', function() {
    document.getElementById('chatbot-tooltip').style.display = 'none';
});
</script>
@endsection