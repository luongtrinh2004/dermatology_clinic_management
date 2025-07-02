<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    // Trang cho bệnh nhân xem danh sách thuốc
    public function index(Request $request)
    {
        $search = $request->input('search');
        $medicines = Medicine::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%$search%");
        })->get();

        return view('medicines', compact('medicines', 'search'));
    }

    // Trang cho admin quản lý thuốc
    public function adminIndex(Request $request)
    {
        $search = $request->input('search');
        $medicines = Medicine::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%$search%");
        })->get();

        $editMedicine = null;
        if ($request->has('edit_id')) {
            $editMedicine = Medicine::findOrFail($request->edit_id);
        }

        return view('role.manage_medicines', compact('medicines', 'editMedicine', 'search'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image',
            'description' => 'nullable|string',
            'contraindications' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'quantity' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads'), $filename);
            $data['image'] = 'uploads/' . $filename;
        }

        Medicine::create($data);
        return redirect()->route('admin.medicines.index')->with('success', 'Thêm thuốc thành công.');
    }

    public function update(Request $request, $id)
    {
        $medicine = Medicine::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image',
            'description' => 'nullable|string',
            'contraindications' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'quantity' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads'), $filename);
            $data['image'] = 'uploads/' . $filename;
        }

        $medicine->update($data);
        return redirect()->route('admin.medicines.index')->with('success', 'Cập nhật thuốc thành công.');
    }

    public function destroy($id)
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->delete();
        return redirect()->route('admin.medicines.index')->with('success', 'Xóa thuốc thành công.');
    }
}