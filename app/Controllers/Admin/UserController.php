<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Models\Customer; // Dùng chung Model với khách hàng

class UserController extends Controller {

    public function __construct() {
        // Chỉ Admin mới được vào
        if (!isset($_SESSION['customer_user']) || $_SESSION['customer_user']['role'] !== 'admin') {
            header("Location: /"); exit();
        }
    }

    // 1. DANH SÁCH
    public function index() {
        $cusModel = new Customer();
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : null;
        
        // Hàm getEmployees này lấy tất cả ai role != 'customer'
        $users = $cusModel->getEmployees($keyword);

        $data = ['title' => 'Quản lý Nhân viên', 'users' => $users];
        $this->view('Admin/users', $data, 'admin_layout');
    }

    // 2. FORM THÊM
    public function add() {
        $data = ['title' => 'Thêm nhân viên mới'];
        $this->view('Admin/user-add', $data, 'admin_layout');
    }

    // 3. XỬ LÝ LƯU (STORE)
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fullname = trim($_POST['fullname']);
            $email    = trim($_POST['email']);
            $phone    = trim($_POST['phone']);
            $password = $_POST['password'];
            $role     = $_POST['role'];
            $status   = $_POST['status'];

            $cusModel = new Customer();
            $errors = [];

            // --- VALIDATION ---
            if (empty($fullname) || empty($email) || empty($password)) {
                $errors[] = "Vui lòng nhập đầy đủ thông tin bắt buộc.";
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email không đúng định dạng.";
            }
            if (!empty($phone) && !preg_match('/^0[0-9]{9,10}$/', $phone)) {
                $errors[] = "Số điện thoại không hợp lệ.";
            }
            // Check trùng
            if ($cusModel->checkDuplicate($phone, $email)) {
                $errors[] = "Email hoặc Số điện thoại đã tồn tại trên hệ thống.";
            }

            // NẾU CÓ LỖI
            if (!empty($errors)) {
                $data = [
                    'title'  => 'Thêm nhân viên mới',
                    'errors' => $errors,
                    'old'    => $_POST
                ];
                $this->view('Admin/user-add', $data, 'admin_layout');
                return;
            }

            // NẾU KHÔNG LỖI -> LƯU
            $cusModel->createEmployee([
                'fullname' => $fullname,
                'email'    => $email,
                'phone'    => $phone,
                'password' => password_hash($password, PASSWORD_DEFAULT), // Mã hóa
                'role'     => $role,
                'status'   => $status
            ]);

            echo "<script>alert('Thêm nhân viên thành công!'); window.location.href='/admin/user';</script>";
        }
    }

    // 4. FORM SỬA
    public function edit($id) {
        $cusModel = new Customer();
        $user = $cusModel->find($id);
        
        if (!$user) { header("Location: /admin/user"); exit(); }

        $data = ['title' => 'Sửa nhân viên', 'user' => $user];
        $this->view('Admin/user-edit', $data, 'admin_layout');
    }

    // 5. XỬ LÝ CẬP NHẬT
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fullname = trim($_POST['fullname']);
            $email    = trim($_POST['email']);
            $phone    = trim($_POST['phone']);
            $role     = $_POST['role'];
            $status   = $_POST['status'];
            $password = $_POST['password'];

            $cusModel = new Customer();
            $errors = [];

            // --- VALIDATION ---
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email không đúng định dạng.";
            }
            // Check trùng (trừ chính mình ra)
            if ($cusModel->checkDuplicate($phone, $email, $id)) {
                $errors[] = "Email hoặc Số điện thoại đã được sử dụng bởi người khác.";
            }

            if (!empty($errors)) {
                $user = $cusModel->find($id);
                $data = [
                    'title'  => 'Sửa nhân viên',
                    'user'   => $user,
                    'errors' => $errors,
                    'old'    => $_POST
                ];
                $this->view('Admin/user-edit', $data, 'admin_layout');
                return;
            }

            // Xử lý password (nếu có nhập mới thì hash, không thì null)
            $hashedPass = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;

            $cusModel->updateEmployee([
                'id'       => $id,
                'fullname' => $fullname,
                'email'    => $email,
                'phone'    => $phone,
                'password' => $hashedPass,
                'role'     => $role,
                'status'   => $status
            ]);

            echo "<script>alert('Cập nhật thành công!'); window.location.href='/admin/user';</script>";
        }
    }

    // 6. XÓA
    public function delete($id) {
        if ($id == $_SESSION['customer_user']['id']) {
            echo "<script>alert('Không thể xóa tài khoản đang đăng nhập!'); window.location.href='/admin/user';</script>";
            exit();
        }
        $cusModel = new Customer();
        $cusModel->delete($id);
        header("Location: /admin/user");
    }
}