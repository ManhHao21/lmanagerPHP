<?php
namespace App\Controllers;

use App\Models\Supplier;
use eftec\bladeone\BladeOne;
use Rakit\Validation\Validator;

class SupplierController
{
    protected $blade;
    protected $supplierModel;

    public function __construct(BladeOne $blade)
    {
        $this->blade = $blade;
        $this->supplierModel = new Supplier();
    }

    // ✅ Hiển thị danh sách người dùng
    public function index()
    {
        $keyword = $_GET['keyword'] ?? '';

        $suppliers = $this->supplierModel->searchSupplier($keyword);

        echo $this->blade->run('suppliers.index', [
            'suppliers' => $suppliers,
            'keyword' => $keyword,
        ]);
    }

    public function create()
    {
        echo $this->blade->run('suppliers.create');
    }

    public function store()
    {
        $validator = new Validator();

        // ✅ Lấy dữ liệu từ form
        $validation = $validator->make($_POST, [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'address' => 'required',
            'phone' => 'required|min:10|max:10',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $errors = $validation->errors();
            echo $this->blade->run('suppliers.create', ['errors' => $errors->firstOfAll()]);
            return;
        }

        // ✅ Nếu hợp lệ, thêm user vào DB
        $name = $_POST['name'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];

        $supplierModel = new Supplier();
        $result = $supplierModel->createSupplier($name, $email, $address, $phone);
        if (isset($result['error'])) {
            $errorMessage = $result['error'];
            echo $this->blade->run('suppliers.create', ['error' => $result['error']]);
            // Thêm debug cho dev
            error_log('Supplier create error: ' . $errorMessage);
        } else {
            header("Location: /admin/supplier");
            exit;
        }
    }

    public function edit($id)
    {
        $supplier = $this->supplierModel->getSupplierById($id);

        if (!$supplier) {
            echo "Nhà cung cấp  không tồn tại!";
            return;
        }

        echo $this->blade->run('suppliers.edit', ['supplier' => $supplier]);
    }

    public function update($id)
    {
        $validator = new Validator();

        $validation = $validator->make($_POST, [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'address' => 'required',
            'phone' => 'required|min:10|max:10',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $errors = $validation->errors();
            $supplierModel = $this->supplierModel->getSupplierById($id);
            echo $this->blade->run('supplier.edit', [
                'supplierModel' => $supplierModel,
                'errors' => $errors->firstOfAll()
            ]);
            return;
        }

        // ✅ Nếu hợp lệ, cập nhật user vào DB
        $name = $_POST['name'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];

        $result = $this->supplierModel->updateSupplier($id, $name, $email, $address, $phone);

        if (isset($result['error'])) {
            $supplierModel = $this->supplierModel->getSupplierById($id);
            echo $this->blade->run('supplier.edit', [
                'supplierModel' => $supplierModel,
                'error' => $result['error']
            ]);
        } else {
            header("Location: /admin/supplier");
            exit;
        }
    }


    public function delete($id)
    {
        $supplierModel = $this->supplierModel->getSupplierById($id);

        if (!$supplierModel) {
            echo "Nhà cung cấp không tồn tại!";
            return;
        }

        $result = $this->supplierModel->deleteSupplier($id);

        if (isset($result['error'])) {
            echo "Lỗi: " . $result['error'];
        } else {
            header("Location: /admin/supplier");
            exit;
        }
    }

}
