<?php
require_once './vendor/autoload.php';
require_once './app/helpers/index.php';

use Dotenv\Dotenv;
use App\Models\User;
use Bramus\Router\Router;
use eftec\bladeone\BladeOne;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$router = new Router();

// Cấu hình BladeOne với đường dẫn đúng
$views = __DIR__ . '/templates'; // Kiểm tra xem có đúng vị trí không
$cache = __DIR__ . '/cache';
$blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

// Đảm bảo phương thức HTTP tồn tại
if (!isset($_SERVER['REQUEST_METHOD'])) {
    $_SERVER['REQUEST_METHOD'] = 'GET';
}
// Định tuyến trang chủ
$router->get('/ASM/user', function () use ($blade) {
    $userModel = new User(); // Khởi tạo model User
    $users = $userModel->getAllUsers(); // Gọi function lấy danh sách người dùng

    // Render view users.index.blade.php và truyền danh sách user vào
    echo $blade->run('users.index', ['users' => $users]);
});


$router->run();
