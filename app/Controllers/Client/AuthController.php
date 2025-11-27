<?php

namespace App\Controllers\Client;

use App\Core\Controller;
use App\Models\Customer;

class AuthController extends Controller
{

    // ---------------------------------------------------------
    // 1. HIỂN THỊ FORM ĐĂNG KÝ (GET)
    // URL: /auth/register
    // ---------------------------------------------------------
    public function register()
    {
        // Nếu đã đăng nhập rồi thì đá về trang chủ
        if (isset($_SESSION['customer_user'])) {
            header("Location: /");
            exit();
        }

        $data = [
            'title'     => 'Đăng ký thành viên - TS AQUA',
            'css_files' => ['style.css', 'login.css'] // Dùng chung CSS với trang login
        ];

        // Gọi view register
        $this->view('Client/register', $data);
    }

    // ---------------------------------------------------------
    // 2. XỬ LÝ ĐĂNG KÝ (POST)
    // URL: /auth/registerPost
    // ---------------------------------------------------------
    public function registerPost()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 1. Lấy dữ liệu
            $fullname   = trim($_POST['fullname']);
            $phone      = trim($_POST['phone']);
            $email      = trim($_POST['email']);
            $password   = $_POST['password'];
            $repassword = isset($_POST['repassword']) ? $_POST['repassword'] : '';

            // 2. Validate dữ liệu
            if (empty($fullname) || empty($phone) || empty($password)) {
                $data['error'] = "Vui lòng nhập đầy đủ Họ tên, SĐT và Mật khẩu!";
                $this->view('Client/register', $data);
                return;
            }

            if ($password !== $repassword) {
                $data['error'] = "Mật khẩu xác nhận không khớp!";
                $this->view('Client/register', $data);
                return;
            }

            // 3. Gọi Model xử lý
            $customerModel = new Customer();

            // Kiểm tra trùng SĐT
            if ($customerModel->exists($phone)) {
                $data['error'] = "Số điện thoại này đã được đăng ký!";
                $this->view('Client/register', $data);
                return;
            }

            // --- GỌI HÀM REGISTER TỪ MODEL (THAY VÌ VIẾT SQL Ở ĐÂY) ---
            $isCreated = $customerModel->register([
                'fullname' => $fullname,
                'phone'    => $phone,
                'email'    => $email,

                // 👇 THAY ĐỔI Ở ĐÂY: Mã hóa mật khẩu trước khi lưu
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ]);

            // 4. Kiểm tra kết quả
            if ($isCreated) {
                echo "<script>
                        alert('Chúc mừng! Đăng ký tài khoản thành công. Vui lòng đăng nhập.'); 
                        window.location.href='/auth/login';
                      </script>";
            } else {
                $data['error'] = "Lỗi hệ thống: Không thể tạo tài khoản.";
                $this->view('Client/register', $data);
            }
        }
    }

    // ---------------------------------------------------------
    // 3. HIỂN THỊ FORM ĐĂNG NHẬP (GET)
    // URL: /auth/login
    // ---------------------------------------------------------
    public function login()
    {
        // Nếu đã đăng nhập thì đá về trang chủ
        if (isset($_SESSION['customer_user'])) {
            header("Location: /");
            exit();
        }

        $data = [
            'title'     => 'Đăng nhập - TS AQUA',
            'css_files' => ['style.css', 'login.css']
        ];

        // Xử lý khi bấm nút Đăng nhập (POST) -> Gộp chung vào hàm login luôn cho tiện
        // Hoặc tách ra loginPost nếu muốn (như trong App.php bạn cấu hình)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $account  = $_POST['account'];
            $password = $_POST['password'];

            // Gọi Model kiểm tra
            $customerModel = new Customer();
            $customer = $customerModel->checkLogin($account, $password);

            if ($customer) {
                // --- ĐĂNG NHẬP THÀNH CÔNG ---
                $_SESSION['customer_user'] = [
                    'id'       => $customer->id,
                    'fullname' => $customer->fullname,
                    'phone'    => $customer->phone,
                    'email'    => $customer->email,
                    'role'     => $customer->role
                ];
                if ($customer->role == 'admin') {
                    header("Location: /admin/dashboard");
                } else {
                    // Nếu là Khách -> Về trang chủ mua hàng
                    header("Location: /");
                }
                exit();
            } else {
                // --- ĐĂNG NHẬP THẤT BẠI ---
                $data['error'] = "Tài khoản hoặc mật khẩu không chính xác!";
                $this->view('Client/login', $data);
            }
        } else {
            // GET: Hiển thị form
            $this->view('Client/login', $data);
        }
    }

    // ---------------------------------------------------------
    // 4. XỬ LÝ ĐĂNG XUẤT
    // URL: /auth/logout
    // ---------------------------------------------------------
    public function logout()
    {
        unset($_SESSION['customer_user']);
        header("Location: /auth/login");
        exit();
    }
}
