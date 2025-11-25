<?php

namespace App\Controllers\Client;

use App\Core\Controller;
use App\Models\Product;

class CartController extends Controller
{

    // Hàm thêm vào giỏ (Xử lý khi bấm nút "Thêm vào giỏ")
    public function add()
    {
        // Chỉ nhận dữ liệu POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product_id = $_POST['product_id'];
            $quantity   = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

            // 1. Lấy thông tin sản phẩm từ DB (để lấy tên, giá, ảnh...)
            $productModel = new Product();
            $product = $productModel->find($product_id);

            if ($product) {
                // 2. Tạo cấu trúc item trong giỏ
                $item = [
                    'id'    => $product->id,
                    'name'  => $product->name,
                    'image' => $product->main_image,
                    'price' => ($product->sale_price > 0) ? $product->sale_price : $product->price,
                    'qty'   => $quantity
                ];

                // 3. Lưu vào Session
                // Nếu giỏ hàng chưa tồn tại thì tạo mới
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }

                // Nếu sản phẩm đã có trong giỏ -> Cộng dồn số lượng
                if (isset($_SESSION['cart'][$product_id])) {
                    $_SESSION['cart'][$product_id]['qty'] += $quantity;
                } else {
                    // Nếu chưa có -> Thêm mới
                    $_SESSION['cart'][$product_id] = $item;
                }

                // 4. Chuyển hướng đến trang xem giỏ hàng
                header("Location: /cart");
                exit();
            }
        }
    }

    // Hàm hiển thị trang giỏ hàng
    public function index()
    {
        // Lấy giỏ hàng từ session
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

        // Tính tổng tiền
        $total_money = 0;
        foreach ($cart as $item) {
            $total_money += $item['price'] * $item['qty'];
        }

        $data = [
            'title'       => 'Giỏ hàng của bạn',
            'cart'        => $cart,
            'total_money' => $total_money,
            'css_files'   => ['cart.css', 'style.css'] // Bạn chuẩn bị file css này nhé
        ];

        $this->view('Client/cart', $data, 'client_layout');
    }

    /**
     * Hàm xóa sản phẩm khỏi giỏ
     * URL: /cart/remove/1
     */
    public function remove($id)
    {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        // Xóa xong quay lại trang giỏ hàng
        header("Location: /cart");
        exit();
    }

    /**
     * Hàm cập nhật số lượng (Dùng cho nút + và -)
     * URL: /cart/update/1/increase (Tăng) hoặc /cart/update/1/decrease (Giảm)
     */
    public function update($id, $type)
    {
        if (isset($_SESSION['cart'][$id])) {
            $currentQty = $_SESSION['cart'][$id]['qty'];

            if ($type == 'increase') {
                $_SESSION['cart'][$id]['qty']++;
            } elseif ($type == 'decrease') {
                if ($currentQty > 1) {
                    $_SESSION['cart'][$id]['qty']--;
                } else {
                    // Nếu giảm về 0 thì coi như xóa luôn (tuỳ logic của bạn)
                    // unset($_SESSION['cart'][$id]);
                }
            }
        }
        header("Location: /cart");
        exit();
    }
}
