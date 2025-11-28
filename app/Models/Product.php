<?php

namespace App\Models;

use App\Core\Model;

class Product extends Model
{

    // ================================================================
    // PHẦN 1: DÀNH CHO KHÁCH HÀNG (CLIENT)
    // ================================================================

    // 1. Lấy sản phẩm mới (Trang chủ)
    public function getNewProducts($limit = 8)
    {
        $sql = "SELECT * FROM products WHERE status = 1 ORDER BY id DESC LIMIT $limit";
        $stmt = $this->query($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 2. Lấy sản phẩm khuyến mãi (Trang chủ)
    public function getSaleProducts($limit = 4)
    {
        $sql = "SELECT * FROM products WHERE status = 1 AND sale_price > 0 ORDER BY id DESC LIMIT $limit";
        $stmt = $this->query($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 3. Lấy tất cả sản phẩm (Trang danh sách sản phẩm)
    public function getAllClient($category_id = null)
    {
        $sql = "SELECT * FROM products WHERE status = 1";

        if ($category_id) {
            $sql .= " AND category_id = :cat_id";
        }

        $sql .= " ORDER BY id DESC";

        $stmt = $this->query($sql);
        if ($category_id) {
            $stmt->bindValue(':cat_id', $category_id);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 4. Lấy sản phẩm liên quan (Trang chi tiết)
    public function getRelatedProducts($category_id, $exclude_id, $limit = 3)
    {
        $sql = "SELECT * FROM products 
                WHERE category_id = :cat_id 
                AND id != :ex_id 
                AND status = 1 
                ORDER BY id DESC LIMIT $limit";

        $stmt = $this->query($sql);
        $stmt->execute([':cat_id' => $category_id, ':ex_id' => $exclude_id]);
        return $stmt->fetchAll();
    }

    // ================================================================
    // PHẦN 2: DÙNG CHUNG (CẢ ADMIN & CLIENT)
    // ================================================================

    // 5. Tìm 1 sản phẩm theo ID (Dùng cho xem chi tiết & Sửa)
    public function find($id)
    {
        // JOIN để lấy tên danh mục luôn
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN product_categories c ON p.category_id = c.id
                WHERE p.id = :id";
        $stmt = $this->query($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // 6. Lấy Album ảnh phụ (Gallery)
    public function getGallery($product_id)
    {
        $sql = "SELECT * FROM product_images WHERE product_id = :pid";
        $stmt = $this->query($sql);
        $stmt->execute([':pid' => $product_id]);
        return $stmt->fetchAll();
    }

    // ================================================================
    // PHẦN 3: DÀNH CHO ADMIN (QUẢN TRỊ)
    // ================================================================

    // 7. Lấy danh sách Admin (Có tìm kiếm + Lọc danh mục)
    public function getAllAdmin($keyword = null, $cat_id = null)
    {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p
                LEFT JOIN product_categories c ON p.category_id = c.id
                WHERE 1=1";

        if (!empty($keyword)) {
            $sql .= " AND (p.name LIKE :keyword OR p.sku LIKE :keyword)";
        }
        if (!empty($cat_id)) {
            $sql .= " AND p.category_id = :cat_id";
        }

        $sql .= " ORDER BY p.id DESC";

        $stmt = $this->query($sql);
        if (!empty($keyword)) $stmt->bindValue(':keyword', "%$keyword%");
        if (!empty($cat_id)) $stmt->bindValue(':cat_id', $cat_id);

        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 8. Thêm mới và lấy ID
    public function createGetId($data)
    {
        $sql = "INSERT INTO products 
                (category_id, name, sku, price, sale_price, main_image, stock, ingredients, uses, usage_instruction, note, status, created_at) 
                VALUES 
                (:cat_id, :name, :sku, :price, :sale, :img, :stock, :ingr, :uses, :usage, :note, :status, NOW())";

        $stmt = $this->query($sql);
        $stmt->execute([
            ':cat_id' => $data['category_id'],
            ':name'   => $data['name'],
            ':sku'    => $data['sku'],
            ':price'  => $data['price'],
            ':sale'   => $data['sale_price'],
            ':img'    => $data['main_image'],
            ':stock'  => $data['stock'],
            ':ingr'   => $data['ingredients'],
            ':uses'   => $data['uses'],
            ':usage'  => $data['usage_instruction'],
            ':note'   => $data['note'],
            ':status' => $data['status']
        ]);
        return $this->db->getConnection()->lastInsertId();
    }

    // 9. Cập nhật
    public function update($data)
    {
        $sql = "UPDATE products SET 
                category_id = :cat_id, name = :name, sku = :sku, price = :price, sale_price = :sale, 
                main_image = :img, stock = :stock, ingredients = :ingr, uses = :uses, 
                usage_instruction = :usage, note = :note, status = :status
                WHERE id = :id";

        $stmt = $this->query($sql);
        return $stmt->execute([
            ':cat_id' => $data['category_id'],
            ':name' => $data['name'],
            ':sku' => $data['sku'],
            ':price' => $data['price'],
            ':sale' => $data['sale_price'],
            ':img' => $data['main_image'],
            ':stock' => $data['stock'],
            ':ingr' => $data['ingredients'],
            ':uses' => $data['uses'],
            ':usage' => $data['usage_instruction'],
            ':note' => $data['note'],
            ':status' => $data['status'],
            ':id' => $data['id']
        ]);
    }

    // 10. Xóa sản phẩm
    public function delete($id)
    {
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $this->query($sql);
        return $stmt->execute([':id' => $id]);
    }

    // 11. Các hàm xử lý Gallery
    public function addGalleryImage($product_id, $image_url)
    {
        $sql = "INSERT INTO product_images (product_id, image_url) VALUES (:pid, :url)";
        $stmt = $this->query($sql);
        return $stmt->execute([':pid' => $product_id, ':url' => $image_url]);
    }

    public function findGalleryImage($image_id)
    {
        $sql = "SELECT * FROM product_images WHERE id = :id";
        $stmt = $this->query($sql);
        $stmt->execute([':id' => $image_id]);
        return $stmt->fetch();
    }

    public function deleteGalleryImage($image_id)
    {
        $sql = "DELETE FROM product_images WHERE id = :id";
        $stmt = $this->query($sql);
        return $stmt->execute([':id' => $image_id]);
    }
}
