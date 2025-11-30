<?php

namespace App\Controllers\Client;

use App\Core\Controller;
use App\Core\Database;

class CheckoutController extends Controller
{

    // 1. Hiển thị trang điền thông tin
    public function index()
    {
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
        // Chỉ xử lý khi có POST và Giỏ hàng không rỗng
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_SESSION['cart'])) {

            // 1. LẤY DỮ LIỆU TỪ FORM
            $fullname = trim($_POST['fullname']);
            $phone    = trim($_POST['phone']);
            $address  = trim($_POST['address']);
            $note     = isset($_POST['note']) ? trim($_POST['note']) : '';

            // 2. XÁC ĐỊNH KHÁCH HÀNG (Để lưu lịch sử mua hàng)
            $customer_id = null; // Mặc định là khách vãng lai
            if (isset($_SESSION['customer_user'])) {
                $customer_id = $_SESSION['customer_user']['id'];
            }

            // 3. TÍNH TỔNG TIỀN
            $cart = $_SESSION['cart'];
            $total_money = 0;
            foreach ($cart as $item) {
                $total_money += $item['price'] * $item['qty'];
            }

            // 4. TẠO MÃ ĐƠN HÀNG (VD: DH1702345678)
            $order_code = 'DH' . time();

            // 5. KẾT NỐI DB & BẮT ĐẦU GIAO DỊCH
            $db = new \App\Core\Database(); // Nhớ thêm dấu \ hoặc use App\Core\Database ở đầu file
            $conn = $db->getConnection();

            try {
                $conn->beginTransaction(); // --- BẮT ĐẦU ---

                // A. INSERT BẢNG ORDERS
                $sql_order = "INSERT INTO orders (code, customer_id, customer_name, customer_phone, shipping_address, total_money, note, status, created_at) 
                              VALUES (:code, :cid, :name, :phone, :addr, :total, :note, 'pending', NOW())";
                
                $stmt_order = $conn->prepare($sql_order);
                $stmt_order->execute([
                    ':code'  => $order_code,
                    ':cid'   => $customer_id, // Lưu ID khách (quan trọng để xem lịch sử)
                    ':name'  => $fullname,
                    ':phone' => $phone,
                    ':addr'  => $address,
                    ':total' => $total_money,
                    ':note'  => $note
                ]);

                // Lấy ID của đơn hàng vừa tạo
                $order_id = $conn->lastInsertId();

                // B. CHUẨN BỊ SQL: CHI TIẾT & TRỪ KHO
                $sql_detail = "INSERT INTO order_details (order_id, product_id, product_name, price, quantity, total_price) 
                               VALUES (:oid, :pid, :pname, :price, :qty, :total)";
                $stmt_detail = $conn->prepare($sql_detail);

                $sql_stock = "UPDATE products SET stock = stock - :qty WHERE id = :pid";
                $stmt_stock = $conn->prepare($sql_stock);

                // C. CHẠY VÒNG LẶP TỪNG SẢN PHẨM
                foreach ($cart as $item) {
                    // C.1 Lưu chi tiết đơn hàng
                    $stmt_detail->execute([
                        ':oid'   => $order_id,
                        ':pid'   => $item['id'],
                        ':pname' => $item['name'],
                        ':price' => $item['price'],
                        ':qty'   => $item['qty'],
                        ':total' => $item['price'] * $item['qty']
                    ]);

                    // C.2 Trừ tồn kho (Nằm trong vòng lặp là ĐÚNG)
                    $stmt_stock->execute([
                        ':qty' => $item['qty'],
                        ':pid' => $item['id']
                    ]);
                }

                // D. CHỐT GIAO DỊCH
                $conn->commit(); // --- THÀNH CÔNG ---

                // 6. XÓA GIỎ HÀNG & CHUYỂN HƯỚNG
                unset($_SESSION['cart']);

                echo "<script>
                        alert('🎉 Đặt hàng thành công! Mã đơn: $order_code. Chúng tôi sẽ liên hệ sớm.');
                        window.location.href = '/';
                      </script>";

            } catch (\Exception $e) {
                // Nếu có lỗi bất kỳ -> Hủy toàn bộ thao tác
                $conn->rollBack(); 
                echo "Lỗi hệ thống: " . $e->getMessage();
            }

        } else {
            // Nếu truy cập trực tiếp mà không mua hàng -> Về trang chủ
            header("Location: /");
        }
    }
}
