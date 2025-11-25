<?php
namespace App\Controllers\Admin;
use App\Core\Controller;

class DashboardController extends Controller {
    
    public function index() {
        // 1. Chuẩn bị dữ liệu (Sau này sẽ lấy thống kê từ DB)
        $data = [
            'title' => 'Tổng quan - Quản trị viên',
            'page'  => 'dashboard' // Để active menu
        ];

        // 2. Gọi View Admin
        // View nội dung: Admin/dashboard.php
        // Layout khung: admin_layout.php
        $this->view('Admin/dashboard', $data, 'admin_layout');
    }
}