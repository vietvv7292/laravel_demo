<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Laravel Broadcasting Guide</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: sans-serif;
            line-height: 1.6;
            padding: 2rem;
            background-color: #f8fafc;
            color: #333;
        }

        h1,
        h2,
        h3 {
            color: #1f2937;
        }

        code {
            background: #e2e8f0;
            padding: 2px 6px;
            border-radius: 4px;
        }

        pre {
            background: #e2e8f0;
            padding: 1rem;
            border-radius: 6px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }

        table,
        th,
        td {
            border: 1px solid #cbd5e1;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        .highlight {
            background: #d1fae5;
            padding: 4px 8px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <h1>Hướng Dẫn Laravel Broadcasting</h1>

    <h2>🔰 Giới thiệu chung</h2>
    <p>Laravel Broadcasting là hệ thống giúp bạn gửi dữ liệu real-time từ backend đến frontend một cách tự động, thường
        dùng trong các ứng dụng như:</p>
    <ul>
        <li>Chat realtime</li>
        <li>Thông báo (notifications) tức thời</li>
        <li>Cập nhật dữ liệu bảng/biểu đồ không cần reload</li>
        <li>Ứng dụng multiplayer hoặc collaborative</li>
    </ul>
    <p>Broadcasting kết hợp giữa sự kiện Laravel và WebSockets hoặc dịch vụ push để truyền dữ liệu.</p>

    <h2>📜 Ngày xưa trước khi có Broadcasting</h2>
    <p>Trước khi Broadcasting phổ biến, các hệ thống realtime thường phải:</p>
    <ul>
        <li>Dùng <strong>AJAX polling</strong>: gọi API liên tục mỗi vài giây để kiểm tra tin nhắn mới → tốn băng thông,
            server mệt.</li>
        <li>Dùng <strong>long-polling</strong>: giữ kết nối mở → khó scale.</li>
        <li>Viết tay bằng <strong>Socket.IO</strong> trong Node.js hoặc cài thêm server riêng để xử lý websocket.</li>
    </ul>
    <p><strong>Laravel Broadcasting</strong> giúp mọi thứ trở nên đơn giản, tích hợp luôn với hệ thống Event của
        Laravel.</p>

    {{-- Laravel Broadcasting Flow & Comparison --}}

    <section class="mt-5">
        <h2 class="text-xl font-bold mb-3">⚙️ Luồng hoạt động của Laravel Broadcasting</h2>
        <ol class="list-decimal list-inside space-y-1">
            <li>Client đăng ký kênh thông qua Laravel Echo (WebSocket).</li>
            <li>Server phát sinh event (ví dụ: gửi tin nhắn).</li>
            <li>Laravel sử dụng driver Broadcasting (Pusher, Soketi...) để đẩy sự kiện real-time.</li>
            <li>Client nhận dữ liệu ngay lập tức qua WebSocket, không cần hỏi lại server.</li>
        </ol>
    </section>

    <section class="mt-6">
        <h2 class="text-xl font-bold mb-3">🔁 So sánh với các phương pháp khác</h2>
        <table class="table-auto w-full border border-gray-300 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border border-gray-300 px-2 py-1 text-left">Phương pháp</th>
                    <th class="border border-gray-300 px-2 py-1 text-left">Đặc điểm</th>
                    <th class="border border-gray-300 px-2 py-1 text-left">Nhược điểm</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border px-2 py-1">AJAX Polling</td>
                    <td class="border px-2 py-1">Gọi API liên tục để kiểm tra tin nhắn mới</td>
                    <td class="border px-2 py-1">Tốn băng thông, tăng tải server, độ trễ cao</td>
                </tr>
                <tr>
                    <td class="border px-2 py-1">Long Polling</td>
                    <td class="border px-2 py-1">Giữ kết nối mở và phản hồi khi có dữ liệu mới</td>
                    <td class="border px-2 py-1">Trễ thấp hơn polling nhưng khó scale, tiêu tốn tài nguyên</td>
                </tr>
                <tr>
                    <td class="border px-2 py-1">Viết tay bằng Socket.IO</td>
                    <td class="border px-2 py-1">Dùng Node.js để tạo WebSocket server riêng</td>
                    <td class="border px-2 py-1">Mạnh mẽ nhưng cần hệ thống riêng, khó tích hợp với Laravel</td>
                </tr>
                <tr>
                    <td class="border px-2 py-1 font-semibold">Laravel Broadcasting</td>
                    <td class="border px-2 py-1">Tích hợp sẵn với Laravel, sử dụng WebSocket driver như Soketi</td>
                    <td class="border px-2 py-1">Tối ưu cho Laravel, dễ triển khai, nhưng cần queue và WebSocket server
                    </td>
                </tr>
            </tbody>
        </table>
    </section>

    <h1>📊 Bảng So Sánh Các Dịch Vụ Broadcasting</h1>
    <table>
        <thead>
            <tr>
                <th>Dịch Vụ</th>
                <th>Cài Đặt</th>
                <th>Chi Phí</th>
                <th>Đặc Điểm</th>
                <th>Dự Án Phù Hợp</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Pusher</td>
                <td>Dễ cài đặt qua Composer và NPM, hỗ trợ Laravel Echo.</td>
                <td>Miễn phí với giới hạn, có các gói trả phí theo mức độ sử dụng.</td>
                <td>Dịch vụ bên thứ ba, dễ tích hợp, nhưng có giới hạn miễn phí và yêu cầu kết nối internet.</td>
                <td>Dự án nhỏ và vừa, ứng dụng cần nhanh chóng triển khai mà không cần quản lý hạ tầng.</td>
            </tr>
            <tr>
                <td>Laravel WebSockets</td>
                <td>Cài đặt qua Composer, cần cấu hình server riêng.</td>
                <td>Miễn phí, nhưng cần tự host và bảo trì server.</td>
                <td>Self-hosted, không phụ thuộc vào dịch vụ bên ngoài, nhưng yêu cầu tài nguyên hệ thống để duy trì.
                </td>
                <td>Dự án trung bình và lớn, nơi mà bạn muốn kiểm soát hoàn toàn hạ tầng và cần tính linh hoạt.</td>
            </tr>
            <tr>
                <td>Soketi</td>
                <td>Cài đặt qua Docker, sử dụng cấu hình giống Pusher.</td>
                <td>Miễn phí, cần host riêng.</td>
                <td>Nhẹ, nhanh, dễ dàng triển khai và sử dụng ít tài nguyên hơn, tương thích với Pusher API.</td>
                <td>Dự án lớn, cần hiệu suất cao và khả năng mở rộng, yêu cầu ít tài nguyên hơn.</td>
            </tr>
            <tr>
                <td>Ably</td>
                <td>Cài đặt PHP SDK qua Composer, sử dụng Laravel Echo.</td>
                <td>Có gói miễn phí giới hạn, gói trả phí theo nhu cầu sử dụng.</td>
                <td>Dịch vụ đám mây, cung cấp tốc độ cao và hỗ trợ nhiều khu vực, dễ dàng mở rộng.</td>
                <td>Dự án nhỏ đến lớn, đặc biệt là ứng dụng di động hoặc toàn cầu cần hỗ trợ nhiều khu vực.</td>
            </tr>
            <tr>
                <td>Redis</td>
                <td>Cài đặt Redis server, cấu hình với Laravel Echo.</td>
                <td>Miễn phí, nhưng cần tự host Redis server.</td>
                <td>Phù hợp với các dự án có yêu cầu tùy chỉnh cao, cần cấu hình Redis và các công cụ khác như
                    Socket.IO.</td>
                <td>Dự án lớn, yêu cầu tính tùy chỉnh cao và không phụ thuộc vào dịch vụ đám mây bên ngoài.</td>
            </tr>
        </tbody>
    </table>


    <h2>🚀 1. Pusher – Dịch vụ bên thứ ba</h2>
    <p>Pusher là một dịch vụ bên thứ ba giúp bạn dễ dàng gửi dữ liệu real-time đến frontend qua WebSockets. Tuy nhiên,
        nó có giới hạn miễn phí và yêu cầu có kết nối internet.</p>

    <h3>Cài đặt:</h3>
    <p><strong>Cài đặt Pusher PHP Server:</strong></p>
    <pre><code>composer require pusher/pusher-php-server</code></pre>

    <p><strong>Cài đặt Laravel Echo và Pusher JS:</strong></p>
    <pre><code>npm install --save laravel-echo pusher-js</code></pre>

    <h3>Cấu hình .env:</h3>
    <p>Trong tệp .env, cấu hình các thông tin liên quan đến Pusher:</p>
    <pre><code>
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=mt1
    </code></pre>

    <h3>Cài đặt cấu hình trong config/broadcasting.php:</h3>
    <p>Laravel sẽ tự động đọc thông tin từ .env để cấu hình Pusher. Bạn có thể tìm và chỉnh sửa phần sau trong tệp
        config/broadcasting.php:</p>
    <pre><code>
'connections' => [
    'pusher' => [
        'driver' => 'pusher',
        'key' => env('PUSHER_APP_KEY'),
        'secret' => env('PUSHER_APP_SECRET'),
        'app_id' => env('PUSHER_APP_ID'),
        'options' => [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true,
        ],
    ],
],
    </code></pre>

    <h3>Tạo Event và Broadcast:</h3>
    <p>Tạo một Event và broadcast nó:</p>
    <pre><code>php artisan make:event MessageSent</code></pre>

    <p>Trong MessageSent, bạn sẽ thực hiện việc broadcast như sau:</p>
    <pre><code>
public function broadcastOn()
{
    return new Channel('chat');
}
    </code></pre>

    <h3>Frontend:</h3>
    <p>Sử dụng Laravel Echo và Pusher JS trên frontend để lắng nghe các sự kiện:</p>
    <pre><code>
Echo.channel('chat')
    .listen('MessageSent', (event) => {
        console.log(event.message);
    });
    </code></pre>

    <h2>🧩 2. Laravel WebSockets – Tự host như Pusher</h2>
    <p>Laravel WebSockets là một gói tự host WebSocket như Pusher, cho phép bạn sử dụng WebSockets mà không cần phải dựa
        vào dịch vụ bên ngoài.</p>

    <h3>Cài đặt:</h3>
    <p><strong>Cài đặt Laravel WebSockets:</strong></p>
    <pre><code>composer require beyondcode/laravel-websockets</code></pre>

    <p><strong>Publish cấu hình và migration:</strong></p>
    <pre><code>php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="migrations"</code></pre>
    <pre><code>php artisan migrate</code></pre>

    <p><strong>Publish cấu hình WebSockets:</strong></p>
    <pre><code>php artisan vendor:publish --tag="websockets-config"</code></pre>

    <h3>Cấu hình WebSockets trong config/websockets.php:</h3>
    <p>Chỉnh sửa tệp config/websockets.php để phù hợp với yêu cầu của bạn:</p>
    <pre><code>
'apps' => [
    [
        'id' => env('APP_ID'),
        'name' => env('APP_NAME'),
        'key' => env('APP_KEY'),
        'secret' => env('APP_SECRET'),
        'path' => env('APP_PATH'),
    ],
],
    </code></pre>

    <h3>Chạy WebSockets Server:</h3>
    <p>Khởi chạy WebSockets server với lệnh:</p>
    <pre><code>php artisan websockets:serve</code></pre>

    <h3>Frontend:</h3>
    <p>Sử dụng Laravel Echo để kết nối tới WebSockets server:</p>
    <pre><code>
Echo.channel('chat')
    .listen('MessageSent', (event) => {
        console.log(event.message);
    });
    </code></pre>

    <h2>⚡ 3. Soketi – Tự host, nhẹ, siêu nhanh</h2>
    <p>Soketi là một WebSocket server nhẹ, cực kỳ nhanh và tương thích với API của Pusher. Nó được thiết kế để sử dụng
        ít tài nguyên hơn và dễ dàng triển khai.</p>

    <h3>Cài đặt:</h3>
    <p><strong>Tạo Docker container cho Soketi:</strong></p>
    <pre><code>
soketi:
  image: 'quay.io/soketi/soketi:1.5-16-debian'
  ports:
    - "6001:6001"
  environment:
    SOKETI_DEBUG: '1'
    SOKETI_METRICS_ENABLED: '1'
    SOKETI_DEFAULT_APP_ID: 'local'
    SOKETI_DEFAULT_APP_KEY: 'local'
    SOKETI_DEFAULT_APP_SECRET: 'local'
    </code></pre>

    <p><strong>Chạy Docker container:</strong></p>
    <pre><code>docker-compose up -d</code></pre>

    <h3>Cấu hình .env:</h3>
    <pre><code>
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=local
PUSHER_APP_KEY=local
PUSHER_APP_SECRET=local
PUSHER_APP_CLUSTER=mt1
    </code></pre>

    <h3>Frontend:</h3>
    <p>Sử dụng Laravel Echo với cấu hình Pusher API như sau:</p>
    <pre><code>
Echo.channel('chat')
    .listen('MessageSent', (event) => {
        console.log(event.message);
    });
    </code></pre>

    <h2>☁️ 4. Ably – Cloud Broadcaster</h2>
    <p>Ably là một dịch vụ đám mây cung cấp khả năng phát sóng dữ liệu real-time. Đây là một giải pháp đám mây mạnh mẽ
        với tốc độ cao và hỗ trợ nhiều khu vực.</p>

    <h3>Cài đặt:</h3>
    <p><strong>Cài đặt Ably PHP SDK:</strong></p>
    <pre><code>composer require ably/ably-php</code></pre>

    <h3>Cấu hình .env:</h3>
    <pre><code>
BROADCAST_DRIVER=ably
ABLY_KEY=your-ably-key
    </code></pre>

    <h3>Cấu hình trong config/broadcasting.php:</h3>
    <pre><code>
'connections' => [
    'ably' => [
        'driver' => 'ably',
        'key' => env('ABLY_KEY'),
    ],
],
    </code></pre>

    <h3>Frontend:</h3>
    <p>Sử dụng Laravel Echo để kết nối tới Ably:</p>
    <pre><code>
Echo.channel('chat')
    .listen('MessageSent', (event) => {
        console.log(event.message);
    });
    </code></pre>

    <h2>🔁 5. Redis – Custom WebSocket Broadcaster</h2>
    <p>Redis có thể được sử dụng như một công cụ để truyền tải sự kiện qua WebSockets, kết hợp với các thư viện như
        Socket.IO.</p>

    <h3>Cài đặt:</h3>
    <p><strong>Cài đặt Redis:</strong></p>
    <pre><code>composer require predis/predis</code></pre>

    <h3>Cấu hình .env:</h3>
    <pre><code>
BROADCAST_DRIVER=redis
    </code></pre>

    <h3>Cấu hình Redis trong config/database.php:</h3>
    <p>Kiểm tra lại phần cấu hình Redis trong tệp này để đảm bảo rằng Redis được cài đặt đúng.</p>

    <h3>Chạy Redis Server:</h3>
    <p>Đảm bảo Redis đang chạy trên hệ thống của bạn.</p>

    <h3>Frontend:</h3>
    <p>Sử dụng Laravel Echo để lắng nghe sự kiện từ Redis:</p>
    <pre><code>
Echo.channel('chat')
    .listen('MessageSent', (event) => {
        console.log(event.message);
    });
    </code></pre>

    <h2>🔔 Lưu Ý Khi Sử Dụng Queue Trong Broadcasting</h2>
    <p>Trong Laravel Broadcasting, nếu bạn sử dụng <strong>queue</strong> để xử lý các sự kiện, bạn cần chắc chắn rằng
        các <strong>queue worker</strong> đang chạy để xử lý các job (sự kiện) và gửi dữ liệu qua WebSockets. Nếu bạn
        không chạy queue worker, các sự kiện broadcasting có thể không được xử lý và phát đi.</p>

    <h3>Các trường hợp cần chạy <code>queue:work</code>:</h3>
    <ul>
        <li><strong>Redis:</strong> Nếu bạn sử dụng Redis để broadcasting, bạn cần chạy queue worker để xử lý các sự
            kiện qua queue.</li>
        <li><strong>WebSockets với queue:</strong> Nếu bạn cấu hình broadcasting qua queue (dù là Laravel WebSockets hay
            Soketi), bạn cần chạy queue worker để đảm bảo các sự kiện được xử lý và phát đi.</li>
        <li><strong>Pusher hoặc Ably với queue:</strong> Nếu bạn sử dụng dịch vụ Pusher hoặc Ably với queue, bạn cũng
            cần chạy queue worker để đảm bảo các sự kiện được phát đi đúng thời gian.</li>
    </ul>

    <h3>Cách chạy queue worker:</h3>
    <p>Để chạy queue worker, sử dụng lệnh sau trong terminal:</p>
    <pre><code>php artisan queue:work</code></pre>

    <p>Lệnh này sẽ bắt đầu xử lý các job trong queue, bao gồm cả các sự kiện broadcasting.</p>

    <p>Đảm bảo rằng queue worker của bạn đang chạy liên tục (hoặc sử dụng <code>queue:listen</code> nếu cần), để các sự
        kiện được gửi đi ngay khi có sự thay đổi.</p>



    <h1>💬 Các Loại Chat Trong Laravel Broadcasting</h1>
    <p>Laravel hỗ trợ nhiều loại kênh cho việc chat realtime, bao gồm:</p>

    <h3>1. Public Channel</h3>
    <p>Kênh công khai, mọi người đều có thể tham gia mà không cần xác thực. Đây là lựa chọn phù hợp khi bạn muốn gửi
        thông tin cho tất cả người dùng.</p>
    <pre><code>broadcast(new MessageSent($message));</code></pre>
    <p>Ví dụ: Gửi một thông điệp lên kênh công khai:</p>
    <pre><code>
public function broadcastOn()
{
    return new Channel('chat');
}
</code></pre>

    <h3>2. Private Channel</h3>
    <p>Kênh riêng tư, chỉ những người đã được xác thực mới có thể tham gia. Đây là lựa chọn phù hợp khi bạn muốn gửi
        thông tin cho một nhóm người dùng cụ thể, ví dụ như chat giữa các người dùng đã đăng nhập.</p>
    <pre><code>broadcast(new MessageSent($message))->to('private-chat.'.$userId);</code></pre>
    <p>Ví dụ: Gửi một thông điệp lên kênh riêng tư cho một người dùng:</p>
    <pre><code>
public function broadcastOn()
{
    return new Channel('private-chat.' . $this->user->id);
}
</code></pre>

    <h3>3. Presence Channel</h3>
    <p>Kênh cho phép theo dõi người dùng online và offline. Thông qua Presence Channel, bạn có thể theo dõi được trạng
        thái của người dùng, xem ai đang online hoặc offline. Đây là lựa chọn tuyệt vời cho các ứng dụng yêu cầu hiển
        thị trạng thái người dùng trong thời gian thực.</p>
    <pre><code>broadcast(new MessageSent($message))->toPresence('presence-chat');</code></pre>
    <p>Ví dụ: Gửi thông điệp lên kênh Presence và kiểm tra ai đang online:</p>
    <pre><code>
public function broadcastOn()
{
    return new PresenceChannel('presence-chat');
}
</code></pre>
    <p>Để theo dõi người dùng online và offline, bạn cần sử dụng các sự kiện như `PresenceChannel` để lắng nghe trạng
        thái của người dùng:</p>
    <pre><code>
Echo.join('presence-chat')
    .here((users) => {
        console.log(users);
    })
    .joining((user) => {
        console.log(user.name + ' đã tham gia');
    })
    .leaving((user) => {
        console.log(user.name + ' đã rời');
    });
</code></pre>

    <h3>4. Một Số Mẹo Và Lưu Ý</h3>
    <ul>
        <li>Đảm bảo bạn đã cấu hình đúng trong file <code>.env</code> và <code>config/broadcasting.php</code> cho từng
            loại kênh.</li>
        <li>Với các kênh Private và Presence, bạn cần xác thực người dùng trước khi cho phép họ tham gia vào kênh đó.
        </li>
        <li>Để giảm thiểu lưu lượng băng thông và tăng hiệu suất, bạn nên cẩn trọng khi sử dụng các kênh public cho các
            dữ liệu nhạy cảm hoặc cần bảo mật.</li>
    </ul>

</body>

</html>
