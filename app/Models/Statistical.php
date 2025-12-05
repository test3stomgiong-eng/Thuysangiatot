<?php
namespace App\Models;
use App\Core\Model;

class Statistical extends Model {
    
    // 1. Đếm tổng số dòng của bảng bất kỳ
    public function count($table) {
        $stmt = $this->query("SELECT COUNT(*) as total FROM $table");
        $stmt->execute();
        return $stmt->fetch()->total;
    }

    // 2. Tính tổng doanh thu (Chỉ tính đơn đã Hoàn thành)
    public function getRevenue() {
        $sql = "SELECT SUM(total_money) as total FROM orders WHERE status = 'completed'";
        $stmt = $this->query($sql);
        $stmt->execute();
        return $stmt->fetch()->total ?? 0;
    }

    // 3. Lấy 5 đơn hàng mới nhất
    public function getRecentOrders() {
        $sql = "SELECT * FROM orders ORDER BY id DESC LIMIT 5";
        $stmt = $this->query($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 4. Lấy sản phẩm sắp hết hàng (Tồn kho < 10)
    public function getLowStockProducts() {
        $sql = "SELECT * FROM products WHERE stock < 10 AND status = 1 ORDER BY stock ASC LIMIT 5";
        $stmt = $this->query($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}