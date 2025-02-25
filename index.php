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
use App\Controllers\ProductController;
use App\Controllers\CategoryController;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$router = new Router();

// Cấu hình BladeOne với đường dẫn đúng
$views = __DIR__ . '/view'; // Kiểm tra xem có đúng vị trí không
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
$router->get('/', function () {
    header("Location: /ASM/user");
    exit;
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

// Thêm sản danh mục sản phẩm
$router->get('/ASM/category', function () use ($blade) {
    $controller = new CategoryController($blade);
    $controller->index();
});
$router->get('/ASM/category/create', function () use ($blade) {
    $controller = new CategoryController($blade);
    $controller->create();
});

$router->post('/ASM/category/store', function () use ($blade) {
    $controller = new CategoryController($blade);
    $controller->store();
});
$router->get('/ASM/category/edit/(\d+)', function ($id) use ($blade) {
    $controller = new CategoryController($blade);
    $controller->edit($id);
});

$router->post('/ASM/category/update/(\d+)', function ($id) use ($blade) {
    $controller = new CategoryController($blade);
    $controller->update($id);
});
$router->get('/ASM/category/delete/(\d+)', function($id) use ($blade) {
    $controller = new CategoryController($blade);
    $controller->delete($id);
});

// danh sách sản phẩm
$router->get('/ASM/product', function () use ($blade) {
    $controller = new ProductController($blade);
    $controller->index();
});

// tạo sản phẩm
$router->get('/ASM/product/create', function () use ($blade) {
    $controller = new ProductController($blade);
    $controller->create();
});

// Lưu sản phẩm
$router->post('/ASM/product/store', function () use ($blade) {
    $controller = new ProductController($blade);
    $controller->store();
});

$router->get('/ASM/product/edit/(\d+)', function ($id) use ($blade) {
    $controller = new ProductController($blade);
    $controller->edit($id);
});

$router->post('/ASM/product/update/(\d+)', function ($id) use ($blade) {
    $controller = new ProductController($blade);
    $controller->update($id);
});
$router->get('/ASM/product/delete/(\d+)', function($id) use ($blade) {
    $controller = new ProductController($blade);
    $controller->delete($id);
});

$router->run();
