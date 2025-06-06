<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\OrderShippedTest;
use App\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Events\MessageSent;


class DemoController extends Controller
{
    public function index()
    {
        return view('demo.index');
    }

    public function queueDemo()
    {

        // php artisan queue:work

        $email = "rooney.aws7292@gmail.com";
        for ($i = 0; $i < 3; $i++) {
            SendEmailJob::dispatch($email); // Đưa job vào hàng đợi
        }
        return response()->json(['message' => 'Gửi Email rooney.aws7292@gmail.com đã được đưa vào hàng đợi!']);
    }


    public function eventDemo()
    {

        // Lấy thông tin đơn hàng
        // $order = Order::find($orderId);

        // // Giả sử quá trình giao hàng thành công
        // $order->status = 'shipped';
        // $order->save();

        // // Phát sự kiện khi đơn hàng đã giao
        // event(new OrderShippedTest($order));

        // Mở rộng dễ dàng

        // Khi dùng Event - Listener, bạn có thể dễ dàng thêm nhiều Listener mà không cần thay đổi code gọi sự kiện.

        // Ví dụ: Khi OrderShippedTest xảy ra, ngoài việc gửi email, bạn có thể:

        // Gửi thông báo SMS

        // Ghi log vào database

        // Cập nhật trạng thái đơn hàng

        // Chỉ cần thêm Listener mà không cần sửa code phần gọi sự kiện.

        // protected $listen = [
        //     OrderShippedTest::class => [
        //         SendOrderShippedEmail::class,
        //         SendOrderShippedSMS::class,
        //         UpdateOrderStatus::class,
        //     ],
        // ];
        

        event(new OrderShippedTest("Order123"));
        return response()->json(['message' => 'Sự kiện đã được kích hoạt!, đã gửi mail cho rooney.aws7292@gmail.com']);
    }

    public function authDemo()
    {
        return Auth::check() ? redirect('dashboard') : redirect('/login');
    }


    public function broadcastingDemo()
    {
        return view('demo.broadcasting');
    }

    public function messageForm()
    {
        return view('demo.message'); // form gửi
    }

    public function messageSend(Request $request)
    {
        $message = $request->input('message', '');
        if (!empty($message)) {
            broadcast(new MessageSent($message))->toOthers();
        }
        return response()->json(['status' => 'ok']);
    }
}
