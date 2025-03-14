<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DirectResetController extends Controller
{
    /**
     * Hiển thị form đặt lại mật khẩu trực tiếp.
     *
     * @return \Illuminate\View\View
     */
    public function showResetForm()
    {
        // Chúng ta không cần token, có thể để trống.
        return view('auth.passwords.reset', ['token' => '']);
    }

    /**
     * Xử lý đặt lại mật khẩu trực tiếp.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        // Validate thông tin: email phải tồn tại, password tối thiểu 8 ký tự và phải khớp xác nhận.
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Tìm user theo email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'Không tìm thấy người dùng với email này.'])->withInput();
        }

        // Cập nhật mật khẩu mới (đã băm)
        $user->password = Hash::make($request->password);
        $user->save();

        // Chuyển hướng tới trang đăng nhập với thông báo thành công
        return redirect()->route('login')->with('status', 'Mật khẩu đã được thay đổi thành công. Vui lòng đăng nhập lại.');
    }
}
