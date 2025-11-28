<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Order;

class OrderController extends Controller
{

    public function __construct()
    {
        // Check quyền Admin
        if (!isset($_SESSION['customer_user']) || $_SESSION['customer_user']['role'] !== 'admin') {
            header("Location: /auth/login");
            exit();
        }
    }

    // 1. DANH SÁCH ĐƠN HÀNG
    public function index()
    {
        $orderModel = new Order();

        // Lấy dữ liệu từ bộ lọc
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : null;
        $status  = isset($_GET['status']) ? $_GET['status'] : null;

        $orders = $orderModel->getAllOrders($keyword, $status);

        $data = [
            'title'  => 'Quản lý đơn hàng',
            'orders' => $orders
        ];
        $this->view('Admin/orders', $data, 'admin_layout');
    }

    // 2. CHI TIẾT ĐƠN HÀNG
    public function detail($id)
    {
        $orderModel = new Order();

        // Lấy thông tin chung
        $order = $orderModel->findOrder($id);

        if (!$order) {
            header("Location: /admin/order");
            exit();
        }

        // Lấy danh sách sản phẩm
        $details = $orderModel->getOrderDetails($id);

        $data = [
            'title'   => 'Chi tiết đơn hàng #' . $order->code,
            'order'   => $order,
            'details' => $details
        ];
        $this->view('Admin/order-detail', $data, 'admin_layout');
    }

    // 3. CẬP NHẬT TRẠNG THÁI
    public function updateStatus($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $status = $_POST['status'];

            $orderModel = new Order();
            $orderModel->updateStatus($id, $status);
            // 👇 THÊM DÒNG NÀY: Lưu thông báo vào session
            $_SESSION['flash_success'] = "Đã cập nhật trạng thái đơn hàng thành công!";
            // Cập nhật xong thì quay lại trang chi tiết
            header("Location: /admin/order/detail/$id");
        }
    }
}
