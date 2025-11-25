<?php

namespace App\Models;

use App\Core\Model;

class Product extends Model
{

    /**
     * 1. Lấy danh sách sản phẩm mới nhất
     * @param int $limit Số lượng cần lấy (mặc định 8)
     */
    public function getNewProducts($limit = 8)
    {
        // Chọn tất cả từ bảng products, điều kiện là đang hiện (status=1)
        // Sắp xếp ID giảm dần (cái nào nhập sau lên đầu)
        $sql = "SELECT * FROM products WHERE status = 1 ORDER BY id DESC LIMIT $limit";

        // Gọi hàm query của Model cha
        $stmt = $this->query($sql);
        $stmt->execute();

        // Trả về danh sách (dạng mảng Object)
        return $stmt->fetchAll();
    }

    /**
     * 2. Lấy sản phẩm đang GIẢM GIÁ (Hot)
     * Điều kiện: status=1 VÀ sale_price > 0
     */
    public function getSaleProducts($limit = 4)
    {
        $sql = "SELECT * FROM products WHERE status = 1 AND sale_price > 0 ORDER BY id DESC LIMIT $limit";

        $stmt = $this->query($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * 3. Lấy chi tiết 1 sản phẩm (Dùng cho trang Detail sau này)
     */
    public function find($id)
    {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM products p 
                LEFT JOIN product_categories c ON p.category_id = c.id
                WHERE p.id = :id";
        $stmt = $this->query($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getGallery($product_id)
    {
        $sql = "SELECT * FROM product_images WHERE product_id = :id ORDER BY sort_order ASC";
        $stmt = $this->query($sql);
        $stmt->bindParam(':id', $product_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getRelatedProducts($category_id, $exclude_id, $limit = 5)
    {
        // Logic: Lấy cùng category_id NHƯNG id phải khác exclude_id
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
}
