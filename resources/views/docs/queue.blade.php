<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queue trong Laravel</title>
    <!-- Thêm Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-success text-white text-center">
                <h1>Queue trong Laravel</h1>
            </div>
            <div class="card-body">
                <h3>1. Giới Thiệu</h3>
                <p>
                    Laravel Queue cung cấp một phương pháp để xử lý công việc nền (background jobs), chẳng hạn như gửi
                    email, xử lý hình ảnh, hoặc các tác vụ mất thời gian, mà không làm chậm ứng dụng web của bạn. Queue
                    cho phép các công việc này được đưa vào hàng đợi và xử lý sau, trong khi người dùng có thể tiếp tục
                    sử dụng ứng dụng mà không bị ảnh hưởng.
                </p>
                <p>
                    Laravel hỗ trợ nhiều loại driver cho queue, như <strong>database</strong>, <strong>Redis</strong>,
                    <strong>SQS</strong>, <strong>Beanstalkd</strong>, <strong>sync</strong>, và <strong>null</strong>.
                    Bạn có thể chọn driver phù hợp với yêu cầu và môi trường của bạn.
                </p>

                <h3>2. Cấu Hình Queue</h3>
                <p>
                    Laravel sử dụng cấu hình trong file <code>config/queue.php</code> để định nghĩa các driver cho
                    queue. Mặc định, Laravel cung cấp một số driver, bạn có thể thay đổi các cấu hình này theo nhu cầu
                    của mình.
                </p>
                <pre><code>'default' => env('QUEUE_CONNECTION', 'sync'),</code></pre>
                <p>
                    Laravel hỗ trợ nhiều driver khác nhau, bao gồm:
                <ul>
                    <li><strong>sync</strong>: Driver mặc định để xử lý các công việc ngay lập tức.</li>
                    <li><strong>database</strong>: Đưa các job vào bảng <code>jobs</code> trong cơ sở dữ liệu để xử lý.
                    </li>
                    <li><strong>redis</strong>: Sử dụng Redis để xử lý các job.</li>
                    <li><strong>beanstalkd</strong>: Driver cho Beanstalkd.</li>
                    <li><strong>sqs</strong>: Sử dụng Amazon SQS để xử lý job.</li>
                    <li><strong>null</strong>: Không thực hiện công việc (chỉ dùng khi cần bỏ qua).</li>
                </ul>
                </p>

                <h3>3. Tạo Job</h3>
                <p>
                    Để tạo một job trong Laravel, bạn có thể sử dụng Artisan command:
                </p>
                <pre><code>php artisan make:job SendEmailJob</code></pre>
                <p>
                    Lệnh trên sẽ tạo một lớp job mới trong thư mục <code>app/Jobs</code>. Sau đó, bạn có thể thêm logic
                    vào job đó. Ví dụ trong <code>SendEmailJob</code>, bạn có thể thêm các thuộc tính và phương thức như
                    sau:
                </p>
                <pre><code>
namespace App\Jobs;

use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;

class SendEmailJob extends Job
{
    public $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function handle()
    {
        Mail::to($this->email)->send(new SendEmail());
    }
}
                </code></pre>

                <h3>4. Dispatch Job</h3>
                <p>
                    Sau khi tạo một job, bạn có thể dispatch job đó vào hàng đợi bằng cách sử dụng phương thức
                    <code>dispatch</code>:
                </p>
                <pre><code>SendEmailJob::dispatch($email);</code></pre>
                <p>
                    Bạn có thể gửi job vào một queue cụ thể bằng cách thêm phương thức <code>onQueue</code>:
                </p>
                <pre><code>SendEmailJob::dispatch($email)->onQueue('emails');</code></pre>

                <h3>5. Chạy Worker</h3>
                <p>
                    Để xử lý các job trong hàng đợi, bạn cần chạy một worker. Bạn có thể chạy worker bằng lệnh sau:
                </p>
                <pre><code>php artisan queue:work</code></pre>
                <p>
                    Lệnh này sẽ bắt đầu một worker và xử lý các job trong hàng đợi. Nếu bạn muốn worker chạy liên tục,
                    bạn có thể sử dụng lệnh sau:
                </p>
                <pre><code>php artisan queue:listen</code></pre>
                <p>
                    Nếu bạn muốn chỉ xử lý các job trong một queue cụ thể, bạn có thể sử dụng lệnh:
                </p>
                <pre><code>php artisan queue:work --queue=emails</code></pre>

                <h3>6. Cấu Hình Retry và Failures</h3>
                <p>
                    Laravel hỗ trợ khả năng thử lại job nếu nó gặp lỗi. Bạn có thể chỉ định số lần retry trong file job
                    của mình:
                </p>
                <pre><code>public $tries = 3;</code></pre>
                <p>
                    Cấu hình này sẽ chỉ định số lần mà job có thể được thử lại nếu gặp lỗi. Nếu job tiếp tục thất bại
                    sau số lần thử, nó sẽ được đánh dấu là thất bại và bạn có thể lưu vào bảng <code>failed_jobs</code>.
                </p>

                <h3>7. Xử Lý Các Job Thất Bại</h3>
                <p>
                    Bạn có thể cấu hình các job thất bại và xử lý chúng bằng cách sử dụng bảng <code>failed_jobs</code>.
                    Laravel sẽ tự động lưu các job thất bại vào bảng này và bạn có thể xem chi tiết về các job thất bại.
                </p>
                <p>
                    Để cấu hình việc lưu trữ các job thất bại, bạn cần chạy migration:
                </p>
                <pre><code>php artisan queue:failed-table</code></pre>
                <p>
                    Sau đó, chạy lệnh migration để tạo bảng:
                </p>
                <pre><code>php artisan migrate</code></pre>
                <p>
                    Bạn có thể sử dụng lệnh để xem danh sách các job thất bại:
                </p>
                <pre><code>php artisan queue:failed</code></pre>

                <h3>8. Cấu hình Supervisor cho Laravel Queue</h3>
                <p><strong>Supervisor</strong> giúp Laravel queue worker chạy liên tục và tự khởi động lại nếu gặp lỗi.
                    Đây là cách cấu hình cơ bản:</p>

                <h6>Cấu hình Supervisor:</h6>
                <pre><code>[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=your-username
numprocs=8
redirect_stderr=true
stdout_logfile=/path/to/your/logs/laravel-worker.log
</code></pre>

                <ul>
                    <li><code>command</code>: Lệnh chạy queue worker của Laravel.</li>
                    <li><code>--sleep=3</code>: Nếu không có job, worker sẽ nghỉ 3 giây trước khi thử lại.</li>
                    <li><code>--tries=3</code>: Job sẽ thử tối đa 3 lần trước khi bị đưa vào bảng
                        <code>failed_jobs</code>.</li>
                    <li><code>numprocs=8</code>: Chạy song song 8 worker.</li>
                    <li><code>user</code>: Tên người dùng hệ thống chạy lệnh (ví dụ: <code>www-data</code>).</li>
                    <li><code>stdout_logfile</code>: File log để giám sát worker.</li>
                </ul>

                <h6>Ví dụ thực tế:</h6>
                <pre><code>[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/laravel/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/var/www/laravel/storage/logs/laravel-worker.log
</code></pre>

                <h6>Đặt file cấu hình:</h6>
                <pre><code>/etc/supervisor/conf.d/laravel-worker.conf</code></pre>

                <h6>Kích hoạt Supervisor:</h6>
                <pre><code>sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*</code></pre>

                <h3>9. Hẹn Lịch Job (Scheduling)</h3>
                <p>
                    Laravel cung cấp hệ thống lập lịch mạnh mẽ thông qua <code>Task Scheduling</code>. Bạn có thể định
                    nghĩa các job hoặc command sẽ được thực thi theo khoảng thời gian định sẵn.
                </p>
                <p>
                    Để sử dụng tính năng này, bạn chỉ cần định nghĩa logic lập lịch trong phương thức
                    <code>schedule</code> của file <code>app/Console/Kernel.php</code>.
                </p>
                <pre><code>
protected function schedule(Schedule $schedule)
{
    $schedule->job(new SendEmailJob('example@example.com'))->everyMinute();
}
</code></pre>
                <p>
                    Các phương thức lập lịch phổ biến bao gồm:
                <ul>
                    <li><code>->everyMinute()</code></li>
                    <li><code>->hourly()</code></li>
                    <li><code>->dailyAt('13:00')</code></li>
                    <li><code>->weeklyOn(1, '8:00')</code> (Thứ Hai lúc 8h sáng)</li>
                    <li><code>->monthly()</code></li>
                </ul>
                </p>
                <p>
                    Để tính năng này hoạt động, bạn cần thêm dòng sau vào crontab server của bạn:
                </p>
                <pre><code>* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1</code></pre>
                <p>
                    Laravel sẽ chạy lệnh này mỗi phút và xử lý các job được lên lịch tự động.
                </p>

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
