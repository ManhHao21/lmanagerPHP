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



    // ✅ Xóa người dùng theo ID
    public function deleteUser($id)
    {
        try {
            return $this->db->delete('users', ['id' => $id]);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function searchUsers($keyword = '', $role = '')
    {
        try {
            $sql = "SELECT * FROM users WHERE 1=1";
            $params = [];

            if (!empty($keyword)) {
                $sql .= " AND (name LIKE :keyword OR email LIKE :keyword)";
                $params['keyword'] = "%$keyword%";
            }

            if (!empty($role)) {
                $sql .= " AND role = :role";
                $params['role'] = $role;
            }

            return $this->db->executeQuery($sql, $params)->fetchAllAssociative();
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getUserById($id)
{
    try {
        return $this->db->fetchAssociative("SELECT * FROM users WHERE id = ?", [$id]);
    } catch (Exception $e) {
        return ['error' => $e->getMessage()];
    }
}

public function updateUser($id, $name, $email, $password = null, $role = 'admin')
{
    try {
        $updateData = [
            'name' => $name,
            'email' => $email,
            'role' => $role
        ];

        if (!empty($password)) {
            $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        return $this->db->update('users', $updateData, ['id' => $id]);
    } catch (Exception $e) {
        return ['error' => $e->getMessage()];
    }
}

public function getUserByEmail($email)
{
    try {
        return $this->db->fetchAssociative("SELECT * FROM users WHERE email = ?", [$email]);
    } catch (Exception $e) {
        return null;
    }
}


}
