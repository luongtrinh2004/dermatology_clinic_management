@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        .spa-box {
            max-width: 1100px;
            margin: auto;
            padding: 20px;
        }

        .spa-title {
            text-align: center;
            font-size: 36px;
            color: #4a9c9f;
            font-family: 'Georgia', serif;
            margin: 30px 0;
        }

        .admin-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            align-items: center;
        }

        .admin-bar .search-form {
            flex: 1;
        }

        .admin-bar .search-form input {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .admin-bar form {
            flex: 7;
        }

        .admin-bar form input {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .admin-bar .add-button {
            flex: 3;
            text-align: right;
        }

        .btn-add {
            white-space: nowrap;
            padding: 10px 22px;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            background-color: #28a745;
            color: #fff;
            cursor: pointer;
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

        .category-content h4 {
            font-size: 22px;
            font-weight: bold;
            color: #4a9c9f;
            font-family: 'Georgia', serif;
            margin-bottom: 16px;
        }

        .service-title {
            font-weight: 600;
            font-size: 15px;
            color: #247b83;
            background: #e7f7f8;
            padding: 12px 15px;
            border: 1px solid #b7dbdd;
            border-radius: 6px;
            margin-bottom: 10px;
            cursor: pointer;
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

        .service-buttons button,
        .service-buttons form button {
            display: inline-block;
            padding: 8px 15px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            margin-right: 10px;
        }

        .btn-edit {
            background-color: #ffc107;
            color: #000;
        }

        .btn-delete {
            background-color: #dc3545;
            color: #fff;
        }

        #editModal,
        #createModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 500px;
            max-width: 95%;
        }

        .text-right {
            text-align: right;
        }

        @media (max-width: 768px) {
            .admin-bar {
                flex-direction: column;
            }

            .admin-bar form,
            .admin-bar .add-button {
                width: 100%;
            }

            .category-row {
                flex-direction: column;
            }

            .category-img,
            .category-content {
                width: 100%;
            }
        }
    </style>

    <div class="spa-box">
        <h2 class="spa-title">QUẢN LÝ DỊCH VỤ SPA</h2>

        {{-- Tìm kiếm + Thêm --}}
        <div class="admin-bar">
            <form method="GET" action="{{ route('admin.managespaservices') }}" class="search-form">
                <input type="text" name="keyword" placeholder="Tìm kiếm tên danh mục hoặc dịch vụ..."
                    value="{{ request('keyword') }}">
            </form>
            <button class="btn-add" onclick="openCreateModal()">
                <i class="fas fa-plus"></i> Thêm dịch vụ
            </button>
        </div>


        {{-- Hiển thị danh mục và dịch vụ --}}
        @foreach($spaCategories as $index => $category)
            @php
                $matched = request('keyword') ?
                    (stripos($category->title, request('keyword')) !== false ||
                        $category->services->filter(function ($s) {
                            return stripos($s->name, request('keyword')) !== false;
                        })->count() > 0)
                    : true;
            @endphp

            @if($matched)
                <div class="category-row {{ $index % 2 == 1 ? 'flex-row-reverse' : '' }}">
                    <div class="category-img">
                        <img src="{{ asset('img/' . $category->image) }}" alt="{{ $category->title }}">
                    </div>
                    <div class="category-content">
                        <h4>{{ $category->title }}</h4>
                        @foreach($category->services as $service)
                            @if(
                                    !request('keyword') || stripos($service->name, request('keyword')) !== false ||
                                    stripos($category->title, request('keyword')) !== false
                                )
                                <div class="service-block">
                                    <div class="service-title" onclick="toggleService(this)">
                                        {{ $service->name }} - <strong>{{ number_format($service->price, 0, ',', '.') }}₫</strong>
                                    </div>

                                    <div class="service-body">
                                        <p>{{ $service->description }}</p>
                                        <div class="service-buttons">
                                            <button class="btn-edit" onclick='openEditModal(@json($service))'>
                                                <i class="fas fa-edit"></i> Sửa
                                            </button>
                                            <form method="POST" action="{{ route('admin.managespaservices.destroy', $service->id) }}"
                                                style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button class="btn-delete" onclick="return confirm('Bạn chắc chắn muốn xóa?')">
                                                    <i class="fas fa-trash"></i> Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    {{-- Modal Sửa --}}
    <div id="editModal">
        <div class="modal-content">
            <h4>Sửa Dịch Vụ</h4>
            <form id="editForm" method="POST">
                @csrf @method('POST')
                <input type="text" name="name" id="editName" class="form-control mb-2" placeholder="Tên dịch vụ" required>
                <textarea name="description" id="editDescription" class="form-control mb-2" rows="4"
                    placeholder="Mô tả"></textarea>
                <input type="number" name="price" id="editPrice" class="form-control mb-2" placeholder="Giá dịch vụ (VND)"
                    required>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Hủy</button>
                </div>
            </form>

        </div>
    </div>

    {{-- Modal Thêm --}}
    <div id="createModal">
        <div class="modal-content">
            <h4>Thêm Dịch Vụ Mới</h4>
            <form action="{{ route('admin.managespaservices.store') }}" method="POST">
                @csrf
                <input type="text" name="name" class="form-control mb-2" placeholder="Tên dịch vụ" required>
                <textarea name="description" class="form-control mb-2" rows="4" placeholder="Mô tả"></textarea>
                <select name="category_id" class="form-control mb-2" required>
                    <option value="">-- Chọn danh mục --</option>
                    @foreach($spaCategories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                    @endforeach
                </select>
                <div class="text-right">
                    <button type="submit" class="btn btn-success">Thêm</button>
                    <button type="button" class="btn btn-secondary" onclick="closeCreateModal()">Hủy</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleService(el) {
            const body = el.nextElementSibling;
            const isOpen = body.style.display === 'block';
            document.querySelectorAll('.service-body').forEach(b => b.style.display = 'none');
            if (!isOpen) body.style.display = 'block';
        }

        function openEditModal(service) {
            document.getElementById('editName').value = service.name;
            document.getElementById('editDescription').value = service.description;
            document.getElementById('editPrice').value = service.price;
            const form = document.getElementById('editForm');
            form.action = `/admin/managespaservices/${service.id}/update`;
            document.getElementById('editModal').style.display = 'flex';
        }


        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function openCreateModal() {
            document.getElementById('createModal').style.display = 'flex';
        }

        function closeCreateModal() {
            document.getElementById('createModal').style.display = 'none';
        }
    </script>
@endsection