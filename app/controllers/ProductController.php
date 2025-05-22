<?php

namespace App\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use eftec\bladeone\BladeOne;
use Rakit\Validation\Validator;

class ProductController
{
    protected $blade;
    protected $productModel;
    protected $categoryModel;
    protected $brandModel;
    protected $supplierModel;
    public function __construct(BladeOne $blade)
    {
        $this->blade = $blade;
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->brandModel = new Brand();
        $this->supplierModel = new Supplier();
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
        $categories = $this->categoryModel->getAllCategories();
        $brands = $this->brandModel->getAllBrands();
        $suppliers = $this->supplierModel->getAllSupplier();

        echo $this->blade->run('product.create', compact('categories', 'brands', 'suppliers'));
    }




    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die("Phương thức không hợp lệ!");
        }

        $validator = new Validator();

        // Xử lý các trường số: nếu rỗng thì gán null hoặc 0, ép kiểu số đúng
        $hotValue = (isset($_POST['Hot']) && $_POST['Hot'] == '1') ? 1 : 0;

        // PromotionPrice: nếu rỗng thì null (để tránh lỗi 'Incorrect decimal value')
        $promotionPriceRaw = trim($_POST['PromotionPrice'] ?? '');
        $promotionPrice = $promotionPriceRaw === '' ? null : (float) $promotionPriceRaw;

        $vatRaw = trim($_POST['Vat'] ?? '');
        $vat = $vatRaw === '' ? null : (int) $vatRaw;

        $quantityRaw = trim($_POST['Quantity'] ?? '');
        $quantity = $quantityRaw === '' ? null : (int) $quantityRaw;

        $statusRaw = trim($_POST['status'] ?? '');
        $status = $statusRaw === '' ? null : (int) $statusRaw;

        $brandIdRaw = trim($_POST['BrandID'] ?? '');
        $brandId = $brandIdRaw === '' ? null : (int) $brandIdRaw;

        $supplierIdRaw = trim($_POST['SupplierID'] ?? '');
        $supplierId = $supplierIdRaw === '' ? null : (int) $supplierIdRaw;

        $viewCountRaw = trim($_POST['ViewCount'] ?? '');
        $viewCount = $viewCountRaw === '' ? 0 : (int) $viewCountRaw;

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'price' => trim($_POST['price'] ?? ''),
            'PromotionPrice' => $promotionPrice,
            'Vat' => $vat,
            'Quantity' => $quantity,
            'Hot' => $hotValue,
            'category_id' => trim($_POST['category_id'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'Detail' => trim($_POST['Detail'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'status' => $status,
            'ViewCount' => $viewCount,
            'BrandID' => $brandId,
            'SupplierID' => $supplierId,
            'image' => null,
        ];

        $validation = $validator->make($data, [
            'name' => 'required',
            'price' => 'required|numeric|min:1',
            'category_id' => 'required|integer',
            'description' => 'required',
            'PromotionPrice' => 'nullable|numeric',
            'Vat' => 'nullable|integer',
            'Quantity' => 'nullable|integer',
            'Hot' => 'nullable|integer',
            'status' => 'nullable|integer',
            'BrandID' => 'nullable|integer',
            'SupplierID' => 'nullable|integer',
            'image' => 'uploaded_file:0,2048K,png,jpg,jpeg'
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $errors = $validation->errors()->all();
            foreach ($errors as $error) {
                echo "<p style='color: red;'>$error</p>";
            }
            return;
        }

        // Xử lý upload ảnh
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === 0) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $imageName = time() . '_' . basename($_FILES['image']['name']);
            $uploadFile = $uploadDir . $imageName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                $data['image'] = $imageName;
            }
        }

        $result = $this->productModel->createProduct($data);

        if (isset($result['error'])) {
            echo "Lỗi: " . $result['error'];
        } else {
            header("Location: /admin/product");
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

        header("Location: /admin/product");
        exit;
    }


    public function edit($id)
    {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            $_SESSION['error'] = "Sản phẩm không tồn tại.";
            header("Location: /admin/product");
            exit;
        }
        $categories = $this->categoryModel->getAllCategories();
        $brands = $this->brandModel->getAllBrands();
        $suppliers = $this->supplierModel->getAllSupplier();

        echo $this->blade->run('product.edit', compact('product', 'categories', 'brands', 'suppliers'));
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die("Phương thức không hợp lệ!");
        }

        $currentProduct = $this->productModel->getProductById($id);
        if (!$currentProduct) {
            $_SESSION['error'] = "Sản phẩm không tồn tại!";
            header("Location: /admin/product");
            exit;
        }

        $data = [
            'name' => trim($_POST['name'] ?? $currentProduct['name']),
            'price' => trim($_POST['price'] ?? $currentProduct['price']),
            'PromotionPrice' => trim($_POST['PromotionPrice'] ?? $currentProduct['PromotionPrice']),
            'Vat' => trim($_POST['Vat'] ?? $currentProduct['Vat']),
            'Quantity' => trim($_POST['Quantity'] ?? $currentProduct['Quantity']),
            'Hot' => isset($_POST['Hot']) ? 1 : ($currentProduct['Hot'] ?? 0),
            'category_id' => trim($_POST['category_id'] ?? $currentProduct['category_id']),
            'description' => trim($_POST['description'] ?? $currentProduct['description']),
            'Detail' => trim($_POST['Detail'] ?? $currentProduct['Detail']),
            'slug' => trim($_POST['slug'] ?? $currentProduct['slug']),
            'status' => trim($_POST['status'] ?? $currentProduct['status']),
            'ViewCount' => trim($_POST['ViewCount'] ?? $currentProduct['ViewCount']),
            'BrandID' => trim($_POST['BrandID'] ?? $currentProduct['BrandID']),
            'SupplierID' => trim($_POST['SupplierID'] ?? $currentProduct['SupplierID']),
            'image' => $currentProduct['image'],
        ];

        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === 0) {
            $imageName = time() . '_' . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $imageName);
            $data['image'] = $imageName;
        }

        $result = $this->productModel->updateProduct($id, $data);

        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['error'] = $result['message'] ?? "Cập nhật sản phẩm thất bại!";
        }

        header("Location: /admin/product");
        exit;
    }
}
