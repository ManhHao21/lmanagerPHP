<?php

namespace App\Models;

use App\Config\Database;
use Doctrine\DBAL\Exception;

class Supplier
{
    protected $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // ✅ Lấy tất cả thương hiệu
    public function getAllSupplier($keyword = '')
    {
        try {
            $sql = "SELECT * FROM suppliers WHERE name LIKE ? ORDER BY created_at DESC";
            return $this->db->fetchAllAssociative($sql, ["%$keyword%"]);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }


    // ✅ Lấy thương hiệu theo ID
    public function getSupplierById($id)
    {
        try {
            return $this->db->fetchAssociative("SELECT * FROM suppliers WHERE id = ?", [$id]);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // ✅ Thêm thương hiệu mới
    public function createSupplier($name, $email, $address, $phone)
    {
        try {
            return $this->db->insert('suppliers', [
                'Name' => $name,
                'Email' => $email,
                'Address' => $address,
                'Phone' => $phone,
                'created_at' => date('Y-m-d H:i:s'),
                'update_at' => date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // ✅ Cập nhật thương hiệu
    public function updateSupplier($id, $name, $email, $address, $phone)
    {
        try {
            return $this->db->update('suppliers', [
                'Name' => $name,
                'Email' => $email,
                'Address' => $address,
                'Phone' => $phone,
                'created_at' => date('Y-m-d H:i:s'),
                'update_at' => date('Y-m-d H:i:s')
            ], ['id' => $id]);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // ✅ Xóa thương hiệu
    public function deleteSupplier($id)
    {
        try {
            return $this->db->delete('suppliers', ['id' => $id]);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // ✅ Tìm kiếm thương hiệu theo tên
    public function searchSupplier($keyword)
    {
        try {
            $sql = "SELECT * FROM suppliers 
            WHERE email LIKE ? OR name LIKE ? OR address LIKE ? 
            ORDER BY created_at DESC";
            $param = ["%$keyword%", "%$keyword%", "%$keyword%"];

            return $this->db->fetchAllAssociative($sql, $param);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

}
