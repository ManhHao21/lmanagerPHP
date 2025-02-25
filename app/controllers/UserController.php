<?php
namespace App\Controllers;

use App\Models\User;
use eftec\bladeone\BladeOne;

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
        $users = $this->userModel->getAllUsers();
        echo $this->blade->run('users.index', ['users' => $users]);
    }

    // ✅ Hiển thị chi tiết một người dùng
    public function show($id)
    {
        $user = $this->userModel->getUserById($id);
        echo $this->blade->run('users.show', ['user' => $user]);
    }
}
