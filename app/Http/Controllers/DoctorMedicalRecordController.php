<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Models\Doctor;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DoctorMedicalRecordController extends Controller
{
    // Hiển thị danh sách hồ sơ bệnh án
    public function index(Request $request)
    {
        $search = $request->input('search');
        $doctorId = $request->input('doctor_id');

        $response = Http::get('http://localhost:8000/doctor_records/', [
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
            $records = array_filter($records, function ($record) use ($search) {
                return stripos($record['name'], $search) !== false || stripos($record['cccd'], $search) !== false;
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
            )->post('http://localhost:8000/upload/');
    
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
            ])->get('http://localhost:8000/records', [
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
}