<?php

namespace App\Models;

use App\Core\Model;

class Inventory extends Model
{

    // 1. Lấy danh sách phiếu nhập
    public function getAllReceipts()
    {
        $sql = "SELECT r.*, u.fullname as user_name 
                FROM inventory_receipts r
                LEFT JOIN customers u ON r.user_id = u.id
                ORDER BY r.id DESC";
        $stmt = $this->query($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 2. Lấy chi tiết 1 phiếu
    public function getReceiptDetail($id)
    {
        $sql = "SELECT d.*, p.sku 
                FROM inventory_details d
                LEFT JOIN products p ON d.product_id = p.id
                WHERE d.receipt_id = :id";
        $stmt = $this->query($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetchAll();
    }

    // 3. TẠO PHIẾU NHẬP (Trả về ID)
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

        return $this->db->getConnection()->lastInsertId();
    }

    // 4. LƯU CHI TIẾT & CẬP NHẬT KHO (Quan trọng)
    public function addDetailAndUpdateStock($receiptId, $productId, $prodName, $qty, $price)
    {
        // A. Lưu vào bảng chi tiết
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
        $sqlStock = "UPDATE products SET stock = stock + :qty WHERE id = :pid";
        $stmtStock = $this->query($sqlStock);
        $stmtStock->execute([':qty' => $qty, ':pid' => $productId]);
    }
}
