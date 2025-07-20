<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class DoctorMedicalRecordController extends Controller
{
    // 📌 Hiển thị danh sách hồ sơ bệnh án của bác sĩ đăng nhập
    public function index(Request $request)
    {
        $search = $request->input('search');
        $doctorId = Auth::id();

        $query = MedicalRecord::where('doctor_id', $doctorId);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('cccd', 'like', "%{$search}%")
                    ->orWhere('diagnosis', 'like', "%{$search}%");
            });
        }

        $medicalRecords = $query->latest()->paginate(10);

        $editMedicalRecord = null;
        if ($request->has('edit_id')) {
            $editMedicalRecord = MedicalRecord::find($request->input('edit_id'));
        }

        return view('role.doctormanagemedicalrecords', compact('medicalRecords', 'search', 'editMedicalRecord'));
    }

    // 📌 Thêm hồ sơ bệnh án mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'age' => 'required|integer',
            'cccd' => 'required|string|max:255',
            'service' => 'nullable|string|max:255',
            'exam_date' => 'required|date',
            'cost' => 'nullable|numeric',
            'status' => 'required|in:paid,unpaid',
            'diagnosis' => 'required|string',
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $cost = $request->filled('cost') ? $request->input('cost') * 1000 : null;

        MedicalRecord::create([
            'doctor_id' => Auth::id(),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'age' => $request->age,
            'cccd' => $request->cccd,
            'service' => $request->service,
            'exam_date' => $request->exam_date,
            'cost' => $cost,
            'status' => $request->status,
            'diagnosis' => $request->diagnosis,
            'prescription' => $request->prescription,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admindoctor.medicalrecords.index')
            ->with('success', 'Hồ sơ bệnh án đã được tạo thành công.');
    }

    // 📌 Chỉnh sửa hồ sơ bệnh án
    public function edit($id)
    {
        $record = MedicalRecord::where('doctor_id', Auth::id())->findOrFail($id);
        $medicalRecords = MedicalRecord::where('doctor_id', Auth::id())->latest()->paginate(10);

        return view('role.doctormanagemedicalrecords', compact('record', 'medicalRecords'));
    }

    // 📌 Cập nhật hồ sơ bệnh án
    public function update(Request $request, $id)
    {
        $record = MedicalRecord::where('doctor_id', Auth::id())->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'age' => 'required|integer',
            'cccd' => 'required|string|max:255',
            'service' => 'nullable|string|max:255',
            'exam_date' => 'required|date',
            'cost' => 'nullable|numeric',
            'status' => 'required|in:paid,unpaid',
            'diagnosis' => 'required|string',
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $cost = $request->filled('cost') ? $request->input('cost') * 1000 : null;

        $record->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'age' => $request->age,
            'cccd' => $request->cccd,
            'service' => $request->service,
            'exam_date' => $request->exam_date,
            'cost' => $cost,
            'status' => $request->status,
            'diagnosis' => $request->diagnosis,
            'prescription' => $request->prescription,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admindoctor.medicalrecords.index')
            ->with('success', 'Hồ sơ bệnh án đã được cập nhật thành công.');
    }

    // 📌 Xóa hồ sơ bệnh án
    public function destroy($id)
    {
        $record = MedicalRecord::where('doctor_id', Auth::id())->findOrFail($id);
        $record->delete();

        return redirect()->route('admindoctor.medicalrecords.index')
            ->with('success', 'Hồ sơ bệnh án đã được xóa thành công.');
    }

    // 📌 Tạo hồ sơ bệnh án từ lịch hẹn
    public function createFromAppointment(Request $request)
    {
        $appointmentId = $request->input('appointment_id');
        $appointment = Appointment::findOrFail($appointmentId); // Lấy lịch hẹn

        // ✅ Tạo hồ sơ bệnh án mới nhưng KHÔNG có ID
        $editMedicalRecord = new MedicalRecord([
            'name' => $appointment->name,
            'email' => $appointment->email,
            'phone' => $appointment->phone,
            'age' => $appointment->age,
            'cccd' => $appointment->cccd,
            'exam_date' => $appointment->appointment_date,
        ]);

        // ✅ Chắc chắn ID = NULL để form nhận diện là thêm mới
        $editMedicalRecord->id = null;

        // ✅ Lấy danh sách các hồ sơ bệnh án hiện có của bác sĩ
        $medicalRecords = MedicalRecord::where('doctor_id', Auth::id())->latest()->get();

        return view('role.doctormanagemedicalrecords', compact('editMedicalRecord', 'medicalRecords'));
    }

}
