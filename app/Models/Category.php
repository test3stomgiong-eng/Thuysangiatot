<?php

namespace App\Models;

use App\Core\Model;

class Category extends Model
{

    // Lấy tất cả danh mục
    // app/Models/Category.php

    public function getAll($keyword = null, $status = null)
    {

        $sql = "SELECT c.*, p.name as parent_name 
            FROM product_categories c
            LEFT JOIN product_categories p ON c.parent_id = p.id
            WHERE 1=1"; // Mẹo: 1=1 để dễ nối chuỗi AND phía sau

        // 1. Lọc theo Tên (Nếu có keyword)
        if (!empty($keyword)) {
            $sql .= " AND c.name LIKE :keyword";
        }

        // 2. Lọc theo Trạng thái (Nếu có chọn)
        if ($status !== null && $status !== '') {
            $sql .= " AND c.status = :status";
        }

        $sql .= " ORDER BY c.id DESC";

        $stmt = $this->query($sql);

        // Bind dữ liệu
        if (!empty($keyword)) {
            $stmt->bindValue(':keyword', '%' . $keyword . '%');
        }
        if ($status !== null && $status !== '') {
            $stmt->bindValue(':status', $status);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Thêm mới danh mục

    public function create($data)
    {
        // 👇 SQL phải có cột parent_id
        $sql = "INSERT INTO product_categories (name, slug, parent_id, description, status) 
            VALUES (:name, :slug, :parent_id, :desc, :status)";

        $stmt = $this->query($sql);

        return $stmt->execute([
            ':name'      => $data['name'],
            ':slug'      => $data['slug'],
            ':parent_id' => $data['parent_id'], // 👈 Bind dữ liệu ở đây
            ':desc'      => $data['description'],
            ':status'    => $data['status']
        ]);
    }

    // Xóa danh mục
    public function delete($id)
    {
        $sql = "DELETE FROM product_categories WHERE id = :id";
        $stmt = $this->query($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Lấy 1 danh mục (để sửa)
    public function find($id)
    {
        $sql = "SELECT * FROM product_categories WHERE id = :id";
        $stmt = $this->query($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Cập nhật danh mục
    public function update($data)
    {
        $sql = "UPDATE product_categories 
            SET name = :name, 
                slug = :slug, 
                parent_id = :parent_id, 
                description = :desc, 
                status = :status 
            WHERE id = :id";

        $stmt = $this->query($sql);

        return $stmt->execute([
            ':name'      => $data['name'],
            ':slug'      => $data['slug'],
            ':parent_id' => $data['parent_id'],
            ':desc'      => $data['description'],
            ':status'    => $data['status'],
            ':id'        => $data['id']
        ]);
    }
}
