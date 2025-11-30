<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{

    // Kiểm tra đăng nhập
    public function checkLogin($email, $password)
    {
        // 1. Tìm user theo email
        $sql = "SELECT * FROM users WHERE email = :email AND status = 1 LIMIT 1";
        $stmt = $this->query($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch();

        // 2. Nếu có user, kiểm tra password
        if ($user) {
            // Vì trong SQL mẫu lúc đầu mình insert pass là '123456' (chưa mã hóa)
            // Nên tạm thời so sánh trực tiếp. 
            // Sau này bạn nên dùng password_verify() nếu mã hóa MD5/Bcrypt
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }
        return false;
    }
    // 1. Lấy danh sách nhân viên
    public function getAll()
    {
        $sql = "SELECT * FROM users ORDER BY id DESC";
        $stmt = $this->query($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 2. Thêm nhân viên mới
    public function create($data)
    {
        $sql = "INSERT INTO users (fullname, email, password, role, status, created_at) 
                VALUES (:name, :email, :pass, :role, :status, NOW())";

        $stmt = $this->query($sql);
        return $stmt->execute([
            ':name'   => $data['fullname'],
            ':email'  => $data['email'],
            ':pass'   => $data['password'], // Pass đã mã hóa ở Controller
            ':role'   => $data['role'],
            ':status' => $data['status']
        ]);
    }

    // 3. Tìm 1 nhân viên
    public function find($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->query($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // 4. Cập nhật nhân viên
    public function update($data)
    {
        // Nếu password rỗng -> Không update cột password
        if (empty($data['password'])) {
            $sql = "UPDATE users SET fullname=:name, email=:email, role=:role, status=:status WHERE id=:id";
            $params = [
                ':name' => $data['fullname'],
                ':email' => $data['email'],
                ':role' => $data['role'],
                ':status' => $data['status'],
                ':id' => $data['id']
            ];
        } else {
            // Có nhập pass mới -> Update cả password
            $sql = "UPDATE users SET fullname=:name, email=:email, password=:pass, role=:role, status=:status WHERE id=:id";
            $params = [
                ':name' => $data['fullname'],
                ':email' => $data['email'],
                ':pass' => $data['password'],
                ':role' => $data['role'],
                ':status' => $data['status'],
                ':id' => $data['id']
            ];
        }

        $stmt = $this->query($sql);
        return $stmt->execute($params);
    }

    // 5. Xóa nhân viên
    public function delete($id)
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->query($sql);
        return $stmt->execute([':id' => $id]);
    }
}
