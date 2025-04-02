<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events trong Laravel</title>
    <!-- Thêm Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h1>Events trong Laravel</h1>
            </div>
            <div class="card-body">
                <h3>1. Giới Thiệu</h3>
                <p>
                    Laravel cung cấp hệ thống sự kiện (Event) mạnh mẽ giúp bạn có thể xử lý các hành động không đồng bộ hoặc tách rời các phần logic của ứng dụng. Ví dụ: gửi email sau khi người dùng đăng ký hoặc ghi log khi đơn hàng được đặt thành công.
                </p>

                <h3>2. Tạo Event và Listener</h3>
                <p>
                    Bạn có thể tạo một event bằng Artisan command:
                </p>
                <pre><code>php artisan make:event OrderShipped</code></pre>
                <p>
                    Tương tự, để tạo một listener:
                </p>
                <pre><code>php artisan make:listener SendOrderShippedNotification --event=OrderShipped</code></pre>

                <h3>3. Định Nghĩa Event</h3>
                <p>
                    Mỗi event được lưu trong <code>app/Events</code> và chứa các thuộc tính cần thiết để truyền dữ liệu:
                </p>
                <pre><code>
namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class OrderShipped
{
    use Dispatchable;
    
    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }
}
                </code></pre>

                <h3>4. Định Nghĩa Listener</h3>
                <p>
                    Mỗi listener được lưu trong <code>app/Listeners</code> và xử lý sự kiện khi nó được kích hoạt:
                </p>
                <pre><code>
namespace App\Listeners;

use App\Events\OrderShipped;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderShippedNotification implements ShouldQueue
{
    public function handle(OrderShipped $event)
    {
        // Gửi thông báo hoặc email
    }
}
                </code></pre>

                <h3>5. Đăng Ký Event và Listener</h3>
                <p>
                    Bạn có thể đăng ký event và listener trong <code>app/Providers/EventServiceProvider.php</code>:
                </p>
                <pre><code>
protected $listen = [
    OrderShipped::class => [
        SendOrderShippedNotification::class,
    ],
];
                </code></pre>

                <h3>6. Kích Hoạt Event</h3>
                <p>
                    Để kích hoạt event, bạn có thể gọi:
                </p>
                <pre><code>event(new OrderShipped($order));</code></pre>

                <h3>7. Xử Lý Queue trong Listener</h3>
                <p>
                    Để đẩy listener vào hàng đợi, hãy triển khai <code>ShouldQueue</code>:
                </p>
                <pre><code>class SendOrderShippedNotification implements ShouldQueue</code></pre>

                <h3>8. Xem Danh Sách Event</h3>
                <p>
                    Bạn có thể chạy lệnh sau để kiểm tra tất cả event và listener đã đăng ký:
                </p>
                <pre><code>php artisan event:list</code></pre>
            </div>
            <div class="card-footer text-center">
                <small class="text-muted">© 2025 Laravel Demo</small>
            </div>
        </div>
    </div>

    <!-- Thêm Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
