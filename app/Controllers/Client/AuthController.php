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
            $fullname   = trim($_POST['fullname']);
            $phone      = trim($_POST['phone']);
            $email      = trim($_POST['email']);
            $password   = $_POST['password'];
            $repassword = $_POST['repassword'];

            // --- VALIDATION PHÍA SERVER (LỚP BẢO VỆ CUỐI CÙNG) ---

            // 1. Check rỗng
            if (empty($fullname) || empty($phone) || empty($password)) {
                $data['error'] = "Vui lòng điền đầy đủ thông tin bắt buộc.";
                $this->view('Client/register', $data);
                return;
            }

            // 2. Check định dạng SĐT (Regex giống JS)
            if (!preg_match('/^0[0-9]{9}$/', $phone)) {
                $data['error'] = "Số điện thoại không hợp lệ (Phải 10 số, bắt đầu bằng 0).";
                $this->view('Client/register', $data);
                return;
            }

            // 3. Check định dạng Email
            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $data['error'] = "Địa chỉ Email không hợp lệ.";
                $this->view('Client/register', $data);
                return;
            }

            // 4. Check trùng lặp (Gọi Model)
            $customerModel = new Customer();
            if ($customerModel->exists($phone, $email)) {
                $data['error'] = "Số điện thoại hoặc Email này đã được sử dụng!";
                $this->view('Client/register', $data);
                return;
            }

            // --- NẾU ỔN HẾT THÌ LƯU ---
            $isCreated = $customerModel->register([
                'fullname' => $fullname,
                'phone'    => $phone,
                'email'    => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ]);

            if ($isCreated) {
                echo "<script>alert('Đăng ký thành công!'); window.location.href='/auth/login';</script>";
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
