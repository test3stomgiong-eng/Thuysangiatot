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
}
