<?php

namespace App\Controllers\Client;

use App\Core\Controller;
use App\Models\Customer;
use App\Models\Order;

class UserController extends Controller
{

    public function __construct()
    {
        // Bắt buộc đăng nhập mới được vào
        if (!isset($_SESSION['customer_user'])) {
            header("Location: /auth/login");
            exit();
        }
    }

    // 1. Trang hồ sơ (Profile) & Lịch sử đơn hàng
    public function index()
    {
        $cusModel = new Customer();
        $orderModel = new Order();

        $userId = $_SESSION['customer_user']['id'];

        // Lấy thông tin mới nhất từ DB
        $user = $cusModel->find($userId);

        // Lấy lịch sử mua hàng
        $orders = $orderModel->getOrdersByCustomer($userId);

        $data = [
            'title'  => 'Tài khoản của tôi',
            'user'   => $user,
            'orders' => $orders,
            'css_files' => ['style.css', 'profile.css'] // Tạo thêm file css này cho đẹp
        ];

        $this->view('Client/profile', $data, 'client_layout');
    }

    // 2. Xử lý cập nhật thông tin (POST)
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId   = $_SESSION['customer_user']['id'];
            $fullname = $_POST['fullname'];
            $phone    = $_POST['phone'];
            $email    = $_POST['email'];
            $address  = $_POST['address'];

            $cusModel = new Customer();
            $cusModel->updateInfo($userId, $fullname, $phone, $address, $email);

            // Cập nhật lại Session
            $_SESSION['customer_user']['fullname'] = $fullname;
            $_SESSION['customer_user']['phone'] = $phone;
            $_SESSION['customer_user']['email'] = $email;

            echo "<script>alert('Cập nhật thông tin thành công!'); window.location.href='/user';</script>";
        }
    }

    // 3. XEM CHI TIẾT ĐƠN HÀNG
    public function orderDetail($id)
    {
        $orderModel = new \App\Models\Order();

        // Lấy thông tin đơn
        $order = $orderModel->findOrder($id);

        // 1. Kiểm tra đơn hàng có tồn tại không?
        if (!$order) {
            echo "<script>alert('Đơn hàng không tồn tại!'); window.location.href='/user';</script>";
            return;
        }

        // 2. BẢO MẬT: Kiểm tra đơn này có phải của khách đang đăng nhập không?
        // Nếu ID khách trong đơn KHÁC ID trong session -> Đá về trang user
        if ($order->customer_id != $_SESSION['customer_user']['id']) {
            echo "<script>alert('Bạn không có quyền xem đơn hàng này!'); window.location.href='/user';</script>";
            return;
        }

        // 3. Lấy chi tiết sản phẩm
        $details = $orderModel->getOrderDetails($id);

        $data = [
            'title'   => 'Chi tiết đơn hàng #' . $order->code,
            'order'   => $order,
            'details' => $details,
            'css_files' => ['style.css', 'profile.css']
        ];

        $this->view('Client/user_order_detail', $data, 'client_layout');
    }

    // 3. XỬ LÝ ĐỔI MẬT KHẨU
    public function changePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId      = $_SESSION['customer_user']['id'];
            $oldPass     = $_POST['old_password'];
            $newPass     = $_POST['new_password'];
            $confirmPass = $_POST['confirm_password'];

            $cusModel = new \App\Models\Customer();

            // Lấy thông tin user hiện tại để check pass cũ
            $user = $cusModel->find($userId);

            // 1. Kiểm tra mật khẩu cũ
            if (!password_verify($oldPass, $user->password)) {
                echo "<script>alert('Mật khẩu hiện tại không đúng!'); window.history.back();</script>";
                return;
            }

            // 2. Kiểm tra xác nhận mật khẩu mới
            if ($newPass !== $confirmPass) {
                echo "<script>alert('Mật khẩu xác nhận không khớp!'); window.history.back();</script>";
                return;
            }

            // 3. Kiểm tra độ dài (Tùy chọn)
            if (strlen($newPass) < 6) {
                echo "<script>alert('Mật khẩu mới phải có ít nhất 6 ký tự!'); window.history.back();</script>";
                return;
            }

            // 4. Mã hóa và Lưu
            $hashedPass = password_hash($newPass, PASSWORD_DEFAULT);
            $cusModel->changePassword($userId, $hashedPass);

            // Đăng xuất để người dùng đăng nhập lại với mật khẩu mới
            unset($_SESSION['customer_user']);
            echo "<script>alert('Đổi mật khẩu thành công! Vui lòng đăng nhập lại.'); window.location.href='/auth/login';</script>";
        }
    }
}
