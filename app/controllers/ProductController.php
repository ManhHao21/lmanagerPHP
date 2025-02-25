<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Category;
use eftec\bladeone\BladeOne;
use Rakit\Validation\Validator;

class ProductController
{
    protected $blade;
    protected $productModel;
    protected $categoryModel;
    public function __construct(BladeOne $blade)
    {
        $this->blade = $blade;
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }

    // ✅ Hiển thị danh sách người dùng
    public function index()
    {
        // Lấy dữ liệu từ request (GET hoặc POST)
        $keyword = $_GET['keyword'] ?? '';
        $category_id = $_GET['category_id'] ?? '';
        $min_price = $_GET['min_price'] ?? '';
        $max_price = $_GET['max_price'] ?? '';

        // Gọi hàm tìm kiếm từ Model
        $products = $this->productModel->searchProducts($keyword, $category_id, $min_price, $max_price);
        $categories = $this->categoryModel->getAllCategories();

        // Trả về view (truyền biến $products để hiển thị)
        echo $this->blade->run('product.index', [
            'products' => $products,
            'categories' => $categories,
            'category_id' => $category_id
        ]);
    }




    public function create()
    {
        // Lấy danh sách danh mục từ database
        $categories = $this->categoryModel->getAllCategories();

        // Trả về view tạo sản phẩm
        echo $this->blade->run('product.create', ['categories' => $categories]);
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
            'price' => trim($_POST['price'] ?? ''),
            'category_id' => trim($_POST['category_id'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'image' => $_FILES['image'] ?? null
        ];

        // Xác định quy tắc kiểm tra
        $validation = $validator->make($data, [
            'name' => 'required',
            'price' => 'required|numeric|min:1',
            'category_id' => 'required|integer',
            'description' => 'required',
            'image' => 'uploaded_file:0,2048K,png,jpg,jpeg' // Ảnh max 2MB, chỉ nhận jpg, png, jpeg
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

        // Xử lý upload ảnh nếu có
        $imagePath = null;
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === 0) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Tạo thư mục nếu chưa tồn tại
            }
            $imageName = time() . '_' . basename($_FILES['image']['name']);
            $uploadFile = $uploadDir . $imageName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                $imagePath = $uploadFile;
            }
        }

        // Gọi hàm lưu sản phẩm vào database
        $result = $this->productModel->createProduct($data['name'], $data['price'], $data['category_id'], $data['description'], $imageName);

        if (isset($result['error'])) {
            echo "Lỗi: " . $result['error'];
        } else {
            header("Location: /ASM/product");
        }
    }

    public function delete($id)
    {
        $result = $this->productModel->deleteProduct($id);

        if (is_array($result) && isset($result['error'])) {
            $_SESSION['error'] = $result['error'];
        } else {
            $_SESSION['success'] = "Xóa sản phẩm thành công!";
        }

        header("Location: /ASM/product");
        exit;
    }


    public function edit($id)
    {
        // Lấy sản phẩm theo ID
        $product = $this->productModel->getProductById($id);
        // Lấy danh sách danh mục
        $categories = $this->categoryModel->getAllCategories();

        // Kiểm tra nếu không tìm thấy sản phẩm
        if (!$product) {
            $_SESSION['error'] = "Sản phẩm không tồn tại.";
            header("Location: /ASM/product");
            exit;
        }

        // Truyền dữ liệu sang view
        echo $this->blade->run('product.edit', ['categories' => $categories, 'product' => $product]);
    }


    public function update($id) // Lấy ID từ URL
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $category_id = $_POST['category_id'];
            $price = $_POST['price'];
            $description = $_POST['description'];

            // Lấy thông tin sản phẩm hiện tại
            $currentProduct = $this->productModel->getProductById($id);
            if (!$currentProduct) {
                $_SESSION['error'] = "Sản phẩm không tồn tại!";
                header("Location: /ASM/product");
                exit;
            }

            // Xử lý upload ảnh
            $image = $currentProduct['image']; // Giữ ảnh cũ mặc định
            if (!empty($_FILES['image']['name'])) {
                $imageName = time() . '_' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $imageName);
                $image = $imageName;
            }

            // Cập nhật sản phẩm
            $result = $this->productModel->updateProduct($id, $name, $category_id, $price, $description, $image);

            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'] ?? "Cập nhật sản phẩm thất bại!";
            }

            header("Location: /ASM/product");
            exit;
        }
    }
}
