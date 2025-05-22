<?php
namespace App\Controllers;

use App\Models\User;
use eftec\bladeone\BladeOne;

class AuthController
{
    protected $blade;
    protected $userModel;

    public function __construct(BladeOne $blade)
    {
        $this->blade = $blade;
        $this->userModel = new User();
    }

    // ✅ Hiển thị trang đăng nhập
    public function showLoginForm()
    {
        echo $this->blade->run('login');
    }

    // ✅ Xử lý đăng nhập
    public function login()
    {
        session_start();
       
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        print_r($password);
        if (empty($email) || empty($password)) {
            echo $this->blade->run('login', ['errors' => ['Vui lòng nhập thông tin đầy đủ!']]);

            return;
        }

        // Kiểm tra user có tồn tại không
        $user = $this->userModel->getUserByEmail($email);
        if (!$user) {
            echo $this->blade->run('login', ['errors' => ['Bạn không có quyền truy cập']]);
            return;
        }

        // Kiểm tra mật khẩu (dùng MD5 hoặc password_verify nếu dùng password_hash)
        if ($user['password'] !== md5($password)) {
            echo $this->blade->run('login', ['errors' => ['Email hoặc mật khẩu không đúng']]);
            return;
        }

        // Đăng nhập thành công, lưu session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        header("Location: /admin/user"); // Chuyển hướng về trang quản lý user
        exit;
    }

    // ✅ Xử lý đăng xuất
    public function logout()
    {
        session_start();
        session_destroy();
        header("Location: /login");
        exit;
    }
}
