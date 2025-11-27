<?php
namespace App\Controllers\Admin;
use App\Core\Controller;

class DashboardController extends Controller {

    public function __construct() {
        // 1. Kiểm tra đã đăng nhập chưa?
        if (!isset($_SESSION['customer_user'])) {
            header("Location: /auth/login");
            exit();
        }

        // 2. Kiểm tra có phải ADMIN không? (Quan trọng)
        // Nếu role không phải admin -> Đuổi về trang chủ
        if ($_SESSION['customer_user']['role'] !== 'admin') {
            echo "<script>alert('Bạn không có quyền truy cập Admin!'); window.location.href='/';</script>";
            exit();
        }
    }

    public function index() {
        // Code hiển thị Dashboard...
        $data = ['title' => 'Dashboard Quản Trị'];
        $this->view('Admin/dashboard', $data, 'admin_layout');
    }
}