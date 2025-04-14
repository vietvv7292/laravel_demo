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
                <p>Laravel cung cấp hệ thống xác thực người dùng mạnh mẽ, hỗ trợ cả xác thực thủ công và tự động thông
                    qua Laravel Breeze, Jetstream hoặc Fortify.</p>

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
                <h5>3.2.1 Giải thích một số câu hỏi thường gặp</h5>
                <ul>
                    <li><strong>Auth::attempt() hoạt động như thế nào?</strong><br>
                        Phương thức <code>Auth::attempt($credentials)</code> sẽ kiểm tra xem email và mật khẩu truyền
                        vào có hợp lệ không (dựa vào bảng <code>users</code>). Laravel sẽ tự động hash mật khẩu đã nhập
                        và so sánh với mật khẩu được lưu trong cơ sở dữ liệu. Nếu khớp, nó sẽ đăng nhập người dùng và
                        trả về <code>true</code>; ngược lại trả về <code>false</code>.
                    </li>

                    <li class="mt-2"><strong>auth()->user() và Auth::user() khác nhau không?</strong><br>
                        Cả hai đều trả về thông tin người dùng hiện tại đã đăng nhập. Về bản chất:
                        <ul>
                            <li><code>auth()</code> là helper function, tiện dùng khi cần chain method nhanh như
                                <code>auth()->user()->email</code>
                            </li>
                            <li><code>Auth::user()</code> dùng khi bạn muốn gọi thông qua facade <code>Auth</code></li>
                        </ul>
                        Chúng tương đương nhau, bạn có thể dùng tùy theo phong cách code bạn thích.
                    </li>

                    <li class="mt-2"><strong>Sử dụng điều kiện nâng cao với Auth::attempt()</strong><br>
                        Từ Laravel 9.42+, bạn có thể truyền thêm một callback (hoặc closure) vào trong mảng
                        <code>Auth::attempt()</code> để giới hạn thêm điều kiện truy vấn.<br>
                        Ví dụ:
                        <pre><code>
if (Auth::attempt([
    'email' => $email,
    'password' => $password,
    fn (Builder $query) => $query->has('activeSubscription'),
])) {
    // Người dùng có email, mật khẩu đúng và có subscription đang active
}
        </code></pre>
                        Trong ví dụ này, Laravel sẽ chỉ đăng nhập người dùng nếu họ có quan hệ
                        <code>activeSubscription</code> (một quan hệ Eloquent đã định nghĩa trong model User như
                        <code>hasOne</code> hoặc <code>hasMany</code> với điều kiện đang hoạt động).
                    </li>
                </ul>


                <h5>3.3 Tạo Seeder Người Dùng</h5>
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

                <h5>3.4 Middleware auth</h5>
                <pre><code>
Route::middleware('auth')->get('/dashboard', function () {
    return view('dashboard');
});
</code></pre>

                <h5>3.5 Lấy Thông Tin Người Dùng Đang Đăng Nhập</h5>
                <p>Để lấy thông tin người dùng hiện tại, bạn có thể sử dụng:</p>
                <pre><code>
$user = Auth::user();
$name = Auth::user()->name;
$email = auth()->user()->email;
</code></pre>

                <h3>4. Authentication API cho App</h3>

                <h5>4.1 Laravel Sanctum – Dành cho SPA hoặc Mobile App đơn giản</h5>
                <p><strong>Laravel Sanctum</strong> là package nhẹ, dễ cấu hình, hỗ trợ xác thực API token, lý tưởng cho
                    SPA (Single Page Application) hoặc ứng dụng mobile không quá phức tạp.</p>

                <h6>Ưu điểm:</h6>
                <ul>
                    <li>Cài đặt đơn giản</li>
                    <li>Hỗ trợ xác thực dựa trên cookie hoặc token</li>
                    <li>Không yêu cầu flow OAuth2 phức tạp</li>
                </ul>

                <h6>Các bước cài đặt:</h6>
                <pre><code>composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate</code></pre>

                <p><strong>Giải thích:</strong></p>
                <ul>
                    <li><code>composer require laravel/sanctum</code>: Cài gói Sanctum để Laravel hỗ trợ xác thực API
                        thông qua token.</li>
                    <li><code>php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"</code>:
                        Copy file cấu hình <code>config/sanctum.php</code> từ gói Sanctum về project, dùng để tuỳ chỉnh.
                    </li>
                    <li><code>php artisan migrate</code>: Tạo bảng <code>personal_access_tokens</code> trong database để
                        lưu các token.</li>
                </ul>
                {{-- <p>🛡️ Thêm middleware cho API: Bạn sẽ thêm đoạn sau vào file app/Http/Kernel.php, trong phần middlewareGroups, cụ thể là group 'api':</p>
                <pre><code>'api' => [
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],</code></pre>

                <p><strong>Giải thích:</strong></p>
                <ul>
                    <li><code>EnsureFrontendRequestsAreStateful</code>: Middleware này dùng cho ứng dụng dạng SPA
                        (Single Page Application) sử dụng cookie để xác thực. Nó đảm bảo các request từ frontend được xử
                        lý đúng trong trạng thái đăng nhập.</li>
                    <li><code>throttle:api</code>: Giới hạn số lượng request trong một khoảng thời gian (rate limiting),
                        tránh spam API.</li>
                    <li><code>SubstituteBindings</code>: Tự động binding các route parameters vào model tương ứng (Route
                        Model Binding).</li>
                </ul> --}}


                <h4>📌 Tạo Route cho API sử dụng Sanctum</h4>

                <p>Thêm các route sau vào <code>routes/api.php</code>:</p>

                <pre><code>
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

// Route đăng nhập trả về token
Route::post('/login', [ApiController::class, 'login']);

// Route được bảo vệ bằng Sanctum
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
</code></pre>

                <h4>📌 Tạo Controller xử lý đăng nhập</h4>

                <p>Tạo <code>ApiController</code> nếu bạn chưa có:</p>
                <pre><code>php artisan make:controller ApiController</code></pre>

                <p>Thêm phương thức <code>login</code> vào <code>app/Http/Controllers/ApiController.php</code>:</p>

                <pre><code>
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ApiController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Sai thông tin'], 401);
        }

        return response()->json([
            'token' => $user->createToken('app-token')->plainTextToken
        ]);
    }
}
</code></pre>
                <h6>Thêm trait vào model User:</h6>
                <p>Để sử dụng được phương thức <code>createToken()</code>, bạn cần thêm trait <code>HasApiTokens</code>
                    vào model <code>User</code>:</p>

                <pre><code>
// File: app/Models/User.php

use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // ...
}
</code></pre>


                <p>Sau đó bạn có thể dùng Postman để test:</p>
                <ul>
                    <li>
                        <code>POST /api/login</code>: Gửi email và password để nhận token.
                        <br>
                        <strong>Body (JSON):</strong>
                        <pre><code>{
    "email": "user@example.com",
    "password": "your_password"
}</code></pre>
                        <strong>Headers:</strong>
                        <ul>
                            <li><code>Accept: application/json</code></li>
                            <li><code>Content-Type: application/json</code></li>
                        </ul>
                    </li>
                    <li>
                        <code>GET /api/user</code>: Gửi token trong header Authorization để truy cập.
                        <br>
                        <strong>Headers:</strong>
                        <ul>
                            <li><code>Accept: application/json</code></li>
                            <li><code>Authorization: Bearer &lt;token&gt;</code> (thay <code>&lt;token&gt;</code> bằng
                                token bạn nhận được khi login)</li>
                        </ul>
                    </li>
                </ul>



                <h5>4.2 Laravel Passport – Dành cho ứng dụng cần OAuth2 phức tạp</h5>
                <p><strong>Laravel Passport</strong> là giải pháp xác thực đầy đủ dựa trên chuẩn
                    <strong>OAuth2</strong>. Nó rất phù hợp nếu bạn cần:</p>
                <ul>
                    <li>Cấp token cho ứng dụng bên thứ ba</li>
                    <li>Phân biệt giữa nhiều client (ví dụ: mobile app, SPA, bên đối tác...)</li>
                    <li>Quản lý token có thời hạn, refresh token, và các quyền (scope)</li>
                </ul>

                <h6>Ưu điểm:</h6>
                <ul>
                    <li>Hỗ trợ đầy đủ các flow OAuth2: Authorization Code, Client Credentials, Password Grant, Refresh
                        Token...</li>
                    <li>Tích hợp sẵn giao diện cấp token (dùng trong quá trình dev hoặc quản lý)</li>
                    <li>Có thể cấu hình scope để giới hạn quyền của từng token</li>
                </ul>

                <h6>Các bước cài đặt:</h6>
                <pre><code>composer require laravel/passport
php artisan migrate
php artisan passport:install</code></pre>

                <h6>Cập nhật model <code>User</code>:</h6>
                <pre><code>use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
}</code></pre>

                <h6>Trong <code>App\Providers\AuthServiceProvider</code>:</h6>
                <pre><code>use Laravel\Passport\Passport;

public function boot()
{
    Passport::routes();
}</code></pre>

                <h6>Trong file <code>config/auth.php</code>, sửa phần guard <code>api</code>:</h6>
                <pre><code>'guards' => [
    'api' => [
        'driver' => 'passport',
        'provider' => 'users',
    ],
],</code></pre>

                <h6>Route và xử lý đăng nhập để nhận token:</h6>
                <pre><code>// Route trong routes/api.php
Route::post('/login', [AuthController::class, 'login']);
</code></pre>

                <pre><code>// AuthController.php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

public function login(Request $request)
{
    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json(['message' => 'Sai thông tin đăng nhập'], 401);
    }

    $user = Auth::user();
    $token = $user->createToken('API Token')->accessToken;

    return response()->json([
        'token' => $token,
        'user' => $user,
    ]);
}
</code></pre>

                <h6>Bảo vệ route bằng middleware <code>auth:api</code>:</h6>
                <pre><code>Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});</code></pre>

                <h6>Sử dụng Postman để test:</h6>
                <ul>
                    <li><code>POST /api/login</code>: gửi <strong>email</strong> và <strong>password</strong> trong body
                        (JSON) để lấy access token.</li>
                    <li><code>GET /api/user</code>: gửi <code>Authorization: Bearer &lt;token&gt;</code> trong header để
                        truy cập.</li>
                </ul>


                <h5>4.3 So sánh Sanctum và Passport</h5>
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Tiêu chí</th>
                            <th>Sanctum</th>
                            <th>Passport</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Chuẩn giao tiếp</td>
                            <td>Tự định nghĩa token</td>
                            <td>Chuẩn OAuth2</td>
                        </tr>
                        <tr>
                            <td>Độ phức tạp</td>
                            <td>Thấp</td>
                            <td>Cao</td>
                        </tr>
                        <tr>
                            <td>Trường hợp sử dụng</td>
                            <td>SPA, mobile app đơn giản</td>
                            <td>Ứng dụng cần liên kết bên thứ 3</td>
                        </tr>
                        <tr>
                            <td>Cơ chế xác thực</td>
                            <td>Token hoặc cookie</td>
                            <td>Bearer token theo chuẩn OAuth2</td>
                        </tr>
                        <tr>
                            <td>Hiệu năng</td>
                            <td>Nhẹ</td>
                            <td>Nặng hơn</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-center">
                <small class="text-muted">© 2025 Laravel Demo</small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
