<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendEmailJob;

class QueueController extends Controller
{
    // php artisan queue:work

    
    public function sendEmail()
    {
        $email = "rooney.aws7292@gmail.com";
        for ($i = 0; $i < 10; $i++) {
            SendEmailJob::dispatch($email); // Đưa job vào hàng đợi
        }
        return response()->json(['message' => 'Email đã được đưa vào hàng đợi!']);
    }
}   