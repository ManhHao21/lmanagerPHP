<?php

namespace App\Models;

use App\Config\Database;
use Doctrine\DBAL\Exception;

class Category
{
    protected $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // ✅ Lấy tất cả danh mục
    public function getAllCategories($keyword = '')
{
    try {
        $sql = "SELECT * FROM categories WHERE name LIKE ? ORDER BY created_at DESC";
        return $this->db->fetchAllAssociative($sql, ["%$keyword%"]);
    } catch (Exception $e) {
        return ['error' => $e->getMessage()];
    }
}


    // ✅ Lấy danh mục theo ID
    public function getCategoryById($id)
    {
        try {
            return $this->db->fetchAssociative("SELECT * FROM categories WHERE id = ?", [$id]);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // ✅ Thêm danh mục mới
    public function createCategory($name)
    {
        try {
            return $this->db->insert('categories', [
                'name' => $name,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // ✅ Cập nhật danh mục
    public function updateCategory($id, $name)
    {
        try {
            return $this->db->update('categories', [
                'name' => $name,
                'updated_at' => date('Y-m-d H:i:s')
            ], ['id' => $id]);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // ✅ Xóa danh mục
    public function deleteCategory($id)
    {
        try {
            return $this->db->delete('categories', ['id' => $id]);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // ✅ Tìm kiếm danh mục theo tên
    public function searchCategories($keyword)
    {
        try {
            return $this->db->fetchAllAssociative(
                "SELECT * FROM categories WHERE name LIKE ? ORDER BY created_at DESC",
                ["%$keyword%"]
            );
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
