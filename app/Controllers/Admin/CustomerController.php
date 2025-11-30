<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Customer;
use App\Models\Order;

class CustomerController extends Controller
{

    public function __construct()
    {
        // Check quyền Admin
        if (!isset($_SESSION['customer_user']) || $_SESSION['customer_user']['role'] !== 'admin') {
            header("Location: /");
            exit();
        }
    }

    // 1. Danh sách khách hàng
    public function index()
    {
        $cusModel = new Customer();
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : null;

        $customers = $cusModel->getAllAdmin($keyword);

        $data = [
            'title'     => 'Quản lý Khách hàng',
            'customers' => $customers
        ];
        $this->view('Admin/customers', $data, 'admin_layout');
    }

    // 2. Xử lý Khóa / Mở khóa
    public function toggleStatus($id, $currentStatus)
    {
        $cusModel = new Customer();

        // Đảo ngược trạng thái: Đang 1 thành 0, Đang 0 thành 1
        $newStatus = ($currentStatus == 1) ? 0 : 1;

        $cusModel->updateStatus($id, $newStatus);

        // Quay lại trang danh sách
        header("Location: /admin/customer");
        exit();
    }

    // 3. Xóa khách hàng
    public function delete($id)
    {
        $cusModel = new Customer();
        $cusModel->delete($id);
        header("Location: /admin/customer");
        exit();
    }

    // 4. Xem lịch sử mua hàng
    public function history($id)
    {
        $cusModel = new Customer();
        $orderModel = new Order();

        // Lấy thông tin khách
        $customer = $cusModel->find($id);

        if (!$customer) {
            header("Location: /admin/customer");
            exit();
        }

        // Lấy danh sách đơn hàng của khách này
        $orders = $orderModel->getOrdersByCustomer($id);

        $data = [
            'title'    => 'Hồ sơ khách hàng: ' . $customer->fullname,
            'customer' => $customer,
            'orders'   => $orders
        ];

        $this->view('Admin/customer-history', $data, 'admin_layout');
    }

    // 4. HIỆN FORM SỬA (GET)
    public function edit($id)
    {
        $cusModel = new Customer();
        $customer = $cusModel->find($id);

        if (!$customer) {
            header("Location: /admin/customer");
            exit();
        }

        $data = [
            'title'    => 'Sửa thông tin khách hàng',
            'customer' => $customer
        ];
        $this->view('Admin/customer-edit', $data, 'admin_layout');
    }

    // 5. XỬ LÝ CẬP NHẬT (POST)
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fullname = trim($_POST['fullname']);
            $email    = trim($_POST['email']);
            $phone    = trim($_POST['phone']);
            $status   = $_POST['status'];
            $password = $_POST['password']; // Nếu rỗng thì không đổi

            $cusModel = new Customer();
            $errors = [];

            // --- VALIDATION (KIỂM TRA DỮ LIỆU) ---

            // 1. Kiểm tra Email đúng định dạng
            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email không đúng định dạng (Ví dụ: abc@gmail.com)";
            }

            // 2. Kiểm tra SĐT (Phải là số, bắt đầu bằng 0, dài 10-11 số)
            if (!preg_match('/^0[0-9]{9,10}$/', $phone)) {
                $errors[] = "Số điện thoại không hợp lệ (Phải bắt đầu bằng số 0, 10 số)";
            }

            // 3. Kiểm tra trùng lặp (Quan trọng)
            if ($cusModel->checkDuplicate($phone, $email, $id)) {
                $errors[] = "Email hoặc Số điện thoại này đã được tài khoản khác sử dụng!";
            }

            // --- NẾU CÓ LỖI -> TRẢ VỀ VIEW ---
            if (!empty($errors)) {
                // Lấy lại thông tin cũ để hiển thị form
                $customer = $cusModel->find($id);
                $data = [
                    'title'    => 'Sửa thông tin khách hàng',
                    'customer' => $customer,
                    'errors'   => $errors, // Gửi lỗi sang View
                    'old'      => $_POST   // Gửi dữ liệu vừa nhập
                ];
                $this->view('Admin/customer-edit', $data, 'admin_layout');
                return; // Dừng lại
            }

            // --- NẾU KHÔNG LỖI -> LƯU DATABASE ---

            // Xử lý mật khẩu (Nếu nhập mới thì hash, không thì thôi)
            $passHash = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;

            // Reuse hàm updateEmployee hoặc viết hàm update riêng cho customer đều được
            // Ở đây ta gọi updateEmployee của Model Customer vì cấu trúc giống nhau
            $cusModel->updateEmployee([
                'id'       => $id,
                'fullname' => $fullname,
                'email'    => $email,
                'phone'    => $phone,
                'password' => $passHash,
                'role'     => 'customer', // Đảm bảo vẫn là khách
                'status'   => $status
            ]);

            echo "<script>alert('Cập nhật thành công!'); window.location.href='/admin/customer';</script>";
        }
    }
}
