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

    // ✅ Lấy tất cả người dùng
    public function getAll()
    {
        try {
            return $this->db->fetchAllAssociative("SELECT * FROM products");
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // ✅ Thêm người dùng mới
    public function createProduct($name, $price, $category_id, $description, $image)
    {
        try {
            return $this->db->insert('products', [
                'name' => $name,
                'price' => $price,
                'category_id' => $category_id,
                'description' => $description,
                'image' => $image,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }




    // ✅ Xóa người dùng theo ID
    public function deleteUser($id)
    {
        try {
            return $this->db->delete('products', ['id' => $id]);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
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


    public function deleteProduct($id)
    {
        try {
            // Lấy thông tin sản phẩm để kiểm tra và xóa ảnh
            $sql = "SELECT image FROM products WHERE id = :id";
            $product = $this->db->executeQuery($sql, ['id' => $id])->fetchAssociative();

            if ($product) {
                // Xóa ảnh nếu tồn tại
                if (!empty($product['image'])) {
                    $imagePath = 'uploads/' . $product['image'];
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }

                // Thực hiện xóa sản phẩm
                $sql = "DELETE FROM products WHERE id = :id";
                $stmt = $this->db->executeQuery($sql, ['id' => $id]);
                $affectedRows = $stmt->rowCount(); // Kiểm tra số dòng bị ảnh hưởng

                return $affectedRows > 0 ? true : ['error' => 'Không tìm thấy sản phẩm để xóa.'];
            }

            return ['error' => 'Sản phẩm không tồn tại.'];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function editProduct($id)
    {
        try {
            $sql = "SELECT * FROM products WHERE id = :id";
            return $this->db->executeQuery($sql, ['id' => $id])->fetchAssociative();
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getProductById($id)
    {
        try {
            $sql = "SELECT * FROM products WHERE id = :id";
            $params = ['id' => $id];

            $result = $this->db->executeQuery($sql, $params);
            return $result->fetchAssociative(); // Lấy dữ liệu dưới dạng mảng liên kết
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }


    public function updateProduct($id, $name, $category_id, $price, $description, $image)
    {
        try {
            $sql = "UPDATE products SET name = :name, category_id = :category_id, price = :price, description = :description";
            $params = [
                'id' => $id,
                'name' => $name,
                'category_id' => $category_id,
                'price' => $price,
                'description' => $description,
            ];

            // Nếu có ảnh mới, cập nhật cột image
            if (!empty($image)) {
                $sql .= ", image = :image";
                $params['image'] = $image;
            }

            $sql .= " WHERE id = :id";

            $result = $this->db->executeQuery($sql, $params);

            // Kiểm tra số dòng bị ảnh hưởng (nếu không có thay đổi thì có thể trả về lỗi)
            if ($result->rowCount() > 0) {
                return ['success' => true, 'message' => "Cập nhật sản phẩm thành công!"];
            } else {
                return ['success' => false, 'message' => "Không có thay đổi nào được thực hiện."];
            }
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
