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

    /**
     * 1. Lấy danh sách khách hàng cho Admin (Có tìm kiếm)
     */
    public function getAllAdmin($keyword = null)
    {
        // Chỉ lấy những user có role là customer
        $sql = "SELECT * FROM customers WHERE role = 'customer'";

        if (!empty($keyword)) {
            $sql .= " AND (fullname LIKE :kw OR phone LIKE :kw OR email LIKE :kw)";
        }

        $sql .= " ORDER BY id DESC";

        $stmt = $this->query($sql);
        if (!empty($keyword)) {
            $stmt->bindValue(':kw', "%$keyword%");
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * 2. Đổi trạng thái (Khóa / Mở khóa)
     * $status: 1 (Hoạt động), 0 (Bị khóa)
     */
    public function updateStatus($id, $status)
    {
        $sql = "UPDATE customers SET status = :status WHERE id = :id";
        $stmt = $this->query($sql);
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    /**
     * 3. Xóa khách hàng
     */
    public function delete($id)
    {
        $sql = "DELETE FROM customers WHERE id = :id";
        $stmt = $this->query($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Lấy thông tin 1 khách hàng theo ID
    public function find($id)
    {
        $sql = "SELECT * FROM customers WHERE id = :id";
        $stmt = $this->query($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getEmployees($keyword = null)
    {
        // Lấy tất cả ai KHÔNG PHẢI là customer
        $sql = "SELECT * FROM customers WHERE role != 'customer'";

        if (!empty($keyword)) {
            $sql .= " AND (fullname LIKE :kw OR email LIKE :kw OR phone LIKE :kw)";
        }

        $sql .= " ORDER BY id DESC";

        $stmt = $this->query($sql);
        if (!empty($keyword)) {
            $stmt->bindValue(':kw', "%$keyword%");
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function checkDuplicate($phone, $email, $exclude_id = null)
    {
        $sql = "SELECT count(*) as total FROM customers WHERE (phone = :phone OR email = :email)";

        // Nếu đang sửa (có exclude_id) thì không check chính dòng đó
        if ($exclude_id) {
            $sql .= " AND id != :id";
        }

        $stmt = $this->query($sql);

        $params = [':phone' => $phone, ':email' => $email];
        if ($exclude_id) {
            $params[':id'] = $exclude_id;
        }

        $stmt->execute($params);
        $row = $stmt->fetch();

        return $row->total > 0; // Trả về True nếu có trùng
    }
    /**
     * 2. Thêm mới Nhân viên (Có chọn Role)
     */
    public function createEmployee($data)
    {
        $sql = "INSERT INTO customers (fullname, email, password, phone, role, status, created_at) 
                VALUES (:name, :email, :pass, :phone, :role, :status, NOW())";

        $stmt = $this->query($sql);
        return $stmt->execute([
            ':name'   => $data['fullname'],
            ':email'  => $data['email'],
            ':pass'   => $data['password'], // Đã hash
            ':phone'  => $data['phone'],
            ':role'   => $data['role'],     // Admin/Sale...
            ':status' => $data['status']
        ]);
    }

    /**
     * 3. Cập nhật Nhân viên
     */
    public function updateEmployee($data)
    {
        if (empty($data['password'])) {
            // Không đổi pass
            $sql = "UPDATE customers SET fullname=:name, email=:email, phone=:phone, role=:role, status=:status WHERE id=:id";
            $params = [
                ':name' => $data['fullname'],
                ':email' => $data['email'],
                ':phone' => $data['phone'],
                ':role' => $data['role'],
                ':status' => $data['status'],
                ':id' => $data['id']
            ];
        } else {
            // Có đổi pass
            $sql = "UPDATE customers SET fullname=:name, email=:email, password=:pass, phone=:phone, role=:role, status=:status WHERE id=:id";
            $params = [
                ':name' => $data['fullname'],
                ':email' => $data['email'],
                ':pass' => $data['password'],
                ':phone' => $data['phone'],
                ':role' => $data['role'],
                ':status' => $data['status'],
                ':id' => $data['id']
            ];
        }

        $stmt = $this->query($sql);
        return $stmt->execute($params);
    }

    // 1. Tìm khách hàng theo Số điện thoại
    public function findByPhone($phone) {
        $sql = "SELECT * FROM customers WHERE phone = :phone";
        $stmt = $this->query($sql);
        $stmt->execute([':phone' => $phone]);
        return $stmt->fetch();
    }

    // 2. Cập nhật thông tin liên hệ (Dùng khi Checkout)
    public function updateContactInfo($id, $fullname, $phone, $address) {
        // Chỉ cập nhật Tên, SĐT, Địa chỉ
        $sql = "UPDATE customers SET fullname = :name, phone = :phone, address = :addr WHERE id = :id";
        $stmt = $this->query($sql);
        return $stmt->execute([
            ':name' => $fullname,
            ':phone' => $phone,
            ':addr' => $address,
            ':id'   => $id
        ]);
    }
}
