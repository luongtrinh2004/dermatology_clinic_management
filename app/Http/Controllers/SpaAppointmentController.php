<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpaAppointment;
use App\Models\SpaServices;


class SpaAppointmentController extends Controller
{
    public function create($service_id = null)
    {
        $services = SpaServices::all(); // hoặc SpaService nếu bạn đổi tên model
        return view('spa.appointmentcreate', compact('services', 'service_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'service_id' => 'required',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        SpaAppointment::create($request->all());

        return redirect()->route('spa.index')->with('success', 'Đặt lịch thành công!');

    }

    public function index()
    {
        $appointments = SpaAppointment::with('service')->latest()->get();
        return view('role.managespaappointments', compact('appointments'));

    }
}