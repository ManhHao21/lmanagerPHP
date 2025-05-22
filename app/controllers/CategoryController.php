<?php
namespace App\Controllers;

use App\Models\Category;
use eftec\bladeone\BladeOne;
use Rakit\Validation\Validator;

class CategoryController
{
    protected $blade;
    protected $categoryModel;

    public function __construct(BladeOne $blade)
    {
        $this->blade = $blade;
        $this->categoryModel = new Category();
    }

    // ✅ Hiển thị danh sách danh mục
    public function index()
    {
        
        $keyword = $_GET['keyword'] ?? '';
        $categories = $this->categoryModel->getAllCategories($keyword);
        echo $this->blade->run('category.index', ['categories' => $categories]);
    }

    //✅ Hiển thị chi tiết danh mục
    public function show($id)
    {
        $category = $this->categoryModel->getCategoryById($id);
        echo $this->blade->run('category.show', ['category' => $category]);
    }

    // ✅ Hiển thị form tạo danh mục
    public function create()
    {
        echo $this->blade->run('category.create');
    }

    // ✅ Xử lý lưu danh mục mới
    public function store()
    {
        $validator = new Validator();

        // Kiểm tra dữ liệu đầu vào
        $validation = $validator->make($_POST, [
            'name' => 'required|min:3',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $errors = $validation->errors();
            echo $this->blade->run('category.create', ['errors' => $errors->firstOfAll()]);
            return;
        }

        // Nếu hợp lệ, thêm category vào DB
        $name = $_POST['name'];

        $result = $this->categoryModel->createCategory($name);

        if (isset($result['error'])) {
            echo $this->blade->run('category.create', ['error' => $result['error']]);
        } else {
            header("Location: /admin/category");
            exit;
        }
    }

    // ✅ Hiển thị form chỉnh sửa danh mục
    public function edit($id)
    {
        $category = $this->categoryModel->getCategoryById($id);

        if (!$category) {
            echo "Danh mục không tồn tại!";
            return;
        }

        echo $this->blade->run('category.edit', ['category' => $category]);
    }

    // ✅ Cập nhật danh mục
    public function update($id)
    {
        $validator = new Validator();

        $validation = $validator->make($_POST, [
            'name' => 'required|min:3',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $errors = $validation->errors();
            $category = $this->categoryModel->getCategoryById($id);
            echo $this->blade->run('category.edit', [
                'category' => $category,
                'errors' => $errors->firstOfAll()
            ]);
            return;
        }

        // Nếu hợp lệ, cập nhật danh mục vào DB
        $name = $_POST['name'];

        $result = $this->categoryModel->updateCategory($id, $name);

        if (isset($result['error'])) {
            $category = $this->categoryModel->getCategoryById($id);
            echo $this->blade->run('category.edit', [
                'category' => $category,
                'error' => $result['error']
            ]);
        } else {
            header("Location: /admin/category");
            exit;
        }
    }

    // ✅ Xóa danh mục
    public function delete($id)
    {
        $category = $this->categoryModel->getCategoryById($id);

        if (!$category) {
            echo "Danh mục không tồn tại!";
            return;
        }

        $result = $this->categoryModel->deleteCategory($id);

        if (isset($result['error'])) {
            echo "Lỗi: " . $result['error'];
        } else {
            header("Location: /admin/category");
            exit;
        }
    }
}
