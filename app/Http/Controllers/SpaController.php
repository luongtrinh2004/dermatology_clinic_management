<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpaCategory;
use App\Models\SpaServices;

class SpaController extends Controller
{
    // Trang danh sách dịch vụ theo nhóm (có ảnh title riêng)
    public function index()
    {
        // Lấy tất cả category kèm dịch vụ con (eager loading)
        $spaCategories = SpaCategory::with('services')->get();

        return view('spa.index', compact('spaCategories'));
    }

    // Trang đặt lịch theo từng dịch vụ
    public function book($id)
    {
        $service = SpaServices::findOrFail($id);
        return view('spa.book', compact('service'));
    }
    public function show($id)
    {
        $service = SpaServices::findOrFail($id);
        return view('spa.service-detail', compact('service'));
    }

}