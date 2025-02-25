<?php
namespace App\Controllers;

use App\Models\User;
use eftec\bladeone\BladeOne;
use Rakit\Validation\Validator;

class UserController
{
    protected $blade;
    protected $userModel;

    public function __construct(BladeOne $blade)
    {
        $this->blade = $blade;
        $this->userModel = new User();
    }

    // ✅ Hiển thị danh sách người dùng
    public function index()
    {
        $keyword = $_GET['keyword'] ?? '';
        $role = $_GET['role'] ?? '';

        $users = $this->userModel->searchUsers($keyword, $role);

        echo $this->blade->run('users.index', [
            'users' => $users,
            'keyword' => $keyword,
            'role' => $role
        ]);
    }


    // ✅ Hiển thị chi tiết một người dùng
    public function show($id)
    {
        $user = $this->userModel->getUserById($id);
        echo $this->blade->run('users.show', ['user' => $user]);
    }

    public function create()
    {
        echo $this->blade->run('users.create');
    }

    public function store()
    {
        $validator = new Validator();

        // ✅ Lấy dữ liệu từ form
        $validation = $validator->make($_POST, [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'role' => 'required|in:user,admin',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $errors = $validation->errors();
            echo $this->blade->run('users.create', ['errors' => $errors->firstOfAll()]);
            return;
        }

        // ✅ Nếu hợp lệ, thêm user vào DB
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        $userModel = new User();
        $result = $userModel->createUser($name, $email, $password, $role);

        if (isset($result['error'])) {
            echo $this->blade->run('users.create', ['error' => $result['error']]);
        } else {
            header("Location: /ASM/user");
            exit;
        }
    }

    public function edit($id)
    {
        $user = $this->userModel->getUserById($id);

        if (!$user) {
            echo "User không tồn tại!";
            return;
        }

        echo $this->blade->run('users.edit', ['user' => $user]);
    }

    public function update($id)
    {
        $validator = new Validator();

        $validation = $validator->make($_POST, [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'role' => 'required|in:user,admin',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $errors = $validation->errors();
            $user = $this->userModel->getUserById($id);
            echo $this->blade->run('users.edit', [
                'user' => $user,
                'errors' => $errors->firstOfAll()
            ]);
            return;
        }

        // ✅ Nếu hợp lệ, cập nhật user vào DB
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'] ?? null;
        $role = $_POST['role'];

        $result = $this->userModel->updateUser($id, $name, $email, $password, $role);

        if (isset($result['error'])) {
            $user = $this->userModel->getUserById($id);
            echo $this->blade->run('users.edit', [
                'user' => $user,
                'error' => $result['error']
            ]);
        } else {
            header("Location: /ASM/user");
            exit;
        }
    }


    public function delete($id)
    {
        $user = $this->userModel->getUserById($id);

        if (!$user) {
            echo "User không tồn tại!";
            return;
        }

        $result = $this->userModel->deleteUser($id);

        if (isset($result['error'])) {
            echo "Lỗi: " . $result['error'];
        } else {
            header("Location: /ASM/user");
            exit;
        }
    }

}
