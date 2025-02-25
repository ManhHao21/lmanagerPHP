<?php
namespace App\Middleware;

class AuthMiddleware
{
    public static function handle()
    {
        session_start();
        
        // Kiểm tra nếu user chưa đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }
    }
}
