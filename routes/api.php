<?php 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

// Route đăng nhập để nhận token
Route::post('/login', [ApiController::class, 'login']);

// Route được bảo vệ bằng Sanctum (yêu cầu Bearer Token)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
