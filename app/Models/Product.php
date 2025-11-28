<?php

namespace App\Models;

use App\Core\Model;

class Product extends Model
{

    // =========================================================================
    // 1. CÁC HÀM CHO CLIENT (TRANG CHỦ & DANH SÁCH)
    // =========================================================================

    // Lấy sản phẩm mới nhất (Dùng cho Trang chủ)
    public function getNewProducts($limit = 8)
    {
        $sql = "SELECT * FROM products WHERE status = 1 ORDER BY id DESC LIMIT $limit";
        $stmt = $this->query($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy sản phẩm khuyến mãi (Dùng cho Trang chủ)
    public function getSaleProducts($limit = 4)
    {
        $sql = "SELECT * FROM products WHERE status = 1 AND sale_price > 0 ORDER BY id DESC LIMIT $limit";
        $stmt = $this->query($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy tất cả sản phẩm cho trang danh sách (Client /product)
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

    // Lấy sản phẩm liên quan (Trang chi tiết)
    public function getRelatedProducts($category_id, $exclude_id, $limit = 3)
    {
        $sql = "SELECT * FROM products 
                WHERE category_id = :cat_id 
                AND id != :ex_id 
                AND status = 1 
                ORDER BY id DESC LIMIT $limit";

        $stmt = $this->query($sql);
        $stmt->bindParam(':cat_id', $category_id);
        $stmt->bindParam(':ex_id', $exclude_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // =========================================================================
    // 2. CÁC HÀM DÙNG CHUNG (CẢ ADMIN & CLIENT)
    // =========================================================================

    // Lấy chi tiết 1 sản phẩm (Kèm tên danh mục)
    public function find($id)
    {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN product_categories c ON p.category_id = c.id
                WHERE p.id = :id";

        $stmt = $this->query($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Lấy album ảnh phụ
    public function getGallery($product_id)
    {
        $sql = "SELECT * FROM product_images WHERE product_id = :pid";
        $stmt = $this->query($sql);
        $stmt->bindParam(':pid', $product_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // =========================================================================
    // 3. CÁC HÀM CHO ADMIN (QUẢN TRỊ)
    // =========================================================================

    // Lấy danh sách admin (có search, filter)
    // app/Models/Product.php

    public function getAllAdmin($keyword = null, $cat_id = null)
    {
        $sql = "SELECT p.*, c.name as category_name 
            FROM products p
            LEFT JOIN product_categories c ON p.category_id = c.id
            WHERE 1=1";

        // 👇 LOGIC TÌM KIẾM
        if (!empty($keyword)) {
            $sql .= " AND (p.name LIKE :keyword OR p.sku LIKE :keyword)";
        }

        // 👇 LOGIC LỌC DANH MỤC
        if (!empty($cat_id)) {
            $sql .= " AND p.category_id = :cat_id";
        }

        $sql .= " ORDER BY p.id DESC";

        $stmt = $this->query($sql);

        // Bind giá trị
        if (!empty($keyword)) $stmt->bindValue(':keyword', "%$keyword%");
        if (!empty($cat_id)) $stmt->bindValue(':cat_id', $cat_id);

        $stmt->execute();
        return $stmt->fetchAll();
    }

    
    // Thêm mới và lấy ID
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

    // Cập nhật
    public function update($data)
    {
        $sql = "UPDATE products SET 
                category_id = :cat_id,
                name = :name,
                sku = :sku,
                price = :price,
                sale_price = :sale,
                main_image = :img,
                stock = :stock,
                ingredients = :ingr,
                uses = :uses,
                usage_instruction = :usage,
                note = :note,
                status = :status
                WHERE id = :id";

        $stmt = $this->query($sql);
        return $stmt->execute([
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
            ':status' => $data['status'],
            ':id'     => $data['id']
        ]);
    }

    // Xóa sản phẩm
    public function delete($id)
    {
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $this->query($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Thêm ảnh gallery
    public function addGalleryImage($product_id, $image_url)
    {
        $sql = "INSERT INTO product_images (product_id, image_url) VALUES (:pid, :url)";
        $stmt = $this->query($sql);
        return $stmt->execute([':pid' => $product_id, ':url' => $image_url]);
    }

    // Tìm ảnh gallery để xóa
    public function findGalleryImage($image_id)
    {
        $sql = "SELECT * FROM product_images WHERE id = :id";
        $stmt = $this->query($sql);
        $stmt->execute([':id' => $image_id]);
        return $stmt->fetch();
    }

    // Xóa ảnh gallery
    public function deleteGalleryImage($image_id)
    {
        $sql = "DELETE FROM product_images WHERE id = :id";
        $stmt = $this->query($sql);
        return $stmt->execute([':id' => $image_id]);
    }
}
