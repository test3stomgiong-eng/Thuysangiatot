<?php
namespace App\Core;

// 1. XÓA dòng "use App\Core\Database;" đi (vì cùng thư mục rồi)

class Model {
    protected $db;
    protected $conn;

    public function __construct() {
        // 2. Thêm dòng này để nạp file Database thủ công (Fix lỗi không tìm thấy class)
        require_once __DIR__ . '/Database.php';
        
        // 3. Khởi tạo
        $this->db = new Database();
    }

    // Hàm lấy tất cả dữ liệu
    public function all($table) {
        $stmt = $this->db->query("SELECT * FROM $table");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Hàm query tùy chỉnh
    public function query($sql) {
        return $this->db->query($sql);
    }
}