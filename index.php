<?php
require_once './vendor/autoload.php';
require_once './app/helpers/index.php';

use App\Controllers\SupplierController;
use Dotenv\Dotenv;
use App\Models\User;
use Bramus\Router\Router;
use eftec\bladeone\BladeOne;
use App\Middleware\AuthMiddleware;
use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\BrandController;
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
$router->before('GET|POST', '/admin/.*', function () {
    AuthMiddleware::handle();
});
$router->get('/', function () {
    header("Location: /admin/user");
    exit;
});
$router->get('/admin/user', function () use ($blade) {
    $controller = new UserController($blade);
    $controller->index();
});
$router->get('/admin/user/create', function () use ($blade) {
    $controller = new UserController($blade);
    $controller->create();
});

$router->post('/admin/user/store', function () use ($blade) {
    $controller = new UserController($blade);
    $controller->store();
});
$router->get('/admin/user/edit/(\d+)', function ($id) use ($blade) {
    $controller = new UserController($blade);
    $controller->edit($id);
});

$router->post('/admin/user/update/(\d+)', function ($id) use ($blade) {
    $controller = new UserController($blade);
    $controller->update($id);
});
$router->get('/admin/user/delete/(\d+)', function($id) use ($blade) {
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
$router->get('/admin/category', function () use ($blade) {
    $controller = new CategoryController($blade);
    $controller->index();
});
$router->get('/admin/category/create', function () use ($blade) {
    $controller = new CategoryController($blade);
    $controller->create();
});

$router->post('/admin/category/store', function () use ($blade) {
    $controller = new CategoryController($blade);
    $controller->store();
});
$router->get('/admin/category/edit/(\d+)', function ($id) use ($blade) {
    $controller = new CategoryController($blade);
    $controller->edit($id);
});

$router->post('/admin/category/update/(\d+)', function ($id) use ($blade) {
    $controller = new CategoryController($blade);
    $controller->update($id);
});
$router->get('/admin/category/delete/(\d+)', function($id) use ($blade) {
    $controller = new CategoryController($blade);
    $controller->delete($id);
});

// danh sách sản phẩm
$router->get('/admin/product', function () use ($blade) {
    $controller = new ProductController($blade);
    $controller->index();
});

// tạo sản phẩm
$router->get('/admin/product/create', function () use ($blade) {
    $controller = new ProductController($blade);
    $controller->create();
});

// Lưu sản phẩm
$router->post('/admin/product/store', function () use ($blade) {
    $controller = new ProductController($blade);
    $controller->store();
});

$router->get('/admin/product/edit/(\d+)', function ($id) use ($blade) {
    $controller = new ProductController($blade);
    $controller->edit($id);
});

$router->post('/admin/product/update/(\d+)', function ($id) use ($blade) {
    $controller = new ProductController($blade);
    $controller->update($id);
});
$router->get('/admin/product/delete/(\d+)', function($id) use ($blade) {
    $controller = new ProductController($blade);
    $controller->delete($id);
});


// danh sách thương hiệu
$router->get('/admin/brand', function () use ($blade) {
    $controller = new BrandController($blade);
    $controller->index();
});

// tạo thương hiệu
$router->get('/admin/brand/create', function () use ($blade) {
    $controller = new BrandController($blade);
    $controller->create();
});

// Lưu thương hiệu
$router->post('/admin/brand/store', function () use ($blade) {
    $controller = new BrandController($blade);
    $controller->store();
});

$router->get('/admin/brand/edit/(\d+)', function ($id) use ($blade) {
    $controller = new BrandController($blade);
    $controller->edit($id);
});

$router->post('/admin/brand/update/(\d+)', function ($id) use ($blade) {
    $controller = new BrandController($blade);
    $controller->update($id);
});
$router->get('/admin/brand/delete/(\d+)', function($id) use ($blade) {
    $controller = new BrandController($blade);
    $controller->delete($id);
});



// danh sách nhà cung cấp
$router->get('/admin/supplier', function () use ($blade) {
    $controller = new SupplierController($blade);
    $controller->index();
});

// tạo nhà cung cấp
$router->get('/admin/supplier/create', function () use ($blade) {
    $controller = new SupplierController($blade);
    $controller->create();
});

// Lưu nhà cung cấp
$router->post('/admin/supplier/store', function () use ($blade) {
    $controller = new SupplierController($blade);
    $controller->store();
});

$router->get('/admin/supplier/edit/(\d+)', function ($id) use ($blade) {
    $controller = new SupplierController($blade);
    $controller->edit($id);
});

$router->post('/admin/supplier/update/(\d+)', function ($id) use ($blade) {
    $controller = new SupplierController($blade);
    $controller->update($id);
});
$router->get('/admin/supplier/delete/(\d+)', function($id) use ($blade) {
    $controller = new SupplierController($blade);
    $controller->delete($id);
});

$router->run();
