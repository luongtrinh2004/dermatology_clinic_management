<<<<<<< HEAD
# dermatology_clinic_management
=======
# 🏥 Quản Lý Phòng Khám Da Liễu

![Clinic Management System](https://github.com/luongtrinh2004/Patient_Management/blob/main/public/img/readme.png)

## 🚀 Giới thiệu

**Quản Lý Phòng Khám Da Liễu** là hệ thống giúp quản lý các hoạt động của phòng khám, bao gồm bác sĩ, bệnh nhân, lịch hẹn, dịch vụ y tế, hồ sơ bệnh nhân, thanh toán và hỗ trợ. Dự án sử dụng **Laravel** và **Bootstrap**, đảm bảo giao diện thân thiện và trải nghiệm mượt mà.

---

## ✨ Tính năng chính

💪 **Quản lý bác sĩ**: Theo dõi danh sách bác sĩ, chuyên môn và hồ sơ cá nhân.  
👨‍⚕️ **Quản lý bệnh nhân**: Theo dõi danh sách bệnh nhân đăng ký khám bệnh.  
💊 **Quản lý dịch vụ**: Thêm, sửa, xóa các dịch vụ khám da liễu.  
📅 **Quản lý lịch hẹn**: Cho phép bệnh nhân đặt lịch, sau khi đặt thì lịch sẽ hiện ở 2 phía Bác Sĩ và Admin. Admin có quền xem và chỉnh sửa.  
📝 **Quản lý hồ sơ bệnh nhân**: Lưu trữ và quản lý thông tin y tế của từng bệnh nhân.  
💳 **Quản lý hóa đơn & thanh toán**: Tạo và theo dõi hóa đơn, xử lý thanh toán.  
📢 **Hỗ trợ bệnh nhân tích hợp ChatBot AI**: Nhân Viên phản hồi và hỗ trợ bệnh nhân nhanh chóng. Cùng với đó hệ thống Chatbot AI có thể phản hồi ngay lập tức .  
📊 **Thống kê & Báo cáo**: Hiển thị tổng quan về hoạt động phòng khám, doanh thu.  
🔒 **Phân quyền tài khoản**: Hệ thống đăng nhập với vai trò **Admin, Bác sĩ**.

---

## 🛠 Công nghệ sử dụng

| Công nghệ  | Phiên bản |
| ---------- | --------- |
| Laravel    | 7.x       |
| Bootstrap  | 5.x       |
| MySQL      | 8.x       |
| JavaScript | ES6+      |
| jQuery     | 3.x       |

---

## 🛠 Cài đặt & Khởi chạy

### 📌 1. Clone repository

```sh
git clone https://github.com/luongtrinh2004/Patient_Management.git
cd ...
```

### 📌 2. Cấu hình môi trường

```sh
cp .env.example .env
php artisan key:generate
```

🎡 Cập nhật file `.env` với thông tin database của bạn.

### 📌 3. Cài đặt các dependencies

```sh
composer install

```

### 📌 4. Chạy database migration và seed dữ liệu mẫu

```sh
php artisan migrate --seed
```

### 📌 5. Chạy ứng dụng

```sh
php artisan serve
```

---

## 📸 Hình ảnh giao diện

### 🔹 Trang quản lý Admin

![Admin Dashboard](public/img/adminreadme.png)

### 🔹 Quản lý dịch vụ

![Service Management](public/img/quanlydichvureadme.png)

### 🔹 Đặt lịch khám

![Appointment Booking](public/img/quanlylichhenreadme.png)

### 🔹 Quản lý bác sĩ

![Doctor Management](public/img/quanlybacsireadme.png.png)

### 🔹 Hóa đơn & Thanh toán

em đang phát triển ạ
![Billing & Payments](https://via.placeholder.com/800x400?text=Billing+&+Payments)

### 🔹 Hỗ trợ bệnh nhân

![Patient Support](public/img/quanlyhotroreadme.png)

---
>>>>>>> 4fa8dd8 (Initial commit)
