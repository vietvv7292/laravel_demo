<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Chạy job gửi email vào queue lúc 8h sáng mỗi ngày
        $schedule->job(new \App\Jobs\SendEmailJob("rooney.aws7292@gmail.com"))->dailyAt('08:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }


    //  Laravel không tự chạy Task Schedule, bạn cần thêm cron job vào server.
    // Chạy lệnh:

    // crontab -e
    // Thêm dòng sau:
    // * * * * * php /path/to/your-project/artisan schedule:run >> /dev/null 2>&1
    // 📌 Thay /path/to/your-project bằng đường dẫn Laravel trên server.
    // 🚀 Cron job này sẽ kiểm tra và chạy các task đã lên lịch mỗi phút.
}
