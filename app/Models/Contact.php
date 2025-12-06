<?php

namespace App\Models;

use App\Core\Model;

class Contact extends Model
{
    // Lưu liên hệ mới
    public function create($data)
    {
        $sql = "INSERT INTO contacts (fullname, email, phone, message, created_at) 
                VALUES (:name, :email, :phone, :msg, NOW())";
        $stmt = $this->query($sql);
        return $stmt->execute([
            ':name'  => $data['fullname'],
            ':email' => $data['email'],
            ':phone' => $data['phone'],
            ':msg'   => $data['message']
        ]);
    }

    // Lấy danh sách cho Admin
    public function getAll()
    {
        $sql = "SELECT * FROM contacts ORDER BY id DESC";
        $stmt = $this->query($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Xóa
    public function delete($id)
    {
        $sql = "DELETE FROM contacts WHERE id = :id";
        $stmt = $this->query($sql);
        return $stmt->execute([':id' => $id]);
    }

    // 1. Lấy chi tiết 1 liên hệ
    public function find($id)
    {
        $sql = "SELECT * FROM contacts WHERE id = :id";
        $stmt = $this->query($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // 2. Đánh dấu đã xem (Cập nhật status = 1)
    public function markAsRead($id)
    {
        $sql = "UPDATE contacts SET status = 1 WHERE id = :id";
        $stmt = $this->query($sql);
        return $stmt->execute([':id' => $id]);
    }
}
