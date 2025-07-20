<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DiseaseDetectionController extends Controller
{
    public function index()
    {
        return view('detection');
    }

    public function predict(Request $request)
    {
        $file = $request->file('file');
        if (!$file || !$file->isValid()) {
            return response()->json(['error' => 'File ảnh không hợp lệ hoặc không được gửi lên.'], 400);
        }

        try {
            $response = Http::attach(
                'file', $file->get(), $file->getClientOriginalName()
            )->withOptions(['http_errors' => false])
            ->post('http://3.107.159.98:8002/predict');

            $responseBody = $response->body();
            $responseData = json_decode($responseBody, true);

            if ($response->status() === 200 && $responseData && isset($responseData['disease'])) {
                return response()->json($responseData);
            } else {
                $errorMessage = $responseData['detail'] ?? 'Không thể nhận diện bệnh. Vui lòng thử lại.';
                return response()->json(['error' => $errorMessage], $response->status() ?: 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Lỗi khi kết nối đến server AI: ' . $e->getMessage()], 500);
        }
    }
}
?>