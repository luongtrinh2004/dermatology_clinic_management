<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpaServices;
use App\Models\SpaCategory;
class AdminSpaServiceController extends Controller
{


    public function index()
    {
        $spaCategories = SpaCategory::with('services')->get(); // Lấy dịch vụ theo danh mục
        return view('role.managespaservices', compact('spaCategories'));
    }

    public function create()
    {
        return view('role.service-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric',
        ]);

        SpaServices::create($request->all());
        return redirect()->route('admin.managespaservices')->with('success', 'Thêm dịch vụ thành công');
    }

    public function edit($id)
    {
        $service = SpaServices::findOrFail($id);
        return view('role.service-edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        $service = SpaServices::findOrFail($id);
        $service->update($request->only(['name', 'description']));

        return redirect()->route('admin.managespaservices')->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        $service = SpaServices::findOrFail($id);
        $service->delete();
        return redirect()->route('admin.managespaservices')->with('success', 'Xóa dịch vụ thành công');
    }
}