<?php

namespace App\Listeners;

use App\Events\OrderShippedTest;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShipped;

class SendOrderShippedEmail
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\OrderShippedTest  $event
     * @return void
     */
    public function handle(OrderShippedTest $event)
    {
        // Gửi email khi sự kiện OrderShippedTest được kích hoạt
        $data = [
            'name' => 'Test Events',
            'order_id' => '123456'
        ];
        Mail::to('rooney.aws7292@gmail.com')->send(new OrderShipped($data));
    }
}
