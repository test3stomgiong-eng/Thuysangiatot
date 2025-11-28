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

    public function getProductCategories()
    {
        // ID của danh mục Bài Viết (Tin tức) cần loại bỏ
        $id_tin_tuc = 1;

        // Câu SQL: Lấy tất cả TRỪ ông tin tức (id != 6) VÀ TRỪ con của ông tin tức (parent_id != 6)
        $sql = "SELECT * FROM product_categories 
                WHERE id != :id_news 
                AND parent_id != :id_news 
                ORDER BY id DESC";

        $stmt = $this->query($sql);
        $stmt->bindValue(':id_news', $id_tin_tuc);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * 1. HÀM CHÍNH: Lấy danh sách cây danh mục sản phẩm (Đã sắp xếp)
     */
    public function getTreeProductCategories()
    {
        // Lấy dữ liệu thô (Đã lọc bỏ tin tức như bước trước)
        $id_tin_tuc = 6; // ID bài viết tổng hợp
        $sql = "SELECT * FROM product_categories 
                WHERE id != :id_news AND parent_id != :id_news 
                ORDER BY name ASC"; // Sắp xếp tên A-Z trước

        $stmt = $this->query($sql);
        $stmt->bindValue(':id_news', $id_tin_tuc);
        $stmt->execute();
        $rawData = $stmt->fetchAll();

        // Gọi hàm đệ quy để sắp xếp lại
        $result = [];
        $this->recursiveSort($rawData, 0, 0, $result);

        return $result;
    }

    /**
     * 2. HÀM PHỤ: Thuật toán đệ quy
     * $source: Mảng dữ liệu thô
     * $parent_id: Đang tìm con của ai?
     * $level: Cấp độ thụt đầu dòng (0, 1, 2...)
     */
    private function recursiveSort($source, $parent_id, $level, &$result)
    {
        if (!empty($source)) {
            foreach ($source as $key => $value) {
                if ($value->parent_id == $parent_id) {
                    // Gán thêm thuộc tính level để View biết đường thụt dòng
                    $value->level = $level;
                    $result[] = $value;

                    // Tiếp tục tìm con của ông này (Level tăng lên 1)
                    $this->recursiveSort($source, $value->id, $level + 1, $result);
                }
            }
        }
    }
}
