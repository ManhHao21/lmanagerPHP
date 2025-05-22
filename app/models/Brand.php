<?php

namespace App\Models;

use App\Config\Database;
use Doctrine\DBAL\Exception;

class Brand
{
    protected $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // ✅ Lấy tất cả thương hiệu
    public function getAllbrands($keyword = '')
{
    try {
        $sql = "SELECT * FROM brands WHERE name LIKE ? ORDER BY created_at DESC";
        return $this->db->fetchAllAssociative($sql, ["%$keyword%"]);
    } catch (Exception $e) {
        return ['error' => $e->getMessage()];
    }
}


    // ✅ Lấy thương hiệu theo ID
    public function getBrandById($id)
    {
        try {
            return $this->db->fetchAssociative("SELECT * FROM brands WHERE id = ?", [$id]);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // ✅ Thêm thương hiệu mới
    public function createBrand($name)
    {
        try {
            return $this->db->insert('brands', [
                'name' => $name,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // ✅ Cập nhật thương hiệu
    public function updateBrand($id, $name)
    {
        try {
            return $this->db->update('brands', [
                'name' => $name,
                'updated_at' => date('Y-m-d H:i:s')
            ], ['id' => $id]);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // ✅ Xóa thương hiệu
    public function deleteBrand($id)
    {
        try {
            return $this->db->delete('brands', ['id' => $id]);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // ✅ Tìm kiếm thương hiệu theo tên
    public function searchBrands($keyword)
    {
        try {
            return $this->db->fetchAllAssociative(
                "SELECT * FROM brands WHERE name LIKE ? ORDER BY created_at DESC",
                ["%$keyword%"]
            );
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
