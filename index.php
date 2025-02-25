<?php
require_once './vendor/autoload.php';
require_once './app/helpers/index.php';

use Dotenv\Dotenv;
use App\Models\User;
use Bramus\Router\Router;
use eftec\bladeone\BladeOne;
use App\Middleware\AuthMiddleware;
use App\Controllers\AuthController;
use App\Controllers\UserController;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$router = new Router();

// Cấu hình BladeOne với đường dẫn đúng
$views = __DIR__ . '/templates'; // Kiểm tra xem có đúng vị trí không
$cache = __DIR__ . '/cache';
$blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
$authController = new AuthController($blade);

// Đảm bảo phương thức HTTP tồn tại
if (!isset($_SERVER['REQUEST_METHOD'])) {
    $_SERVER['REQUEST_METHOD'] = 'GET';
}
// Định tuyến trang chủ
$router->before('GET|POST', '/ASM/.*', function () {
    AuthMiddleware::handle();
});
$router->get('/ASM/user', function () use ($blade) {
    $controller = new UserController($blade);
    $controller->index();
});
$router->get('/ASM/user/create', function () use ($blade) {
    $controller = new UserController($blade);
    $controller->create();
});

$router->post('/ASM/user/store', function () use ($blade) {
    $controller = new UserController($blade);
    $controller->store();
});
$router->get('/ASM/user/edit/(\d+)', function ($id) use ($blade) {
    $controller = new UserController($blade);
    $controller->edit($id);
});

$router->post('/ASM/user/update/(\d+)', function ($id) use ($blade) {
    $controller = new UserController($blade);
    $controller->update($id);
});
$router->get('/ASM/user/delete/(\d+)', function($id) use ($blade) {
    $controller = new UserController($blade);
    $controller->delete($id);
});

// ✅ Hiển thị trang đăng nhập
$router->get('/login', function () use ($authController) {
    $authController->showLoginForm();
});

// ✅ Xử lý đăng nhập
$router->post('/login', function () use ($authController) {
    $authController->login();
});

// ✅ Xử lý đăng xuất
$router->get('/logout', function () use ($authController) {
    $authController->logout();
});

$router->run();
