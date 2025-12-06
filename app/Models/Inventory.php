<?php

namespace App\Models;

use App\Core\Model;

class Inventory extends Model
{
    // 1. Lấy danh sách phiếu nhập (CÓ TÌM KIẾM)
    public function getAllReceipts($keyword = null)
    {
        $sql = "SELECT r.*, u.fullname as user_name 
                FROM inventory_receipts r
                LEFT JOIN customers u ON r.user_id = u.id
                WHERE 1=1";

        // Thêm điều kiện tìm kiếm theo Mã phiếu
        if (!empty($keyword)) {
            $sql .= " AND r.code LIKE :kw";
        }

        $sql .= " ORDER BY r.id DESC";

        $stmt = $this->query($sql);

        if (!empty($keyword)) {
            $stmt->bindValue(':kw', "%$keyword%");
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 2. Lấy thông tin chung của 1 phiếu (Header: Mã, Ngày, Tổng tiền...)
    public function findReceipt($id)
    {
        $sql = "SELECT r.*, u.fullname as user_name 
                FROM inventory_receipts r
                LEFT JOIN customers u ON r.user_id = u.id
                WHERE r.id = :id";
        $stmt = $this->query($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // 3. Lấy chi tiết sản phẩm trong phiếu (Items: Tên, SKU, Giá nhập...)
    public function getReceiptDetails($receipt_id)
    {
        $sql = "SELECT d.*, p.name as product_name, p.sku, p.main_image 
                FROM inventory_details d
                LEFT JOIN products p ON d.product_id = p.id
                WHERE d.receipt_id = :rid";
        $stmt = $this->query($sql);
        $stmt->execute([':rid' => $receipt_id]);
        return $stmt->fetchAll();
    }

    // 4. TẠO PHIẾU NHẬP (Trả về ID phiếu vừa tạo)
    public function createReceipt($data)
    {
        $sql = "INSERT INTO inventory_receipts (code, user_id, total_money, note, created_at) 
                VALUES (:code, :uid, :total, :note, NOW())";

        $stmt = $this->query($sql);
        $stmt->execute([
            ':code'  => $data['code'],
            ':uid'   => $data['user_id'],
            ':total' => $data['total_money'],
            ':note'  => $data['note']
        ]);

        // Trả về ID để dùng cho việc lưu chi tiết
        return $this->db->getConnection()->lastInsertId();
    }

    // 5. LƯU CHI TIẾT & CẬP NHẬT KHO (Transaction an toàn nên được xử lý ở Controller hoặc tại đây nếu gộp)
    public function addDetailAndUpdateStock($receiptId, $productId, $prodName, $qty, $price)
    {
        // A. Lưu vào bảng chi tiết nhập kho
        $sql = "INSERT INTO inventory_details (receipt_id, product_id, product_name, quantity, import_price) 
                VALUES (:rid, :pid, :name, :qty, :price)";
        $stmt = $this->query($sql);
        $stmt->execute([
            ':rid'   => $receiptId,
            ':pid'   => $productId,
            ':name'  => $prodName,
            ':qty'   => $qty,
            ':price' => $price
        ]);

        // B. Cộng dồn số lượng vào kho (Bảng Products)
        // Lưu ý: Nếu muốn quản lý chặt chẽ hơn, nên lưu cả lịch sử giá vốn mới vào bảng products
        $sqlStock = "UPDATE products SET stock = stock + :qty WHERE id = :pid";
        $stmtStock = $this->query($sqlStock);
        $stmtStock->execute([':qty' => $qty, ':pid' => $productId]);
    }
}