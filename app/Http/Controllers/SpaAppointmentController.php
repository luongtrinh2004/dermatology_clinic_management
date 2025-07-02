<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpaAppointment;
use App\Models\SpaServices;

class SpaAppointmentController extends Controller
{
    // Hiển thị danh sách và form thêm/sửa
    public function index(Request $request)
    {
        $search = $request->input('search');

        $appointments = SpaAppointment::with('service')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%");
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        $services = SpaServices::all();

        // Nếu người dùng nhấn nút sửa
        $editAppointment = null;
        if ($request->has('edit_id')) {
            $editAppointment = SpaAppointment::find($request->edit_id);
        }

        return view('role.managespaappointments', compact('appointments', 'services', 'editAppointment', 'search'));
    }

    // Trang tạo lịch hẹn (dành cho khách hoặc admin)
    public function create($service_id = null)
    {
        $services = SpaServices::all();
        return view('spa.appointmentcreate', compact('services', 'service_id'));
    }

    // Lưu lịch hẹn mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'service_id' => 'required|exists:spa_services,id',
            'date' => 'required|date',
            'time' => 'required',
            'note' => 'nullable|string'
        ]);

        SpaAppointment::create($request->all());

        if (auth()->check() && auth()->user()->role === 'admin') {
            return redirect()->route('spa.appointments.index')->with('success', 'Đặt lịch thành công!');
        }

        return redirect()->route('spa.index')->with('success', 'Đặt lịch thành công!');
    }


    // Cập nhật lịch hẹn
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'service_id' => 'required|exists:spa_services,id',
            'date' => 'required|date',
            'time' => 'required',
            'note' => 'nullable|string'
        ]);

        $appointment = SpaAppointment::findOrFail($id);
        $appointment->update($request->all());

        return redirect()->route('spa.appointments.index')->with('success', 'Cập nhật lịch hẹn thành công!');
    }

    // Xóa lịch hẹn
    public function destroy($id)
    {
        $appointment = SpaAppointment::findOrFail($id);
        $appointment->delete();

        return redirect()->route('spa.appointments.index')->with('success', 'Xóa lịch hẹn thành công!');
    }
}