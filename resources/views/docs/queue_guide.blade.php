<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hướng Dẫn Queue Laravel</title>
    <!-- Thêm Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h1>Hướng Dẫn Queue trong Laravel</h1>
            </div>
            <div class="card-body">
                <p class="lead text-center">Hướng dẫn cấu hình và sử dụng Queue trong Laravel để xử lý công việc nền một cách hiệu quả.</p>
                
                <h4 class="mt-4">1. Cài đặt môi trường</h4>
                <p>
                    Trước tiên, bạn cần cấu hình cơ sở dữ liệu hoặc hệ thống lưu trữ mà Laravel sẽ sử dụng để lưu trữ các công việc trong Queue. Laravel hỗ trợ nhiều driver như <strong>database</strong>, <strong>Redis</strong>, <strong>SQS</strong>, <strong>Beanstalkd</strong>, <strong>sync</strong>, v.v.
                </p>

                <h5 class="mt-3">a) Cấu hình trong <code>.env</code></h5>
                <p>Mở tệp <code>.env</code> và cấu hình các thông số kết nối cho hệ thống queue mà bạn muốn sử dụng. Ví dụ, sử dụng MySQL cho Queue:</p>
                <pre><code>QUEUE_CONNECTION=database</code></pre>

                <h5 class="mt-3">b) Cấu hình trong <code>config/queue.php</code></h5>
                <p>Mở tệp cấu hình <code>config/queue.php</code> và chọn driver tương ứng:</p>

                <h4 class="mt-4">2. Tạo Job để thêm vào Queue</h4>
                <p>Sau khi cấu hình xong, bạn có thể tạo một Job để đưa vào hàng đợi.</p>

                <h5 class="mt-3">a) Tạo Job</h5>
                <p>Để tạo một Job, bạn có thể sử dụng lệnh Artisan:</p>
                <pre><code>php artisan make:job SendEmailJob</code></pre>
                <p>Sau khi tạo xong, mở tệp <code>app/Jobs/SendEmailJob.php</code> và thêm logic gửi email vào phương thức <code>handle</code>.</p>

                <h5 class="mt-3">b) Đưa Job vào hàng đợi</h5>
                <p>Để đưa job vào hàng đợi, bạn có thể sử dụng phương thức <code>dispatch()</code>:</p>
                <pre><code>SendEmailJob::dispatch($email);</code></pre>
                <p>Hoặc sử dụng hàng đợi cụ thể như sau:</p>
                <pre><code>SendEmailJob::dispatch($email)->onQueue('emails');</code></pre>

                <h4 class="mt-4">3. Xử lý Queue</h4>
                <p>Để bắt đầu xử lý các công việc trong Queue, bạn cần chạy lệnh Artisan sau:</p>
                <pre><code>php artisan queue:work</code></pre>
                <p>Lệnh này sẽ xử lý các job trong hàng đợi và chạy các tác vụ đã được đưa vào Queue.</p>

                <h4 class="mt-4">4. Giới hạn Retry và Xử lý Lỗi</h4>
                <p>Laravel hỗ trợ việc retry (thử lại) các job khi gặp lỗi. Bạn có thể cấu hình số lần retry trong <code>config/queue.php</code> hoặc khi dispatch job:</p>
                <pre><code>SendEmailJob::dispatch($email)->onQueue('emails')->delay(now()->addMinutes(10));</code></pre>

                <h5 class="mt-3">Cài đặt retry tự động:</h5>
                <pre><code>php artisan queue:work --tries=3</code></pre>

                <h4 class="mt-4">5. Kiểm tra các Job thất bại</h4>
                <p>Trong trường hợp job thất bại, bạn có thể kiểm tra các job đã thất bại bằng lệnh:</p>
                <pre><code>php artisan queue:failed</code></pre>

                <p>Cảm ơn bạn đã tham khảo hướng dẫn về Queue trong Laravel!</p>
            </div>
        </div>
    </div>

    <!-- Thêm Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
