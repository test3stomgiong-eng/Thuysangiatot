<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Models\User;
use App\Models\Customer;
class AuthController extends Controller {

    // Hiển thị Form đăng nhập
    public function login() {
        // Nếu đã đăng nhập rồi thì đá vào Dashboard luôn, không cần login nữa
        if (isset($_SESSION['admin_logged_in'])) {
            header("Location: /admin/dashboard");
            exit();
        }

        // View Login đơn giản, không cần Layout Admin (vì chưa vào được)
        // Bạn có thể tạo layout riêng hoặc view trần
        $this->view('Admin/login'); 
    }

    // Xử lý khi bấm nút Đăng nhập
    public function loginPost() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $userModel = new User();
            $user = $userModel->checkLogin($email, $password);

            if ($user) {
                // Kiểm tra quyền (Chỉ admin, sale, warehouse... mới được vào)
                // Khách hàng (customer) không được vào đây
                if (in_array($user->role, ['admin', 'sale', 'warehouse', 'editor'])) {
                    
                    // Lưu Session
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_id'] = $user->id;
                    $_SESSION['admin_name'] = $user->fullname;
                    $_SESSION['admin_role'] = $user->role;

                    header("Location: /admin/dashboard");
                } else {
                    $data['error'] = "Bạn không có quyền truy cập Admin!";
                    $this->view('Admin/login', $data);
                }
            } else {
                $data['error'] = "Email hoặc Mật khẩu không đúng!";
                $this->view('Admin/login', $data);
            }
        }
    }

    // Đăng xuất
    public function logout() {
        unset($_SESSION['admin_logged_in']);
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_name']);
        unset($_SESSION['admin_role']);
        
        header("Location: /admin/login");
    }

  
}