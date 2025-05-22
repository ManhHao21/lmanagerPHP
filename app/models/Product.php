<?php

namespace App\Models;

use App\Config\Database;
use Doctrine\DBAL\Exception;

class Product
{
    protected $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAll()
    {
        try {
            return $this->db->fetchAllAssociative("SELECT * FROM products");
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function createProduct($data)
    {
        try {
            // Gửi mảng dữ liệu đầy đủ để insert
            return $this->db->insert('products', [
                'name' => $data['name'],
                'price' => $data['price'] ?? 0,
                'PromotionPrice' => $data['PromotionPrice'] ?? 0,
                'Vat' => $data['Vat'] ?? 0,
                'Quantity' => $data['Quantity'] ?? 0,
                'Hot' => $data['Hot'] ?? 0,
                'category_id' => $data['category_id'],
                'description' => $data['description'],
                'Detail' => $data['Detail'] ?? null,
                'slug' => $data['slug'] ?? null,
                'status' => $data['status'] ?? 0,
                'ViewCount' => $data['ViewCount'] ?? 0,
                'image' => $data['image'] ?? null,
                'BrandID' => $data['BrandID'] ?? null,
                'SupplierID' => $data['SupplierID'] ?? null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function deleteProduct($id)
    {
        try {
            $sql = "SELECT image FROM products WHERE id = :id";
            $product = $this->db->executeQuery($sql, ['id' => $id])->fetchAssociative();

            if ($product) {
                if (!empty($product['image'])) {
                    $imagePath = 'uploads/' . $product['image'];
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }

                $sql = "DELETE FROM products WHERE id = :id";
                $stmt = $this->db->executeQuery($sql, ['id' => $id]);
                $affectedRows = $stmt->rowCount();

                return $affectedRows > 0 ? true : ['error' => 'Không tìm thấy sản phẩm để xóa.'];
            }

            return ['error' => 'Sản phẩm không tồn tại.'];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getProductById($id)
    {
        try {
            $sql = "SELECT * FROM products WHERE id = :id";
            return $this->db->executeQuery($sql, ['id' => $id])->fetchAssociative();
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function updateProduct($id, $data)
    {
        try {
            $sql = "UPDATE products SET 
                    name = :name, 
                    price = :price,
                    PromotionPrice = :PromotionPrice,
                    Vat = :Vat,
                    Quantity = :Quantity,
                    Hot = :Hot,
                    category_id = :category_id,
                    description = :description,
                    Detail = :Detail,
                    slug = :slug,
                    status = :status,
                    ViewCount = :ViewCount,
                    image = :image,
                    BrandID = :BrandID,
                    SupplierID = :SupplierID,
                    updated_at = :updated_at
                    WHERE id = :id";

            $params = [
                'id' => $id,
                'name' => $data['name'],
                'price' => $data['price'],
                'PromotionPrice' => $data['PromotionPrice'] ?? null,
                'Vat' => $data['Vat'] ?? null,
                'Quantity' => $data['Quantity'] ?? null,
                'Hot' => $data['Hot'] ?? 0,
                'category_id' => $data['category_id'],
                'description' => $data['description'],
                'Detail' => $data['Detail'] ?? null,
                'slug' => $data['slug'] ?? null,
                'status' => $data['status'] ?? null,
                'ViewCount' => $data['ViewCount'] ?? null,
                'image' => $data['image'] ?? null,
                'BrandID' => $data['BrandID'] ?? null,
                'SupplierID' => $data['SupplierID'] ?? null,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $result = $this->db->executeQuery($sql, $params);

            if ($result->rowCount() > 0) {
                return ['success' => true, 'message' => "Cập nhật sản phẩm thành công!"];
            } else {
                return ['success' => false, 'message' => "Không có thay đổi nào được thực hiện."];
            }
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function searchProducts($keyword = '', $category_id = '', $min_price = '', $max_price = '')
    {
        try {
            $sql = "SELECT p.*, c.name as category_name 
                    FROM products p
                    LEFT JOIN categories c ON p.category_id = c.id
                    WHERE 1=1";
            $params = [];

            if (!empty($keyword)) {
                $sql .= " AND (p.name LIKE :keyword OR p.description LIKE :keyword)";
                $params['keyword'] = "%$keyword%";
            }

            if (!empty($category_id)) {
                $sql .= " AND p.category_id = :category_id";
                $params['category_id'] = $category_id;
            }

            if (!empty($min_price)) {
                $sql .= " AND p.price >= :min_price";
                $params['min_price'] = $min_price;
            }

            if (!empty($max_price)) {
                $sql .= " AND p.price <= :max_price";
                $params['max_price'] = $max_price;
            }

            return $this->db->executeQuery($sql, $params)->fetchAllAssociative();
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
