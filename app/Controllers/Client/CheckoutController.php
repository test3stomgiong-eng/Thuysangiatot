<?php
namespace App\Controllers\Client;
use App\Core\Controller;
use App\Core\Database;

class CheckoutController extends Controller {

    // 1. Hiển thị trang điền thông tin
    public function index() {
        // Nếu giỏ hàng trống thì đá về trang chủ, không cho thanh toán
        if (empty($_SESSION['cart'])) {
            header("Location: /");
            exit();
        }

        // Tính tổng tiền
        $cart = $_SESSION['cart'];
        $total_money = 0;
        foreach ($cart as $item) {
            $total_money += $item['price'] * $item['qty'];
        }

        $data = [
            'title'       => 'Thanh toán đơn hàng',
            'cart'        => $cart,
            'total_money' => $total_money,
            // Bạn có thể tạo thêm file checkout.css nếu muốn style riêng
            'css_files'   => ['style.css', 'checkout.css'] 
        ];

        $this->view('Client/checkout', $data, 'client_layout');
    }

    // 2. Xử lý khi bấm nút "ĐẶT HÀNG" (Lưu vào DB)
    public function process() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_SESSION['cart'])) {
            
            // Lấy dữ liệu từ Form
            $fullname = $_POST['fullname'];
            $phone    = $_POST['phone'];
            $address  = $_POST['address'];
            $note     = isset($_POST['note']) ? $_POST['note'] : '';
            
            // Tính lại tổng tiền
            $cart = $_SESSION['cart'];
            $total_money = 0;
            foreach ($cart as $item) {
                $total_money += $item['price'] * $item['qty'];
            }

            // Tạo mã đơn hàng (Ví dụ: DH-169...)
            $order_code = 'DH' . time();

            // Kết nối DB
            $db = new Database();
            $conn = $db->getConnection();

            try {
                // Bắt đầu giao dịch (Transaction)
                $conn->beginTransaction();

                // A. Lưu bảng ORDERS
                $sql1 = "INSERT INTO orders (code, customer_name, customer_phone, shipping_address, total_money, note, status, created_at) 
                         VALUES (:code, :name, :phone, :address, :total, :note, 'pending', NOW())";
                $stmt1 = $conn->prepare($sql1);
                $stmt1->execute([
                    ':code'    => $order_code,
                    ':name'    => $fullname,
                    ':phone'   => $phone,
                    ':address' => $address,
                    ':total'   => $total_money,
                    ':note'    => $note
                ]);
                
                $order_id = $conn->lastInsertId(); // Lấy ID đơn vừa tạo

                // B. Lưu bảng ORDER_DETAILS (Chi tiết từng món)
                $sql2 = "INSERT INTO order_details (order_id, product_id, product_name, price, quantity, total_price) 
                         VALUES (:order_id, :p_id, :p_name, :price, :qty, :total)";
                $stmt2 = $conn->prepare($sql2);

                foreach ($cart as $item) {
                    $stmt2->execute([
                        ':order_id' => $order_id,
                        ':p_id'     => $item['id'],
                        ':p_name'   => $item['name'],
                        ':price'    => $item['price'],
                        ':qty'      => $item['qty'],
                        ':total'    => $item['price'] * $item['qty']
                    ]);
                }

                // C. Chốt đơn và Xóa giỏ hàng
                $conn->commit();
                unset($_SESSION['cart']);

                // Thông báo và chuyển về trang chủ
                echo "<script>
                        alert('🎉 Đặt hàng thành công! Mã đơn: $order_code. Chúng tôi sẽ liên hệ sớm.');
                        window.location.href = '/';
                      </script>";

            } catch (\Exception $e) {
                $conn->rollBack(); // Hủy nếu lỗi
                echo "Lỗi hệ thống: " . $e->getMessage();
            }
        } else {
            // Nếu truy cập trực tiếp link process mà không post
            header("Location: /");
        }
    }
}