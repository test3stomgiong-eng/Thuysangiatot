<?php

namespace App\Models;

use App\Core\Model;

class NewsCategory extends Model
{

    // 1. Lấy tất cả danh mục tin tức (Có JOIN để lấy tên cha)
    public function getAll()
    {
        // Giả sử bảng news_categories cũng có cột parent_id
        $sql = "SELECT c.*, p.name as parent_name 
                FROM news_categories c
                LEFT JOIN news_categories p ON c.parent_id = p.id
                ORDER BY c.id DESC";
        $stmt = $this->query($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 2. Thêm mới
    public function create($data)
    {
        $sql = "INSERT INTO news_categories (name, slug, parent_id, status) 
                VALUES (:name, :slug, :parent_id, :status)";
        $stmt = $this->query($sql);
        return $stmt->execute([
            ':name'      => $data['name'],
            ':slug'      => $data['slug'],
            ':parent_id' => $data['parent_id'],
            ':status'    => $data['status']
        ]);
    }

    // 3. Cập nhật
    public function update($data)
    {
        $sql = "UPDATE news_categories SET name=:name, slug=:slug, parent_id=:parent_id, status=:status WHERE id=:id";
        $stmt = $this->query($sql);
        return $stmt->execute([
            ':name'      => $data['name'],
            ':slug'      => $data['slug'],
            ':parent_id' => $data['parent_id'],
            ':status'    => $data['status'],
            ':id'        => $data['id']
        ]);
    }

    // 4. Xóa
    public function delete($id)
    {
        $sql = "DELETE FROM news_categories WHERE id = :id";
        $stmt = $this->query($sql);
        return $stmt->execute([':id' => $id]);
    }

    // 5. Tìm 1 dòng
    public function find($id)
    {
        $sql = "SELECT * FROM news_categories WHERE id = :id";
        $stmt = $this->query($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getTree() {
        // Lấy tất cả danh mục đang hiện (status = 1)
        $sql = "SELECT * FROM news_categories WHERE status = 1 ORDER BY name ASC";
        $stmt = $this->query($sql);
        $stmt->execute();
        $rawData = $stmt->fetchAll();

        // Gọi hàm đệ quy để sắp xếp
        $result = [];
        $this->recursiveSort($rawData, 0, 0, $result);
        
        return $result;
    }

    /**
     * 2. HÀM ĐỆ QUY (Sắp xếp và tính Level)
     */
    private function recursiveSort($source, $parent_id, $level, &$result) {
        if (!empty($source)) {
            foreach ($source as $value) {
                if ($value->parent_id == $parent_id) {
                    // Gán level (0 là Cha, 1 là Con)
                    $value->level = $level;
                    $result[] = $value;
                    
                    // Tìm con của ông này
                    $this->recursiveSort($source, $value->id, $level + 1, $result);
                }
            }
        }
    }
}
