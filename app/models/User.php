<?php
namespace App\Models;

use App\Config\Database;
use Doctrine\DBAL\Exception;

class User
{
    protected $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // ✅ Lấy tất cả người dùng
    public function getAllUsers()
    {
        try {
            return $this->db->fetchAllAssociative("SELECT * FROM users");
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // ✅ Lấy thông tin một người dùng theo ID
    public function getUserById($id)
    {
        try {
            return $this->db->fetchAssociative("SELECT * FROM users WHERE id = ?", [$id]);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // ✅ Thêm người dùng mới
    public function createUser($name, $email, $password, $role = 'user')
    {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            return $this->db->insert('users', [
                'name' => $name,
                'email' => $email,
                'password' => $hashedPassword,
                'role' => $role
            ]);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // ✅ Cập nhật thông tin người dùng
    public function updateUser($id, $name, $email, $role)
    {
        try {
            return $this->db->update('users', [
                'name' => $name,
                'email' => $email,
                'role' => $role
            ], ['id' => $id]);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // ✅ Xóa người dùng theo ID
    public function deleteUser($id)
    {
        try {
            return $this->db->delete('users', ['id' => $id]);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
