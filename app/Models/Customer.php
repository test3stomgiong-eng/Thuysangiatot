<?php
namespace App\Models;
use App\Core\Model;

class Customer extends Model {
    
    // Kiểm tra đăng nhập (Hỗ trợ cả Email và Số điện thoại)
    public function checkLogin($account, $password) {
        // Tìm khách hàng có email HOẶC số điện thoại trùng khớp
        $sql = "SELECT * FROM customers WHERE (email = :account OR phone = :account) AND status = 1 LIMIT 1";
        $stmt = $this->query($sql);
        $stmt->bindParam(':account', $account);
        $stmt->execute();
        
        $customer = $stmt->fetch();

        // Kiểm tra mật khẩu
        if ($customer) {
            // Lưu ý: Thực tế nên dùng password_verify() nếu pass đã mã hóa
            // Ở đây mình so sánh trực tiếp cho giống demo
            if ($password == $customer->password) {
                return $customer;
            }
        }
        return false;
    }

    // Kiểm tra xem email/sđt đã tồn tại chưa (Dùng cho Đăng ký sau này)
    public function exists($phone, $email = null) {
        $sql = "SELECT id FROM customers WHERE phone = :phone OR email = :email";
        $stmt = $this->query($sql);
        $stmt->execute([':phone' => $phone, ':email' => $email]);
        return $stmt->fetch();
    }
}