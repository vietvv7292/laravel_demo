<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Broadcasting Docs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3>Laravel Broadcasting: Gửi dữ liệu real-time thông qua WebSockets</h3>
            </div>
            <div class="card-body">
                <h5>1. Giới thiệu</h5>
                <p>Broadcasting trong Laravel cho phép bạn truyền dữ liệu từ server đến client theo thời gian thực
                    (real-time) thông qua WebSockets. Điều này rất hữu ích trong các ứng dụng như chat, thông báo,
                    dashboard realtime, v.v...</p>

                <p>Laravel hỗ trợ ba cách để triển khai WebSockets cho Broadcasting:</p>
                <ul>
                    <li><strong>Pusher</strong> - Dịch vụ WebSockets phổ biến, dễ sử dụng nhưng có chi phí.</li>
                    <li><strong>Laravel WebSockets</strong> - WebSocket server tự-hosted của Laravel, không cần dịch vụ
                        bên ngoài.</li>
                    <li><strong>Redis</strong> - Dùng Redis làm backend cho Broadcasting để truyền tải sự kiện giữa các
                        server.</li>
                </ul>

                <h5>2. Cài đặt môi trường Broadcasting</h5>
                <p>Trước khi cấu hình, bạn cần phải cài đặt một trong ba phương pháp sau để Broadcasting hoạt động. Dưới
                    đây là hướng dẫn chi tiết cho từng cách.</p>

                <h5>2.1. Cài đặt và cấu hình Pusher</h5>
                <p>Để sử dụng Pusher, bạn cần cài đặt gói Pusher qua Composer:</p>
                <pre><code>composer require pusher/pusher-php-server</code></pre>

                <p>Cập nhật các thông số Pusher trong file <code>.env</code>:</p>
                <pre><code>.env:
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=your-cluster</code></pre>

                <p>Cập nhật cấu hình trong file <code>config/broadcasting.php</code>:</p>
                <pre><code>config/broadcasting.php:
'pusher' => [
    'driver' => 'pusher',
    'key' => env('PUSHER_APP_KEY'),
    'secret' => env('PUSHER_APP_SECRET'),
    'app_id' => env('PUSHER_APP_ID'),
    'options' => [
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'useTLS' => true,
    ],
],</code></pre>

                <h5>2.2. Cài đặt và cấu hình Laravel WebSockets (Tự-hosted)</h5>
                <p>Laravel WebSockets là một giải pháp WebSockets tự-hosted, giúp bạn không cần phụ thuộc vào dịch vụ
                    Pusher bên ngoài.</p>

                <p>Cài đặt gói Laravel WebSockets:</p>
                <pre><code>composer require beyondcode/laravel-websockets</code></pre>

                <p>Cập nhật file <code>.env</code> như sau (vẫn dùng <code>pusher</code>):</p>
                <pre><code>.env:
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=local
PUSHER_APP_KEY=local
PUSHER_APP_SECRET=local
PUSHER_APP_CLUSTER=mt1</code></pre>

                <p>Trong file <code>config/broadcasting.php</code>, chỉ cần chỉnh phần <code>pusher</code> như sau:</p>
                <pre><code>'pusher' => [
    'driver' => 'pusher',
    'key' => env('PUSHER_APP_KEY', 'local'),
    'secret' => env('PUSHER_APP_SECRET', 'local'),
    'app_id' => env('PUSHER_APP_ID', 'local'),
    'options' => [
        'cluster' => env('PUSHER_APP_CLUSTER', 'mt1'),
        'useTLS' => false,
        'host' => '127.0.0.1',
        'port' => 6001,
        'scheme' => 'http',
    ],
],</code></pre>

                <p>Cuối cùng, khởi động WebSocket server:</p>
                <pre><code>php artisan websockets:serve</code></pre>


                <h5>2.3. Cài đặt và cấu hình Redis</h5>
                <p>Redis có thể sử dụng làm backend cho Broadcasting khi bạn cần triển khai với nhiều server hoặc không
                    muốn sử dụng dịch vụ WebSockets như Pusher.</p>

                <p>Đầu tiên, cài đặt Redis qua Composer:</p>
                <pre><code>composer require predis/predis</code></pre>

                <p>Cập nhật cấu hình trong file <code>.env</code> để sử dụng Redis:</p>
                <pre><code>.env:
BROADCAST_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379</code></pre>

                <p>Trong file <code>config/broadcasting.php</code>, cấu hình Redis như sau:</p>
                <pre><code>config/broadcasting.php:
'redis' => [
    'driver' => 'redis',
    'connection' => 'default',
],</code></pre>

                <h5>3. Tạo Event để Broadcast</h5>
                <p>Tiếp theo, bạn cần tạo một Event mà sẽ được broadcast. Dùng lệnh Artisan để tạo một Event mới:</p>
                <pre><code>php artisan make:event MessageSent</code></pre>

                <p>Trong file <code>app/Events/MessageSent.php</code>, chỉnh sửa như sau:</p>
                <pre><code>use Illuminate\\Contracts\\Broadcasting\\ShouldBroadcast;

class MessageSent implements ShouldBroadcast
{
    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return ['chat']; // Kênh phát sóng 'chat'
    }
}</code></pre>

                <p>Event này sẽ broadcast một thông điệp đến tất cả những người đang lắng nghe kênh <code>chat</code>.
                </p>

                <h5>4. Gửi Event từ Controller</h5>
                <p>Trong Controller, bạn có thể phát sự kiện mỗi khi có một hành động nào đó, ví dụ như gửi một tin
                    nhắn:</p>
                <pre><code>use App\\Events\\MessageSent;
use Illuminate\\Http\\Request;

Route::post('/send-message', function (Request $request) {
    broadcast(new MessageSent($request->message));
    return ['status' => 'Message broadcasted'];
});</code></pre>
                <h5>4.1. Bắt buộc chạy Queue Worker</h5>
                <p>Khi sử dụng Broadcasting (ví dụ phát Event như <code>MessageSent</code>), Laravel sẽ tự động đưa
                    Event vào hệ thống <strong>queue</strong>. Vì vậy, bạn <strong>bắt buộc phải chạy queue
                        worker</strong> để Laravel thực sự phát Event đi.</p>

                <p>Chạy lệnh sau để khởi động queue worker:</p>

                <pre><code>php artisan queue:work</code></pre>

                <p><strong>Ghi chú:</strong> Nếu không chạy queue worker, Event sẽ không được broadcast và bạn sẽ không
                    nhận được dữ liệu real-time trên client.</p>


                <h5>5. Cấu hình Client (Frontend)</h5>
                <p>Để lắng nghe sự kiện từ server, bạn cần cấu hình Laravel Echo và Pusher trên client. Trước tiên, cài
                    đặt các gói cần thiết:</p>
                <pre><code>npm install --save laravel-echo pusher-js</code></pre>

                <p>Trong file <code>resources/js/bootstrap.js</code>, thêm cấu hình sau:</p>
                <pre><code>// resources/js/bootstrap.js
import Echo from 'laravel-echo';
window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    wsHost: window.location.hostname,
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
});</code></pre>

                <h5>6. Lắng nghe sự kiện trên Client</h5>
                <p>Sử dụng Laravel Echo để lắng nghe sự kiện phát từ server. Trong file
                    <code>resources/js/app.js</code>, thêm mã sau:
                </p>
                <pre><code>Echo.channel('chat')
    .listen('MessageSent', (e) => {
        console.log('Tin nhắn mới:', e.message);
    });</code></pre>

                <table class="table-auto w-full border border-gray-300 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2 text-left">Giải pháp</th>
                            <th class="border px-4 py-2 text-left">Ưu điểm</th>
                            <th class="border px-4 py-2 text-left">Nhược điểm</th>
                            <th class="border px-4 py-2 text-left">Khi nào nên dùng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border px-4 py-2 font-semibold">Pusher</td>
                            <td class="border px-4 py-2">Cấu hình đơn giản, ổn định, có dashboard quản lý</td>
                            <td class="border px-4 py-2">Tốn phí sau giới hạn, phụ thuộc bên thứ 3</td>
                            <td class="border px-4 py-2">Dự án nhỏ, demo nhanh, không cần tự vận hành server</td>
                        </tr>
                        <tr>
                            <td class="border px-4 py-2 font-semibold">Laravel WebSockets</td>
                            <td class="border px-4 py-2">Tự host, tích hợp sâu với Laravel, miễn phí</td>
                            <td class="border px-4 py-2">Phải tự quản lý server, khó scale, dễ lỗi version</td>
                            <td class="border px-4 py-2">Dự án vừa, cần tiết kiệm, chủ động quản lý server</td>
                        </tr>
                        <tr>
                            <td class="border px-4 py-2 font-semibold">Redis</td>
                            <td class="border px-4 py-2">Hiệu năng cao, phù hợp scale lớn, tận dụng Redis sẵn có</td>
                            <td class="border px-4 py-2">Không hỗ trợ WebSocket trực tiếp, cần thêm server
                                Node/Socket.IO</td>
                            <td class="border px-4 py-2">Dự án lớn, nhiều server, cần hiệu năng và sẵn Redis</td>
                        </tr>
                    </tbody>
                </table>

                <hr class="my-5">
                <h5>7. Các loại Chat trong Laravel</h5>
                <p>Laravel Broadcasting hỗ trợ ba loại kênh để phục vụ cho nhiều mô hình chat:</p>
                <ul>
                    <li>
                        <strong>Public Channels</strong>:
                        Kênh công khai, bất kỳ ai cũng có thể lắng nghe.
                        <pre><code>public function broadcastOn()
{
    return new Channel('chat');
}</code></pre>
                    </li>
                    <li>
                        <strong>Private Channels</strong>:
                        Chỉ những người được phép mới lắng nghe, thường dùng để chat riêng.
                        <pre><code>public function broadcastOn()
{
    return new PrivateChannel('chat.user.' . $this->userId);
}</code></pre>
                        <p>Trong <code>routes/channels.php</code>:</p>
                        <pre><code>Broadcast::channel('chat.user.{userId}', function ($user, $userId) {
    return $user->id === (int) $userId;
});</code></pre>
                    </li>
                    <li>
                        <strong>Presence Channels</strong>:
                        Tương tự như Private Channels nhưng cho phép bạn theo dõi người dùng nào đang "join" kênh, rất
                        hữu ích trong ứng dụng chat nhóm để hiển thị danh sách online.

                        <p><strong>1. Event:</strong></p>
                        <pre><code>public function broadcastOn()
                    {
                        return new PresenceChannel('chat.room.' . $this->roomId);
                    }</code></pre>

                        <p><strong>2. Định nghĩa quyền truy cập trong <code>routes/channels.php</code>:</strong></p>
                        <pre><code>Broadcast::channel('chat.room.{roomId}', function ($user, $roomId) {
                        return ['id' => $user->id, 'name' => $user->name];
                    });</code></pre>
                        <p><i>Lưu ý:</i> Phải trả về thông tin user để Laravel quản lý danh sách người đang tham gia
                            kênh.</p>

                        <p><strong>3. Frontend: Lắng nghe sự kiện và danh sách user:</strong></p>
                        <pre><code>Echo.join(`chat.room.${roomId}`)
                        .here((users) => {
                            console.log('Người đang online:', users);
                        })
                        .joining((user) => {
                            console.log(`${user.name} đã vào phòng`);
                        })
                        .leaving((user) => {
                            console.log(`${user.name} đã rời phòng`);
                        })
                        .listen('MessageSent', (e) => {
                            console.log('Tin nhắn:', e.message);
                        });</code></pre>

                        <p><strong>Yêu cầu:</strong> Presence Channels cần người dùng đã đăng nhập (xác thực Laravel) và
                            đã cấu hình Laravel Echo chính xác.</p>
                    </li>

                </ul>

                <h5>8. Chat riêng với từng User</h5>
                <p>Để gửi tin nhắn riêng tư tới một user, bạn cần:</p>

                <ol>
                    <li><strong>Tạo Event sử dụng PrivateChannel:</strong>
                        <pre><code>public function broadcastOn()
{
    return new PrivateChannel('chat.user.' . $this->receiverId);
}</code></pre>
                    </li>

                    <li><strong>Frontend: lắng nghe sự kiện bằng Laravel Echo:</strong>
                        <pre><code>Echo.private(`chat.user.${userId}`)
    .listen('MessageSent', (e) => {
        console.log('Tin nhắn riêng:', e.message);
    });</code></pre>
                    </li>

                    <li><strong>Xác thực quyền truy cập kênh riêng:</strong>
                        <pre><code>Broadcast::channel('chat.user.{userId}', function ($user, $userId) {
    return $user->id === (int) $userId;
});</code></pre>
                    </li>

                    <li><strong>Gửi Event từ Controller kèm theo ID người nhận:</strong>
                        <pre><code>broadcast(new MessageSent($message, $receiverId));</code></pre>
                    </li>
                </ol>

                <p class="mt-3">Với cách này, mỗi user chỉ nhận được tin nhắn gửi cho mình thông qua
                    <code>PrivateChannel</code>, bảo mật và đúng chức năng "chat riêng tư".
                </p>

                <h5>9. Kết luận</h5>
                <p>Với Laravel Broadcasting kết hợp với Laravel Echo, bạn có thể dễ dàng triển khai các tính năng
                    real-time như chat, thông báo, cập nhật dữ liệu tự động mà không cần phải tải lại trang.</p>

                <p>Chúc bạn thành công trong việc triển khai tính năng real-time cho ứng dụng Laravel của mình!</p>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
