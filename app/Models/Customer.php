<?php

namespace App\Models;

use App\Core\Model;

class Customer extends Model
{

    // Kiểm tra đăng nhập (Hỗ trợ cả Email và Số điện thoại)
    public function checkLogin($account, $password)
    {
        // 1. Tìm user theo Email hoặc SĐT
        $sql = "SELECT * FROM customers WHERE (email = :account OR phone = :account) AND status = 1 LIMIT 1";
        $stmt = $this->query($sql);
        $stmt->bindParam(':account', $account);
        $stmt->execute();

        $customer = $stmt->fetch();

        // 2. Kiểm tra mật khẩu
        if ($customer) {
            // 👇 THAY ĐỔI Ở ĐÂY: Dùng password_verify
            // Hàm này sẽ lấy mật khẩu nhập vào (ví dụ "123456") so sánh với chuỗi mã hóa trong DB
            if (password_verify($password, $customer->password)) {
                return $customer;
            }
        }
        return false;
    }

    public function register($data)
    {
        $sql = "INSERT INTO customers (fullname, phone, email, password, status, created_at) 
                VALUES (:fullname, :phone, :email, :password, 1, NOW())";

        $stmt = $this->query($sql);

        // Thực thi và trả về True/False
        return $stmt->execute([
            ':fullname' => $data['fullname'],
            ':phone'    => $data['phone'],
            ':email'    => $data['email'],
            ':password' => $data['password']
        ]);
    }

    // Kiểm tra xem email/sđt đã tồn tại chưa (Dùng cho Đăng ký sau này)
    public function exists($phone, $email = null)
    {
        $sql = "SELECT id FROM customers WHERE phone = :phone OR email = :email";
        $stmt = $this->query($sql);
        $stmt->execute([':phone' => $phone, ':email' => $email]);
        return $stmt->fetch();
    }
}
