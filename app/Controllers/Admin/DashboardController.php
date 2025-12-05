<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Models\Statistical; // 👈 NHỚ THÊM DÒNG NÀY

class DashboardController extends Controller {

    public function __construct() {
        // 1. Kiểm tra đăng nhập
        if (!isset($_SESSION['customer_user'])) {
            header("Location: /auth/login");
            exit();
        }

        // 2. Kiểm tra quyền Admin
        if ($_SESSION['customer_user']['role'] !== 'admin') {
            echo "<script>alert('Bạn không có quyền truy cập Admin!'); window.location.href='/';</script>";
            exit();
        }
    }

    public function index() {
        // Khởi tạo Model Thống kê
        $statModel = new Statistical();

        // 1. Lấy các con số tổng quan
        $totalOrders    = $statModel->count('orders');
        $totalProducts  = $statModel->count('products');
        
        // Đếm khách hàng (Trừ đi 1 admin ra cho chuẩn xác hơn)
        $totalCustomers = $statModel->count('customers'); 
        if($totalCustomers > 0) $totalCustomers = $totalCustomers - 1; 

        $revenue        = $statModel->getRevenue();

        // 2. Lấy danh sách bảng biểu
        $recentOrders = $statModel->getRecentOrders();      // 5 đơn mới nhất
        $lowStock     = $statModel->getLowStockProducts();  // Sản phẩm sắp hết hàng

        // 3. Gửi sang View
        $data = [
            'title'          => 'Dashboard Quản Trị',
            'count_order'    => $totalOrders,
            'count_product'  => $totalProducts,
            'count_user'     => $totalCustomers,
            'revenue'        => $revenue,
            'recent_orders'  => $recentOrders,
            'low_stock'      => $lowStock
        ];

        $this->view('Admin/dashboard', $data, 'admin_layout');
    }
}