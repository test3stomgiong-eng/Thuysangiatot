<?php
// File: app/Core/Database.php
namespace App\Core;

use PDO;
use PDOException;
use DatabaseConfig;

// Gọi file cấu hình DB (Dùng __DIR__ để trỏ đường dẫn chính xác)
require_once __DIR__ . '/../Config/Database.php';

class Database
{
    protected $conn;

    public function __construct()
    {
        // Chuỗi kết nối (Thêm utf8mb4 để không bị lỗi font tiếng Việt)
        $dsn = "mysql:host=" . DatabaseConfig::DB_HOST . ";dbname=" . DatabaseConfig::DB_NAME . ";charset=utf8mb4";

        try {
            // Tạo kết nối PDO
            $this->conn = new PDO($dsn, DatabaseConfig::DB_USER, DatabaseConfig::DB_PASS);

            // Cấu hình báo lỗi: Nếu sai SQL thì hiện lỗi ngay để mình biết đường sửa
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Cấu hình lấy dữ liệu: Mặc định trả về dạng Object ($item->name)
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            // Nếu sai pass hoặc tên DB thì dừng luôn và báo lỗi
            echo "Lỗi kết nối Database: " . $e->getMessage();
            die();
        }
    }

    // Hàm chuẩn bị câu lệnh SQL (Prepare Statement)
    public function query($sql)
    {
        return $this->conn->prepare($sql);
    }

    // 👇 THÊM HÀM MỚI NÀY VÀO CUỐI CLASS 👇
    public function getConnection()
    {
        return $this->conn;
    }
}
