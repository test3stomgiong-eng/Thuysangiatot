<?php

namespace App\Controllers\Client;

use App\Core\Controller;
use App\Models\Customer;
use App\Helpers\Mailer;

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

    // ...

    // 1. HIỆN FORM QUÊN MẬT KHẨU
    public function forgotPassword()
    {
        $data = [
            'title'     => 'Quên mật khẩu',

            // 👇 THÊM DÒNG NÀY VÀO ĐỂ NHẬN CSS
            'css_files' => ['style.css', 'login.css']
        ];

        $this->view('Client/forgot_password', $data, 'client_layout');
    }

    // 2. XỬ LÝ TẠO TOKEN VÀ GỬI MAIL
    public function sendResetLink()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $cusModel = new \App\Models\Customer();

            // 1. Kiểm tra email
            if (!$cusModel->exists('', $email)) {
                $data = ['title' => 'Quên mật khẩu', 'error' => 'Email không tồn tại!', 'css_files' => ['style.css', 'login.css']];
                $this->view('Client/forgot_password', $data, 'client_layout');
                return;
            }

            // 2. Tạo Token
            $token = bin2hex(random_bytes(32));

            // 3. Lưu Token vào DB (CHỈ LƯU TOKEN, KHÔNG ĐỔI PASS)
            // Hàm này trong Model chỉ update cột 'reset_token' và 'reset_expiry'
            $cusModel->saveResetToken($email, $token);

            // 4. Tạo Link
            // (Code lấy domain tự động như bài trước)
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $domainName = $_SERVER['HTTP_HOST'];
            $link = $protocol . $domainName . "/auth/reset/" . $token;

            // 5. Gửi Mail
            $subject = "Hỗ trợ khôi phục mật khẩu tài khoản";
            $body = "
  <div style='font-family:Arial,Helvetica,sans-serif;background:#f4f6f8;padding:20px;'>
    <div style='max-width:600px;margin:auto;background:#ffffff;border-radius:8px;padding:24px;box-shadow:0 4px 12px rgba(0,0,0,0.08);'>


      <p style='color:#374151;line-height:1.6;margin-bottom:16px;'>
        Chúng tôi nhận được yêu cầu đặt lại mật khẩu của bạn. 
        Nhấn nút bên dưới để tiếp tục tạo mật khẩu mới.
      </p>

      <p style='text-align:center;margin:28px 0;'>
        <a href='$link' 
           style='background:#1e88e5;color:#fff;padding:12px 22px;
                  text-decoration:none;border-radius:8px;font-weight:600;display:inline-block;'>
          ĐẶT LẠI MẬT KHẨU
        </a>
      </p>

      <p style='color:#6b7280;font-size:14px;line-height:1.6;margin-bottom:16px;'>
        Liên kết chỉ có hiệu lực trong <strong>15 phút</strong>.
      </p>

      <p style='color:#6b7280;font-size:14px;line-height:1.6;margin-bottom:6px;'>
        Nếu nút không hoạt động, hãy sao chép liên kết bên dưới và dán vào trình duyệt:
      </p>

      <p style='word-break:break-all;font-size:13px;color:#1e88e5;margin-bottom:20px;'>
        <a href='$link' style='color:#1e88e5;text-decoration:underline;'>$link</a>
      </p>

      <hr style='border:0;border-top:1px solid #eee;margin:20px 0;'>

      <p style='color:#374151;line-height:1.6;margin-bottom:0;'>
        Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.
      </p>

      <p style='margin-top:20px;color:#374151;'>
        Trân trọng,<br>
      </p>

    </div>
  </div>
";


            $result = \App\Helpers\Mailer::send($email, $subject, $body);

            // ... (Phần trả về View giữ nguyên)
            $data = ['title' => 'Quên mật khẩu', 'css_files' => ['style.css', 'login.css']];
            if ($result === true) {
                $data['success'] = 'Đã gửi link khôi phục vào Email. Mật khẩu cũ vẫn sử dụng bình thường.';
            } else {
                $data['error'] = 'Gửi mail thất bại: ' . $result;
            }
            $this->view('Client/forgot_password', $data, 'client_layout');
        }
    }

    // 3. MÀN HÌNH NHẬP MẬT KHẨU MỚI (Khi khách bấm link trong mail)
    // Router sẽ truyền token trên URL vào biến $token
    public function reset($token = null)
    {
        $cusModel = new Customer();

        // Dùng Token để tìm xem đây là khách hàng nào
        $user = $cusModel->checkToken($token);

        if (!$user) {
            die("Đường dẫn không hợp lệ hoặc đã hết hạn!");
        }

        $data = [
            'title'     => 'Đặt lại mật khẩu',
            'token'     => $token, // Gửi token xuống view để lát form gửi ngược lại
            'css_files' => ['style.css', 'login.css']
        ];
        $this->view('Client/reset_password_form', $data, 'client_layout');
    }

    // 4. LƯU MẬT KHẨU MỚI
    public function saveNewPass()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $token  = $_POST['token']; // Nhận lại token từ form
            $pass   = $_POST['password'];
            $repass = $_POST['repassword'];

            if ($pass !== $repass) {
                echo "<script>alert('Mật khẩu không khớp!'); window.history.back();</script>";
                return;
            }

            $cusModel = new Customer();

            // Check token lần cuối cho chắc ăn
            $user = $cusModel->checkToken($token);

            if ($user) {
                // Mã hóa và lưu mật khẩu mới cho User tìm được
                $hashedPass = password_hash($pass, PASSWORD_DEFAULT);
                $cusModel->updatePasswordByToken($user->id, $hashedPass);

                echo "<script>alert('Đổi mật khẩu thành công! Mời đăng nhập.'); window.location.href='/auth/login';</script>";
            } else {
                echo "Lỗi xác thực! Phiên làm việc đã hết hạn.";
            }
        }
    }
}
