<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\OrderShipped;
use Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function handle()
    {
        // Giả lập gửi email (có thể thay bằng Mail::to($this->email)->send(...))
        \Log::info("Gửi email đến: " . $this->email);

        // Gửi email thực tế
        $data = [
            'name' => 'Test Queue',
            'order_id' => '123456'
        ];
        Mail::to($this->email)->send(new OrderShipped($data));
    }
}

