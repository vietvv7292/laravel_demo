<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Lệnh kiểm tra thông tin đăng nhập:
        // Auth::attempt($credentials) sẽ kiểm tra xem email và password có hợp lệ không.
        // Nếu đúng, Laravel sẽ đăng nhập người dùng và trả về true.
        // Nếu sai, trả về false.
        // Hàm này sẽ tự động hash password để so sánh với password trong DB.

        // ✅ Nếu muốn xác thực theo 'username' thay vì 'email':
        // $credentials = $request->only('username', 'password');
        // => Laravel sẽ dùng cột 'username' trong bảng users để xác thực.

        // ✅ Ghi nhớ đăng nhập:
        // Auth::attempt($credentials, $remember)
        // Nếu $remember = true, Laravel sẽ lưu thông tin đăng nhập vào cookie để không bị đăng xuất khi tắt trình duyệt.

        
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không đúng.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }


    // Đăng ký người dùng mới
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }

    public function showDashboard()
    {
        $users = User::all();
        return view('auth.dashboard', compact('users'));
    }

    // Chỉnh sửa thông tin người dùng
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('auth.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6|confirmed', // quan trọng: dùng confirmed
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('user.edit', $id)->with('success', 'Cập nhật thành công.');
    }
}
