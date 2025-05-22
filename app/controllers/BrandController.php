<?php

namespace App\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use eftec\bladeone\BladeOne;
use Rakit\Validation\Validator;

class BrandController
{
    protected $blade;
    protected $brandtModel;
    public function __construct(BladeOne $blade)
    {
        $this->blade = $blade;
        $this->brandtModel = new Brand();
    }

    // ✅ Hiển thị danh sách người dùng
    public function index()
    {
        // Lấy dữ liệu từ request (GET hoặc POST)
        $keyword = $_GET['keyword'] ?? '';
        $brand = $this->brandtModel->searchBrands($keyword);

        // Trả về view (truyền biến $products để hiển thị)
        echo $this->blade->run('brands.index', [
            'brands' => $brand,
        ]);
    }




    public function create()
    {
        echo $this->blade->run('brands.create');
    }


    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die("Phương thức không hợp lệ!");
        }

        // Khởi tạo Validator của Rakit
        $validator = new Validator();

        // Lấy dữ liệu từ form
        $data = [
            'name' => trim($_POST['name'] ?? ''),
        ];

        // Xác định quy tắc kiểm tra
        $validation = $validator->make($data, [
            'name' => 'required',
        ]);

        // Chạy kiểm tra
        $validation->validate();

        // Nếu có lỗi, hiển thị thông báo lỗi
        if ($validation->fails()) {
            $errors = $validation->errors()->all();
            foreach ($errors as $error) {
                echo "<p style='color: red;'>$error</p>";
            }
            return;
        }
        // Gọi hàm lưu sản phẩm vào database
        $result = $this->brandtModel->createBrand($data['name']);

        if (isset($result['error'])) {
            echo "Lỗi: " . $result['error'];
        } else {
            header("Location: /admin/brand");
        }
    }

    public function delete($id)
    {
        $result = $this->brandtModel->deleteBrand($id);

        if (is_array($result) && isset($result['error'])) {
            $_SESSION['error'] = $result['error'];
        } else {
            $_SESSION['success'] = "Xóa thương hiệu thành công!";
        }

        header("Location: /admin/brand");
        exit;
    }


    public function edit($id)
    {
        // Lấy sản phẩm theo ID
        $brand = $this->brandtModel->getBrandById($id);
        // Truyền dữ liệu sang view
        echo $this->blade->run('brands.edit', variables: [ 'brand' => $brand]);
    }


    public function update($id) // Lấy ID từ URL
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            // Lấy thông tin sản phẩm hiện tại
            $currentProduct = $this->brandtModel->getBrandById($id);
            if (!$currentProduct) {
                $_SESSION['error'] = "Thương hiệu không tồn tại!";
                header("Location: /admin/brand");
                exit;
            }
            // Cập nhật sản phẩm
            $result = $this->brandtModel->updateBrand($id, $name);

            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'] ?? "Cập nhật thương hiệu thất bại!";
            }

            header("Location: /admin/brand");
            exit;
        }
    }
}
