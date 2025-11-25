<?php
namespace App\Controllers\Client;
use App\Core\Controller;
use App\Models\Customer;

class AuthController extends Controller {

    // ---------------------------------------------------------
    // 1. HIỂN THỊ FORM ĐĂNG KÝ (GET)
    // URL: /auth/register
    // ---------------------------------------------------------
    public function register() {
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
    public function registerPost() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy dữ liệu từ form
            $fullname   = trim($_POST['fullname']);
            $phone      = trim($_POST['phone']);
            $email      = trim($_POST['email']);
            $password   = $_POST['password'];
            $repassword = isset($_POST['repassword']) ? $_POST['repassword'] : ''; // Mật khẩu nhập lại

            // --- KIỂM TRA DỮ LIỆU (VALIDATION) ---
            
            // 1. Kiểm tra rỗng
            if (empty($fullname) || empty($phone) || empty($password)) {
                $data['error'] = "Vui lòng nhập đầy đủ Họ tên, SĐT và Mật khẩu!";
                $this->view('Client/register', $data);
                return;
            }

            // 2. Kiểm tra mật khẩu nhập lại
            if ($password !== $repassword) {
                $data['error'] = "Mật khẩu xác nhận không khớp!";
                $this->view('Client/register', $data);
                return;
            }

            // 3. Gọi Model để kiểm tra trùng lặp
            $customerModel = new Customer();
            
            // Kiểm tra SĐT đã có chưa (Hàm exists này phải có trong Model Customer)
            if ($customerModel->exists($phone)) {
                $data['error'] = "Số điện thoại này đã được đăng ký!";
                $this->view('Client/register', $data);
                return;
            }

            // --- LƯU VÀO DATABASE ---
            
            // Câu lệnh SQL thêm mới
            $sql = "INSERT INTO customers (fullname, phone, email, password, status, created_at) 
                    VALUES (:name, :phone, :email, :pass, 1, NOW())";
            
            $stmt = $customerModel->query($sql);
            $result = $stmt->execute([
                ':name'  => $fullname,
                ':phone' => $phone,
                ':email' => $email,
                ':pass'  => $password 
                // Lưu ý: Thực tế nên mã hóa: password_hash($password, PASSWORD_DEFAULT)
            ]);

            if ($result) {
                // Đăng ký thành công -> Báo JS rồi chuyển về trang Login
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
    public function login() {
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
                    'email'    => $customer->email
                ];
                header("Location: /");
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
    public function logout() {
        unset($_SESSION['customer_user']);
        header("Location: /auth/login"); 
        exit();
    }
}