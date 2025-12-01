<?php

namespace App\Models;

use App\Core\Model;

class Category extends Model
{
    // 1. Lấy tất cả danh mục (Kèm tên cha)
    public function getAll($keyword = null, $status = null)
    {
        $sql = "SELECT c.*, p.name as parent_name 
            FROM product_categories c
            LEFT JOIN product_categories p ON c.parent_id = p.id
            WHERE 1=1";

        if (!empty($keyword)) {
            $sql .= " AND c.name LIKE :keyword";
        }

        if ($status !== null && $status !== '') {
            $sql .= " AND c.status = :status";
        }

        $sql .= " ORDER BY c.id DESC";

        $stmt = $this->query($sql);

        if (!empty($keyword)) {
            $stmt->bindValue(':keyword', '%' . $keyword . '%');
        }
        if ($status !== null && $status !== '') {
            $stmt->bindValue(':status', $status);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 2. Thêm mới danh mục (Đã bổ sung image và icon_class)
    public function create($data)
    {
        $sql = "INSERT INTO product_categories (name, slug, parent_id, description, status, image, icon_class) 
            VALUES (:name, :slug, :parent_id, :desc, :status, :img, :icon)";

        $stmt = $this->query($sql);

        return $stmt->execute([
            ':name'      => $data['name'],
            ':slug'      => $data['slug'],
            ':parent_id' => $data['parent_id'],
            ':desc'      => $data['description'],
            ':status'    => $data['status'],
            
            // 👇 Hai trường mới thêm
            ':img'       => $data['image'],
            ':icon'      => $data['icon_class']
        ]);
    }

    // 3. Cập nhật danh mục (Đã bổ sung image và icon_class)
    public function update($data)
    {
        $sql = "UPDATE product_categories 
            SET name = :name, 
                slug = :slug, 
                parent_id = :parent_id, 
                description = :desc, 
                status = :status,
                image = :img,        -- 👈 Thêm
                icon_class = :icon   -- 👈 Thêm
            WHERE id = :id";

        $stmt = $this->query($sql);

        return $stmt->execute([
            ':name'      => $data['name'],
            ':slug'      => $data['slug'],
            ':parent_id' => $data['parent_id'],
            ':desc'      => $data['description'],
            ':status'    => $data['status'],
            ':img'       => $data['image'],       // 👈 Bind dữ liệu
            ':icon'      => $data['icon_class'],  // 👈 Bind dữ liệu
            ':id'        => $data['id']
        ]);
    }

    // 4. Xóa danh mục
    public function delete($id)
    {
        $sql = "DELETE FROM product_categories WHERE id = :id";
        $stmt = $this->query($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // 5. Lấy 1 danh mục (để sửa)
    public function find($id)
    {
        $sql = "SELECT * FROM product_categories WHERE id = :id";
        $stmt = $this->query($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // 6. Lấy danh mục SẢN PHẨM (Loại bỏ tin tức ID=6)
    public function getProductCategories()
    {
        $id_tin_tuc = 6; // ID bài viết tổng hợp

        $sql = "SELECT * FROM product_categories 
                WHERE id != :id_news 
                AND parent_id != :id_news 
                ORDER BY id DESC";

        $stmt = $this->query($sql);
        $stmt->bindValue(':id_news', $id_tin_tuc);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // 7. Lấy cây danh mục sản phẩm (Đã sắp xếp và lọc tin tức)
    public function getTreeProductCategories()
    {
        $id_tin_tuc = 6; 
        $sql = "SELECT * FROM product_categories 
                WHERE id != :id_news AND parent_id != :id_news 
                ORDER BY name ASC";

        $stmt = $this->query($sql);
        $stmt->bindValue(':id_news', $id_tin_tuc);
        $stmt->execute();
        $rawData = $stmt->fetchAll();

        $result = [];
        $this->recursiveSort($rawData, 0, 0, $result);

        return $result;
    }

    // 8. Hàm đệ quy sắp xếp
    private function recursiveSort($source, $parent_id, $level, &$result)
    {
        if (!empty($source)) {
            foreach ($source as $key => $value) {
                if ($value->parent_id == $parent_id) {
                    $value->level = $level;
                    $result[] = $value;
                    $this->recursiveSort($source, $value->id, $level + 1, $result);
                }
            }
        }
    }

    // 9. Lấy riêng danh mục TIN TỨC (Nếu cần dùng tạm)
    public function getNewsCategories() {
        $id_tin_tuc = 6; 
        $sql = "SELECT * FROM product_categories 
                WHERE id = :id OR parent_id = :id 
                ORDER BY id DESC";
        
        $stmt = $this->query($sql);
        $stmt->execute([':id' => $id_tin_tuc]);
        return $stmt->fetchAll();
    }
}