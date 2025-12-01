<?php

namespace App\Models;

use App\Core\Model;

class News extends Model
{

    // 1. Lấy danh sách tin tức (Admin)
    public function getAllAdmin($keyword = null)
    {

        // 👇 SỬA CÂU SQL NÀY
        $sql = "SELECT n.*, 
                       c.name as category_name, 
                       u.fullname as author_name 
                FROM news n
                
                -- 1. JOIN VỚI BẢNG DANH MỤC TIN TỨC (MỚI)
                LEFT JOIN news_categories c ON n.category_id = c.id
                
                -- 2. JOIN VỚI BẢNG KHÁCH HÀNG (VÌ ADMIN NẰM Ở ĐÂY)
                LEFT JOIN customers u ON n.author_id = u.id
                
                WHERE 1=1";

        if (!empty($keyword)) {
            $sql .= " AND n.title LIKE :kw";
        }

        $sql .= " ORDER BY n.id DESC";

        $stmt = $this->query($sql);
        if (!empty($keyword)) $stmt->bindValue(':kw', "%$keyword%");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 2. Thêm mới
    public function create($data)
    {
        $sql = "INSERT INTO news (category_id, title, slug, thumbnail, summary, content, author_id, status, created_at) 
                VALUES (:cat_id, :title, :slug, :thumb, :summary, :content, :author, :status, NOW())";

        $stmt = $this->query($sql);
        return $stmt->execute([
            ':cat_id'  => $data['category_id'],
            ':title'   => $data['title'],
            ':slug'    => $data['slug'],
            ':thumb'   => $data['thumbnail'],
            ':summary' => $data['summary'],
            ':content' => $data['content'],
            ':author'  => $data['author_id'],
            ':status'  => $data['status']
        ]);
    }

    // 3. Lấy 1 bài viết (Sửa)
    public function find($id)
    {
        $sql = "SELECT * FROM news WHERE id = :id";
        $stmt = $this->query($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // 4. Cập nhật
    public function update($data)
    {
        $sql = "UPDATE news SET 
                category_id = :cat_id, title = :title, slug = :slug, 
                thumbnail = :thumb, summary = :summary, content = :content, 
                status = :status 
                WHERE id = :id";

        $stmt = $this->query($sql);
        return $stmt->execute([
            ':cat_id'  => $data['category_id'],
            ':title'   => $data['title'],
            ':slug'    => $data['slug'],
            ':thumb'   => $data['thumbnail'],
            ':summary' => $data['summary'],
            ':content' => $data['content'],
            ':status'  => $data['status'],
            ':id'      => $data['id']
        ]);
    }

    // 5. Xóa
    public function delete($id)
    {
        $sql = "DELETE FROM news WHERE id = :id";
        $stmt = $this->query($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Trong Model News
    public function getLatestNews($limit = 4)
    {
        $sql = "SELECT * FROM news WHERE status = 1 ORDER BY id DESC LIMIT $limit";
        $stmt = $this->query($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
