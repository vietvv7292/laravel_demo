<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication trong Laravel</title>
    <!-- Thêm Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-success text-white text-center">
                <h1>Xác Thực (Authentication) trong Laravel</h1>
            </div>
            <div class="card-body">
                <h3>1. Giới Thiệu</h3>
                <p>Laravel cung cấp hệ thống xác thực người dùng mạnh mẽ, hỗ trợ cả xác thực thủ công và tự động thông
                    qua Laravel Breeze, Jetstream hoặc Fortify.</p>

                <h3>2. Tạo Authentication Tự Động</h3>
                <p>Bạn có thể dùng các starter kit để tạo nhanh hệ thống auth:</p>

                <h5>2.1 Laravel Breeze</h5>
                <pre><code>composer require laravel/breeze --dev
php artisan breeze:install
npm install && npm run dev
php artisan migrate</code></pre>
                <p>Sau khi chạy, bạn sẽ có sẵn form login, register, reset password, dashboard...</p>

                <h5>2.2 Laravel Jetstream</h5>
                <pre><code>composer require laravel/jetstream
php artisan jetstream:install livewire
npm install && npm run dev
php artisan migrate</code></pre>
                <p>Jetstream hỗ trợ nhiều tính năng nâng cao như xác thực 2 bước, API token, session management...</p>

                <h3>3. Tạo Authentication Thủ Công</h3>

                <h5>3.1 Tạo Route</h5>
                <pre><code>
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
</code></pre>

                <h5>3.2 Tạo Controller</h5>
                <pre><code>
php artisan make:controller AuthController</code></pre>

                <pre><code>
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không đúng.',
        ]);
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
</code></pre>

                <h5>3.3 Tạo View Login</h5>
                <pre><code>
<!-- resources/views/auth/login.blade.php -->
<form method="POST" action="login">
    @csrf
    <label>Email</label>
    <input type="email" name="email" required>

    <label>Mật khẩu</label>
    <input type="password" name="password" required>

    <button type="submit">Đăng nhập</button>
</form>
</code></pre>

                <h5>3.4 Tạo Seeder Người Dùng</h5>
                <pre><code>
php artisan make:seeder UserSeeder
</code></pre>
                <pre><code>
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => Hash::make('password')
]);
</code></pre>

                <h5>3.5 Middleware auth</h5>
                <p>Thêm middleware <code>auth</code> để bảo vệ route:</p>
                <pre><code>
Route::middleware('auth')->get('/dashboard', function () {
    return view('dashboard');
});
</code></pre>

                <h3>4. Các Hệ Thống Liên Quan</h3>

                <h5>4.1 Laravel Fortify</h5>
                <p>
                    Fortify là một backend authentication system không đi kèm giao diện, cung cấp các tính năng như:
                </p>
                <ul>
                    <li>Đăng ký / Đăng nhập</li>
                    <li>Reset mật khẩu</li>
                    <li>Xác thực 2 yếu tố (2FA)</li>
                    <li>Xác minh email</li>
                    <li>Session Management</li>
                </ul>
                <p>Để cài đặt:</p>
                <pre><code>composer require laravel/fortify
php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"
</code></pre>
                <p>Định nghĩa các hành vi trong <code>App\Actions</code> và bật tính năng trong
                    <code>fortify.php</code>. Bạn cần tự tạo view nếu không dùng Jetstream.</p>

                <h5>4.2 Laravel Sanctum</h5>
                <p>
                    Sanctum là package đơn giản để xác thực API bằng token:
                </p>
                <ul>
                    <li>Hỗ trợ SPA (cookie-based)</li>
                    <li>Hỗ trợ token truyền qua header (Bearer token)</li>
                </ul>
                <p>Cài đặt:</p>
                <pre><code>composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
</code></pre>
                <p>Thêm middleware:</p>
                <pre><code>
'api' => [
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
</code></pre>
                <p>Sử dụng:</p>
                <pre><code>
$user = User::find(1);
$token = $user->createToken('API Token')->plainTextToken;
</code></pre>

                <h5>4.3 Laravel Passport</h5>
                <p>
                    Passport là giải pháp OAuth2 đầy đủ tính năng cho ứng dụng lớn.
                </p>
                <p>Cài đặt:</p>
                <pre><code>composer require laravel/passport
php artisan migrate
php artisan passport:install</code></pre>
                <p>Trong model <code>User</code>:</p>
                <pre><code>use Laravel\Passport\HasApiTokens;

class User extends Authenticatable {
    use HasApiTokens, Notifiable;
}</code></pre>
                <p>Trong <code>AuthServiceProvider</code>:</p>
                <pre><code>Passport::routes();</code></pre>

                <h5>4.4 Middleware</h5>
                <p>
                    Middleware giúp kiểm tra người dùng có đủ điều kiện truy cập một route hay không.
                </p>
                <ul>
                    <li><code>auth</code>: Kiểm tra người dùng đã đăng nhập</li>
                    <li><code>guest</code>: Chỉ cho phép truy cập khi chưa đăng nhập</li>
                    <li><code>verified</code>: Chỉ truy cập nếu email đã xác minh</li>
                    <li><code>can:view-post</code>: Kiểm tra quyền cụ thể</li>
                </ul>
                <p>Áp dụng middleware:</p>
                <pre><code>
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'));
});
</code></pre>
                <p>Bạn cũng có thể tạo middleware tùy chỉnh:</p>
                <pre><code>
php artisan make:middleware CheckAdmin
</code></pre>
                <pre><code>
public function handle($request, Closure $next)
{
    if (!auth()->user()?->is_admin) {
        abort(403);
    }
    return $next($request);
}
</code></pre>

                <h5>4.5 Gợi Ý Mở Rộng</h5>
                <ul>
                    <li>Xác thực qua mạng xã hội (Google, Facebook): Laravel Socialite</li>
                    <li>Xác minh email bằng queue</li>
                    <li>Xác thực 2 yếu tố bằng OTP hoặc Google Authenticator</li>
                    <li>Sử dụng package <code>spatie/laravel-permission</code> để phân quyền theo role</li>
                </ul>

                <h3>5. Custom Guard và Phân Quyền Nâng Cao</h3>

                <h5>5.1 Giới thiệu về Guard</h5>
                <p>
                    Trong Laravel, guard dùng để xác định cách người dùng được xác thực với ứng dụng của bạn (web, API,
                    v.v). Mặc định Laravel có guard <code>web</code> và <code>api</code>. Bạn có thể tạo guard riêng cho
                    từng loại người dùng (admin, nhân viên, khách hàng, v.v).
                </p>

                <h5>5.2 Tạo Custom Guard</h5>
                <p>
                    Giả sử bạn có bảng <code>admins</code> riêng, bạn có thể tạo model và guard như sau:
                </p>

                <pre><code>// Tạo model và migration
php artisan make:model Admin -m
</code></pre>

                <pre><code>// Trong config/auth.php thêm:
'guards' => [
    'admin' => [
        'driver' => 'session',
        'provider' => 'admins',
    ],
],

'providers' => [
    'admins' => [
        'driver' => 'eloquent',
        'model' => App\Models\Admin::class,
    ],
],
</code></pre>

                <p>
                    Sau đó bạn có thể dùng guard <code>admin</code> như sau:
                </p>
                <pre><code>
Auth::guard('admin')->attempt([...]);
Auth::guard('admin')->user();
Auth::guard('admin')->logout();
</code></pre>

                <h5>5.3 Route và Middleware cho Admin</h5>
                <p>
                    Tạo middleware để bảo vệ route dành riêng cho admin:
                </p>

                <pre><code>
// Tạo middleware
php artisan make:middleware AdminAuth
</code></pre>

                <pre><code>
// app/Http/Middleware/AdminAuth.php

public function handle($request, Closure $next)
{
    if (!Auth::guard('admin')->check()) {
        return redirect('/admin/login');
    }
    return $next($request);
}
</code></pre>

                <p>
                    Đăng ký middleware trong <code>app/Http/Kernel.php</code>:
                </p>
                <pre><code>
'admin.auth' => \App\Http\Middleware\AdminAuth::class,
</code></pre>

                <p>
                    Áp dụng middleware vào route:
                </p>
                <pre><code>
Route::middleware(['admin.auth'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    });
});
</code></pre>

                <h5>5.4 Phân Quyền Nâng Cao (Authorization)</h5>
                <p>
                    Laravel cung cấp 2 cách phân quyền: <strong>Gate</strong> và <strong>Policy</strong>.
                </p>

                <h6>5.4.1 Sử dụng Gate</h6>
                <pre><code>
// Trong App\Providers\AuthServiceProvider
use Illuminate\Support\Facades\Gate;

public function boot()
{
    Gate::define('view-dashboard', function ($user) {
        return $user->is_admin;
    });
}
</code></pre>

                <pre><code>
// Sử dụng:
@can('view-dashboard')
&lt;a href="/dashboard"&gt;Vào Dashboard&lt;/a&gt;
@endcan
</code></pre>

                <h6>5.4.2 Sử dụng Policy</h6>
                <pre><code>
// Tạo policy
php artisan make:policy PostPolicy --model=Post
</code></pre>

                <pre><code>
// Trong PostPolicy:
public function update(User $user, Post $post)
{
    return $user->id === $post->user_id;
}
</code></pre>

                <pre><code>
// Trong Controller hoặc View:
$this->authorize('update', $post);

// hoặc trong Blade:
</code></pre>

            </div>
            <div class="card-footer text-center">
                <small class="text-muted">© 2025 Laravel Demo</small>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
