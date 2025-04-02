<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->from('viet.marrish.test@gmail.com')
                    ->subject('Đơn hàng của bạn đã được gửi đi')
                    ->view('emails.order_shipped')
                    ->with('data', $this->data);
    }
}

