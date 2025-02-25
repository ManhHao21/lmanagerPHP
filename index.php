<?php
require_once './vendor/autoload.php';
require_once './app/helpers/index.php';

use Dotenv\Dotenv;
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

$route->prefix('ASM')->group(function(){
    $route->prefix('user')->group(function(){
    $router->get('/', function() use ($ blade) {
        echo $blade->run('home', ['name' => 'John']);
    });
})
// Định tuyến trang chủ


$router->run();
