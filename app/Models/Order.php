<?php
namespace App\Models;
use App\Core\Model;

class Order extends Model {
    
    // 1. Lấy tất cả đơn hàng (Có tìm kiếm + Lọc trạng thái)
    public function getAllOrders($keyword = null, $status = null) {
        $sql = "SELECT * FROM orders WHERE 1=1";

        // Tìm theo Mã đơn, Tên khách hoặc SĐT
        if (!empty($keyword)) {
            $sql .= " AND (code LIKE :kw OR customer_name LIKE :kw OR customer_phone LIKE :kw)";
        }

        // Lọc theo trạng thái (pending, shipping, completed, cancelled)
        if (!empty($status)) {
            $sql .= " AND status = :status";
        }

        $sql .= " ORDER BY id DESC"; // Đơn mới nhất lên đầu
        
        $stmt = $this->query($sql);
        
        if (!empty($keyword)) $stmt->bindValue(':kw', "%$keyword%");
        if (!empty($status))  $stmt->bindValue(':status', $status);
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 2. Lấy thông tin 1 đơn hàng (Header)
    public function findOrder($id) {
        $sql = "SELECT * FROM orders WHERE id = :id";
        $stmt = $this->query($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // 3. Lấy chi tiết sản phẩm trong đơn (Items)
    // Join với bảng products để lấy thêm hình ảnh, SKU
    public function getOrderDetails($order_id) {
        $sql = "SELECT od.*, p.main_image, p.sku 
                FROM order_details od
                LEFT JOIN products p ON od.product_id = p.id
                WHERE od.order_id = :oid";
        
        $stmt = $this->query($sql);
        $stmt->execute([':oid' => $order_id]);
        return $stmt->fetchAll();
    }

    // 4. Cập nhật trạng thái đơn hàng
    public function updateStatus($id, $status) {
        $sql = "UPDATE orders SET status = :status WHERE id = :id";
        $stmt = $this->query($sql);
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }
}