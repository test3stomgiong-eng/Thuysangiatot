<?php

namespace App\Controllers\Client;

use App\Core\Controller;
use App\Models\Product;

class CartController extends Controller
{
    // 1. HÀM ADD (ĐÃ SỬA LOGIC LẤY TỔNG)
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product_id = $_POST['product_id'];
            $quantity   = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

            // Xử lý điều hướng (Mua ngay hay Thêm giỏ)
            $action = isset($_POST['action']) ? $_POST['action'] : 'add_cart';

            $productModel = new \App\Models\Product();
            $product = $productModel->find($product_id);

            if ($product) {
                if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

                // --- BƯỚC 1: CẬP NHẬT SẢN PHẨM CHÍNH VÀO GIỎ ---
                if (isset($_SESSION['cart'][$product_id])) {
                    $_SESSION['cart'][$product_id]['qty'] += $quantity;
                } else {
                    $_SESSION['cart'][$product_id] = [
                        'id'      => $product->id,
                        'name'    => $product->name,
                        'image'   => $product->main_image,
                        'price'   => ($product->sale_price > 0) ? $product->sale_price : $product->price,
                        'qty'     => $quantity,
                        'is_gift' => false
                    ];
                }

                // --- BƯỚC 2: LẤY TỔNG SỐ LƯỢNG HIỆN TẠI TRONG GIỎ (QUAN TRỌNG) ---
                // (Lấy số lượng sau khi đã cộng dồn ở trên)
                $currentTotalQty = $_SESSION['cart'][$product_id]['qty'];

                // --- BƯỚC 3: TÍNH TOÁN QUÀ TẶNG DỰA TRÊN TỔNG ---
                $this->checkPromotion($product, $currentTotalQty);

                // Điều hướng
                if ($action == 'buy_now') {
                    header("Location: /cart");
                } else {
                    // Nếu thêm giỏ thì quay lại trang cũ hoặc về giỏ hàng tùy bạn
                    header("Location: /cart");
                }
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
    // ---------------------------------------------------------
    // XÓA SẢN PHẨM (VÀ XÓA LUÔN QUÀ TẶNG KÈM THEO)
    // ---------------------------------------------------------
    public function remove($id)
    {
        // 1. Xóa sản phẩm chính
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }

        // 2. LOGIC MỚI: TÌM VÀ DIỆT QUÀ TẶNG ĂN THEO
        // (Các món quà luôn có key bắt đầu bằng: "ID_CUA_SP_CHINH" + "-gift")
        // Ví dụ: Sản phẩm chính ID=6, thì quà sẽ là "6-gift" hoặc "6-gift-12"

        $giftPrefix = $id . '-gift';

        if (!empty($_SESSION['cart'])) {
            // Duyệt qua toàn bộ giỏ hàng
            foreach (array_keys($_SESSION['cart']) as $key) {

                // Kiểm tra xem Key này có bắt đầu bằng "6-gift" không?
                // Hàm strpos trả về 0 nghĩa là tìm thấy ngay đầu chuỗi
                if (strpos($key, $giftPrefix) === 0) {
                    unset($_SESSION['cart'][$key]); // Xóa quà
                }
            }
        }

        // Quay lại giỏ hàng
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

            // 1. Cập nhật số lượng chính
            if ($type == 'increase') {
                $_SESSION['cart'][$id]['qty']++;
            } elseif ($type == 'decrease') {
                if ($currentQty > 1) {
                    $_SESSION['cart'][$id]['qty']--;
                } else {
                    // Nếu giảm về 0 thì xóa (Tùy logic)
                    // unset($_SESSION['cart'][$id]);
                }
            }

            // 2. QUAN TRỌNG: TÍNH LẠI KHUYẾN MÃI NGAY SAU KHI CẬP NHẬT
            // Chỉ tính lại nếu đây là sản phẩm mua (không phải là quà)
            if (!isset($_SESSION['cart'][$id]['is_gift']) || $_SESSION['cart'][$id]['is_gift'] == false) {

                $productModel = new \App\Models\Product();
                // Lấy ID sản phẩm thực (bỏ phần key session nếu có)
                $realId = $_SESSION['cart'][$id]['id'];
                $product = $productModel->find($realId);

                // Lấy số lượng mới
                $newQty = $_SESSION['cart'][$id]['qty'];

                // Gọi hàm tính lại quà
                $this->checkPromotion($product, $newQty);
            }
        }

        header("Location: /cart");
        exit();
    }

    // 2. HÀM TÍNH KHUYẾN MÃI (Logic tính lại toàn bộ quà)
    private function checkPromotion($product, $totalQty)
    {
        // Nếu không có khuyến mãi -> Thoát
        if ($product->promo_type == 'none') return;

        // Tính số lượng quà được nhận dựa trên TỔNG số lượng mua
        // Công thức: (Tổng mua / Số lượng điều kiện) * Số lượng tặng
        // Ví dụ: Mua 5 (quy định mua 2 tặng 1) -> floor(5/2)*1 = 2 quà.

        $promo_buy = (int)$product->promo_buy;
        $promo_get = (int)$product->promo_get;

        if ($promo_buy <= 0) return; // Tránh chia cho 0

        $giftQty = floor($totalQty / $promo_buy) * $promo_get;

        // Nếu không đủ điều kiện nhận quà (giftQty = 0) -> Thì phải XÓA quà cũ đi (nếu có)
        // Để tránh trường hợp khách giảm số lượng mua xuống mà quà vẫn còn.

        if ($product->promo_type == 'same') {
            // Tặng chính nó (ID + '-gift')
            $this->updateGiftInSession($product->id . '-gift', $product, $giftQty);
        } elseif ($product->promo_type == 'gift' && $product->promo_gift_id > 0) {
            // Tặng sản phẩm khác
            $prodModel = new \App\Models\Product();
            $giftProduct = $prodModel->find($product->promo_gift_id);
            if ($giftProduct) {
                $this->updateGiftInSession($product->id . '-gift-' . $giftProduct->id, $giftProduct, $giftQty);
            }
        }
    }

    // 3. HÀM CẬP NHẬT SESSION QUÀ TẶNG
    private function updateGiftInSession($giftKey, $productInfo, $qty)
    {
        if ($qty > 0) {
            // Cập nhật hoặc Thêm mới quà
            $_SESSION['cart'][$giftKey] = [
                'id'      => $productInfo->id,
                'name'    => '[QUÀ TẶNG] ' . $productInfo->name,
                'image'   => $productInfo->main_image,
                'price'   => 0,
                'qty'     => $qty,
                'is_gift' => true
            ];
        } else {
            // Nếu số lượng quà = 0 (do khách giảm số lượng mua) -> Xóa quà khỏi giỏ
            if (isset($_SESSION['cart'][$giftKey])) {
                unset($_SESSION['cart'][$giftKey]);
            }
        }
    }
}
