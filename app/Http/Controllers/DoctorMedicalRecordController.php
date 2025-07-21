<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DoctorMedicalRecordController extends Controller
{
    // Hiển thị danh sách hồ sơ bệnh án
    public function index(Request $request)
    {
        $search = $request->input('search');
        $doctorId = $request->input('doctor_id');

        $response = Http::get('http://52.63.211.53:8000/doctor_records/', [
            'doctor_id' => $doctorId,
        ]);

        if ($response->failed()) {
            if ($response->status() === 500) {
                return redirect()->back()->with('error', 'Bạn ấn quá nhanh. Hãy thử lại sau một lúc');
            }
            \Log::error('API call failed: ' . $response->body());
        }

        $records = $response->successful() ? $response->json()['records'] : [];

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('cccd', 'like', "%{$search}%")
                    ->orWhere('diagnosis', 'like', "%{$search}%");
            });
        }

        $doctors = Doctor::all();

        $viewMedicalRecord = null;
        if ($request->has('view_id')) {
            $viewMedicalRecord = MedicalRecord::find($request->input('view_id'));
        }

        $doctor = null;
        if ($doctorId) {
            $doctor = Doctor::find($doctorId);
        }

        return view('role.doctormanagemedicalrecords', compact('records', 'doctors', 'search', 'viewMedicalRecord', 'doctor'));
    }

    // Lưu hồ sơ bệnh án mới vào database
    public function store(Request $request)
    {
        $tempPath = null;
    
        try {
            $request->validate([
                'doctor_id' => 'required|exists:doctors,id',
                'name' => 'required|string|max:255',
                'cccd' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'age' => 'required|integer',
                'service' => 'nullable|string|max:255',
                'exam_date' => 'required|date',
                'cost' => 'nullable|numeric',
                'status' => 'required|in:paid,unpaid',
                'diagnosis' => 'required|string',
                'prescription' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);
    
            if ($request->filled('cost')) {
                $request->merge(['cost' => $request->input('cost') * 1000]);
            }
    
            $data = $request->only(['doctor_id', 'name', 'email', 'phone', 'age', 'cccd', 'service', 'exam_date', 'cost', 'status', 'diagnosis', 'prescription', 'notes']);
            $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
            $tempPath = storage_path('app/public/data.json');
            file_put_contents($tempPath, $json);
    
            $response = Http::attach(
                'file',
                file_get_contents($tempPath),
                'data.json'
            )->post('http://52.63.211.53:8000/upload/');
    
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
    
            if ($response->successful()) {
                $responseData = $response->json();
                $cid = $responseData['cid'] ?? null;
    
                if ($cid) {
                    // Kiểm tra bệnh nhân mới/cũ dựa trên cccd
                    $existingRecord = MedicalRecord::where('cccd', $request->input('cccd'))->first();
                    if (!$existingRecord) {
                        // Bệnh nhân mới: Lưu toàn bộ dữ liệu vào database
                        MedicalRecord::create(array_merge($data, ['cid' => $cid]));
                        return redirect()->route('admindoctor.medicalrecords.index', ['doctor_id' => $request->input('doctor_id')])
                            ->with('success', 'Hồ sơ bệnh án đã được tạo thành công.');
                    } else {
                        // Bệnh nhân cũ: Chỉ gửi JSON, không lưu name và cccd
                        MedicalRecord::create(['cid' => $cid, 'doctor_id' => $data['doctor_id']]);
                        return redirect()->route('admindoctor.medicalrecords.index', ['doctor_id' => $request->input('doctor_id')])
                            ->with('success', 'Hồ sơ bệnh án đã được tạo thành công.');
                    }
                } else {
                    return redirect()->back()->with('error', 'Không nhận được CID từ IPFS.');
                }
            } else {
                return redirect()->back()->with('error', 'Lỗi khi lưu lên IPFS: ' . $response->body());
            }
        } catch (\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
            return redirect()->back()->with('success', 'Hồ sơ bệnh án được tạo thành công.');
        }
    }

    // Xử lý GET request từ nút "Xem" để kiểm tra IPFS
    public function checkIpfs($cccd)
    {
        try {
            $response = Http::withOptions([
                'timeout' => 60,
                'allow_redirects' => ['max' => 5]
            ])->get('http://52.63.211.53:8000/records', [
                'cccd' => $cccd
            ]);

            if ($response->successful() && !empty($response->json()['records'])) {
                return response()->json(['status' => 'Lấy dữ liệu thành công']);
            } else {
                return response()->json(['status' => 'Lấy dữ liệu thất bại']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'Lấy dữ liệu thất bại']);
        }
    }

    // Tạo hồ sơ bệnh án từ lịch hẹn
    public function create(Request $request)
    {
        $appointmentId = $request->input('appointment_id');
        $appointment = Appointment::findOrFail($appointmentId); // Lấy lịch hẹn

        // Tạo hồ sơ bệnh án mới
        $editMedicalRecord = new Appointment([
            'name' => $appointment->name,
            'email' => $appointment->email,
            'phone' => $appointment->phone,
            'age' => $appointment->age,
            'cccd' => $appointment->cccd,
            'exam_date' => $appointment->appointment_date,
        ]);

        $editMedicalRecord->id = null;

        // Lấy danh sách hồ sơ bệnh án của bác sĩ
        $response = Http::get('http://52.63.211.53:8000/doctor_records/', [
            'doctor_id' => Auth::id(),
        ]);

        if ($response->failed()) {
            if ($response->status() === 500) {
                return redirect()->back()->with('error', 'Bạn ấn quá nhanh. Hãy thử lại sau một lúc');
            }
            \Log::error('API call failed: ' . $response->body());
        }

        $records = $response->successful() ? $response->json()['records'] : [];

        $doctors = Doctor::all();
        $search = $request->input('search');
        $viewMedicalRecord = null;
        $doctor = Doctor::find(Auth::id());

        if ($search) {
            $records = array_filter($records, function ($record) use ($search) {
                return stripos($record['name'] ?? '', $search) !== false ||
                    stripos($record['email'] ?? '', $search) !== false ||
                    stripos($record['phone'] ?? '', $search) !== false ||
                    stripos($record['cccd'] ?? '', $search) !== false ||
                    stripos($record['diagnosis'] ?? '', $search) !== false;
            });
        }

        return view('role.doctormanagemedicalrecords', compact('editMedicalRecord', 'records', 'doctors', 'search', 'viewMedicalRecord', 'doctor'));
    }
}
