<?php

namespace App\Controllers\Client;

use App\Core\Controller;
use App\Core\Database;

class CheckoutController extends Controller
{

    // 1. Hiển thị trang điền thông tin
    public function index()
    {
        // 1. Kiểm tra giỏ hàng (Code cũ)
        if (empty($_SESSION['cart'])) {
            header("Location: /");
            exit();
        }

        // 2. Tính tổng tiền (Code cũ)
        $cart = $_SESSION['cart'];
        $total_money = 0;
        foreach ($cart as $item) {
            $total_money += $item['price'] * $item['qty'];
        }

        // 👇 3. THÊM ĐOẠN NÀY: Lấy thông tin khách hàng nếu đã đăng nhập
        $currentUser = null; // Mặc định là null (Khách vãng lai)

        if (isset($_SESSION['customer_user'])) {
            $cusModel = new \App\Models\Customer();
            // Lấy dữ liệu mới nhất từ DB (để đảm bảo địa chỉ, sđt là mới nhất)
            $currentUser = $cusModel->find($_SESSION['customer_user']['id']);
        }
        // -----------------------------------------------------------

        $data = [
            'title'       => 'Thanh toán đơn hàng',
            'cart'        => $cart,
            'total_money' => $total_money,

            // Truyền biến user sang view
            'user'        => $currentUser,

            'css_files'   => ['style.css','checkout.css']
        ];

        $this->view('Client/checkout', $data, 'client_layout');
    }

    // 2. Xử lý khi bấm nút "ĐẶT HÀNG" (Lưu vào DB)
    public function process()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_SESSION['cart'])) {

            // 1. LẤY DỮ LIỆU TỪ FORM
            $fullname = trim($_POST['fullname']);
            $phone    = trim($_POST['phone']);
            $address  = trim($_POST['address']);
            $note     = isset($_POST['note']) ? trim($_POST['note']) : '';

            // --- LOGIC MỚI: XỬ LÝ CẬP NHẬT THÔNG TIN KHÁCH HÀNG ---

            $customer_id = null;
            $customerModel = new \App\Models\Customer(); // Gọi Model

            // TRƯỜNG HỢP A: Khách ĐÃ Đăng Nhập
            if (isset($_SESSION['customer_user'])) {
                $customer_id = $_SESSION['customer_user']['id'];

                // Cập nhật thông tin mới vào bảng customers
                // (Ví dụ lúc đăng ký chưa có địa chỉ, giờ mua hàng nhập địa chỉ -> Lưu luôn)
                $customerModel->updateContactInfo($customer_id, $fullname, $phone, $address);

                // Cập nhật lại Session để hiển thị đúng ngay lập tức (nếu cần)
                $_SESSION['customer_user']['fullname'] = $fullname;
                $_SESSION['customer_user']['phone'] = $phone;
            }
            // TRƯỜNG HỢP B: Khách Vãng Lai (Chưa đăng nhập)
            else {
                // Kiểm tra xem SĐT này đã có trong hệ thống chưa?
                $existCus = $customerModel->findByPhone($phone);

                if ($existCus) {
                    // Nếu SĐT đã tồn tại -> Gán đơn hàng này cho khách đó luôn
                    $customer_id = $existCus->id;

                    // (Tùy chọn) Nếu khách cũ chưa có địa chỉ trong DB thì cập nhật luôn
                    if (empty($existCus->address)) {
                        $customerModel->updateContactInfo($customer_id, $fullname, $phone, $address);
                    }
                }
            }
            // -------------------------------------------------------

            // 3. TIẾP TỤC QUY TRÌNH ĐẶT HÀNG (Code cũ của bạn)
            $cart = $_SESSION['cart'];
            $total_money = 0;
            foreach ($cart as $item) {
                $total_money += $item['price'] * $item['qty'];
            }

            $order_code = 'DH' . time();

            $db = new \App\Core\Database();
            $conn = $db->getConnection();

            try {
                $conn->beginTransaction();

                // A. INSERT BẢNG ORDERS
                $sql_order = "INSERT INTO orders (code, customer_id, customer_name, customer_phone, shipping_address, total_money, note, status, created_at) 
                              VALUES (:code, :cid, :name, :phone, :addr, :total, :note, 'pending', NOW())";

                $stmt_order = $conn->prepare($sql_order);
                $stmt_order->execute([
                    ':code'  => $order_code,
                    ':cid'   => $customer_id, // ID khách hàng (đã xử lý ở trên)
                    ':name'  => $fullname,
                    ':phone' => $phone,
                    ':addr'  => $address,
                    ':total' => $total_money,
                    ':note'  => $note
                ]);

                $order_id = $conn->lastInsertId();

                // ... (Phần lưu chi tiết và trừ kho giữ nguyên như cũ) ...
                // B. CHUẨN BỊ SQL: CHI TIẾT & TRỪ KHO
                $sql_detail = "INSERT INTO order_details (order_id, product_id, product_name, price, quantity, total_price) 
                               VALUES (:oid, :pid, :pname, :price, :qty, :total)";
                $stmt_detail = $conn->prepare($sql_detail);

                $sql_stock = "UPDATE products SET stock = stock - :qty WHERE id = :pid";
                $stmt_stock = $conn->prepare($sql_stock);

                foreach ($cart as $item) {
                    $stmt_detail->execute([
                        ':oid'   => $order_id,
                        ':pid'   => $item['id'],
                        ':pname' => $item['name'],
                        ':price' => $item['price'],
                        ':qty'   => $item['qty'],
                        ':total' => $item['price'] * $item['qty']
                    ]);
                    $stmt_stock->execute([
                        ':qty' => $item['qty'],
                        ':pid' => $item['id']
                    ]);
                }

                $conn->commit();
                unset($_SESSION['cart']);

                echo "<script>
                        alert('🎉 Đặt hàng thành công! Mã đơn: $order_code');
                        window.location.href = '/';
                      </script>";
            } catch (\Exception $e) {
                $conn->rollBack();
                echo "Lỗi hệ thống: " . $e->getMessage();
            }
        } else {
            header("Location: /");
        }
    }
}
