<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication trong Laravel</title>
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
                <p>Laravel cung cấp hệ thống xác thực người dùng mạnh mẽ, hỗ trợ cả xác thực thủ công và tự động thông qua Laravel Breeze, Jetstream hoặc Fortify.</p>

                <h3>2. Tạo Authentication Tự Động</h3>
                <p>Bạn có thể dùng các starter kit để tạo nhanh hệ thống auth:</p>

                <h5>2.1 Laravel Breeze</h5>
                <pre><code>composer require laravel/breeze --dev
php artisan breeze:install
npm install && npm run dev
php artisan migrate</code></pre>

                <h5>2.2 Laravel Jetstream</h5>
                <pre><code>composer require laravel/jetstream
php artisan jetstream:install livewire
npm install && npm run dev
php artisan migrate</code></pre>

                <h3>3. Tạo Authentication Thủ Công</h3>

                <h5>3.1 Tạo Route</h5>
                <pre><code>
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
</code></pre>

                <h5>3.2 Tạo Controller</h5>
                <pre><code>php artisan make:controller AuthController</code></pre>

                <pre><code>
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function showRegisterForm() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }
}
</code></pre>

                <h5>3.3 Tạo View Login & Register</h5>
                <pre><code>
<!-- resources/views/auth/login.blade.php -->
<form method="POST" action="/login">
    @csrf
    <label>Email</label>
    <input type="email" name="email" required>

    <label>Mật khẩu</label>
    <input type="password" name="password" required>

    <button type="submit">Đăng nhập</button>
</form>
<a href="{{ route('register') }}">Đăng ký</a>
</code></pre>

<pre><code>
<!-- resources/views/auth/register.blade.php -->
<form method="POST" action="/register">
    @csrf
    <label>Tên</label>
    <input type="text" name="name" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Mật khẩu</label>
    <input type="password" name="password" required>

    <label>Xác nhận mật khẩu</label>
    <input type="password" name="password_confirmation" required>

    <button type="submit">Đăng ký</button>
</form>
<a href="{{ route('login') }}">Đăng nhập</a>
</code></pre>

                <h5>3.4 Tạo Seeder Người Dùng</h5>
                <pre><code>php artisan make:seeder UserSeeder</code></pre>
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
                <pre><code>
Route::middleware('auth')->get('/dashboard', function () {
    return view('dashboard');
});
</code></pre>

                <h5>3.6 Lấy Thông Tin Người Dùng Đang Đăng Nhập</h5>
                <p>Để lấy thông tin người dùng hiện tại, bạn có thể sử dụng:</p>
                <pre><code>
$user = Auth::user();
$name = Auth::user()->name;
$email = auth()->user()->email;
</code></pre>

                <h3>4. Các Hệ Thống Liên Quan</h3>

                <h5>4.1 Laravel Fortify</h5>
                <pre><code>composer require laravel/fortify
php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"</code></pre>

                <h5>4.2 Laravel Sanctum</h5>
                <pre><code>composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate</code></pre>

                <h5>4.3 Laravel Passport</h5>
                <pre><code>composer require laravel/passport
php artisan migrate
php artisan passport:install</code></pre>

                <h5>4.4 Middleware</h5>
                <ul>
                    <li><code>auth</code>: Kiểm tra người dùng đã đăng nhập</li>
                    <li><code>guest</code>: Chỉ cho phép truy cập khi chưa đăng nhập</li>
                    <li><code>verified</code>: Chỉ truy cập nếu email đã xác minh</li>
                    <li><code>can:view-post</code>: Kiểm tra quyền cụ thể</li>
                </ul>

                <h5>4.5 Gợi Ý Mở Rộng</h5>
                <ul>
                    <li>Xác thực qua mạng xã hội (Google, Facebook): Laravel Socialite</li>
                    <li>Xác minh email bằng queue</li>
                    <li>Xác thực 2 yếu tố bằng OTP hoặc Google Authenticator</li>
                    <li>Sử dụng package <code>spatie/laravel-permission</code> để phân quyền theo role</li>
                </ul>

                <h3>5. Authentication API nâng cao</h3>

                <h5>5.1 Laravel Sanctum</h5>
                <p>Phù hợp với SPA, ứng dụng mobile hoặc API đơn giản:</p>
                <pre><code>composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate</code></pre>
                <p>Thêm middleware vào <code>api</code> trong <code>app/Http/Kernel.php</code>:</p>
                <pre><code>'api' => [
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],</code></pre>
                <p>Đăng nhập và tạo token API:</p>
                <pre><code>
public function login(Request $request)
{
    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Đăng nhập thất bại'], 401);
    }

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json(['token' => $token]);
}
</code></pre>
                <p>Bảo vệ route với middleware <code>auth:sanctum</code>:</p>
                <pre><code>
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
</code></pre>

                <h5>5.2 Laravel Passport</h5>
                <p>Phù hợp nếu bạn cần xác thực theo chuẩn OAuth2:</p>
                <pre><code>composer require laravel/passport
php artisan migrate
php artisan passport:install</code></pre>
                <p>Trong <code>App\Models\User</code>:</p>
                <pre><code>
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
}
</code></pre>
                <p>Trong <code>AuthServiceProvider</code>:</p>
                <pre><code>
use Laravel\Passport\Passport;

public function boot()
{
    Passport::routes();
}
</code></pre>
                <p>Trong <code>config/auth.php</code>:</p>
                <pre><code>
'guards' => [
    'api' => [
        'driver' => 'passport',
        'provider' => 'users',
    ],
],
</code></pre>

            </div>
            <div class="card-footer text-center">
                <small class="text-muted">© 2025 Laravel Demo</small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
