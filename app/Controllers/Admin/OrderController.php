<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Order;
use App\Models\Setting;

class OrderController extends Controller
{

    public function __construct()
    {
        // Check quyền Admin
        if (!isset($_SESSION['customer_user']) || $_SESSION['customer_user']['role'] !== 'admin') {
            header("Location: /auth/login");
            exit();
        }
    }

    // 1. DANH SÁCH ĐƠN HÀNG
    public function index()
    {
        $orderModel = new Order();

        // Lấy dữ liệu từ bộ lọc
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : null;
        $status  = isset($_GET['status']) ? $_GET['status'] : null;

        $orders = $orderModel->getAllOrders($keyword, $status);

        $data = [
            'title'  => 'Quản lý đơn hàng',
            'orders' => $orders
        ];
        $this->view('Admin/orders', $data, 'admin_layout');
    }

    // 2. CHI TIẾT ĐƠN HÀNG
    public function detail($id)
    {
        $orderModel = new \App\Models\Order();

        // 1. Lấy thông tin đơn hàng
        $order = $orderModel->findOrder($id);
        if (!$order) {
            header("Location: /admin/order");
            exit();
        }

        $details = $orderModel->getOrderDetails($id);

        // 2. 👇 THÊM ĐOẠN NÀY: Lấy danh sách mẫu in từ DB
        $db = new \App\Core\Database();
        // Lấy mẫu mặc định lên đầu
        $stmt = $db->query("SELECT * FROM print_templates ORDER BY is_default DESC, id DESC");
        $stmt->execute();
        $templates = $stmt->fetchAll();

        // 3. Truyền biến $templates sang View
        $data = [
            'title'     => 'Chi tiết đơn hàng #' . $order->code,
            'order'     => $order,
            'details'   => $details,
            'templates' => $templates // <--- Quan trọng
        ];

        $this->view('Admin/order-detail', $data, 'admin_layout');
    }

    // 3. CẬP NHẬT TRẠNG THÁI
    public function updateStatus($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $status = $_POST['status'];

            $orderModel = new Order();
            $orderModel->updateStatus($id, $status);
            // 👇 THÊM DÒNG NÀY: Lưu thông báo vào session
            $_SESSION['flash_success'] = "Đã cập nhật trạng thái đơn hàng thành công!";
            // Cập nhật xong thì quay lại trang chi tiết
            header("Location: /admin/order/detail/$id");
        }
    }

    // Hàm In Hóa Đơn (Dynamic)
    // Hàm In Hóa Đơn (Đã fix lỗi thiếu tham số)
    // Thêm "= null" để nếu thiếu ID mẫu thì không bị lỗi
    public function printOrder($order_id, $template_id = null) {
        $orderModel = new \App\Models\Order();
        $db = new \App\Core\Database();

        // 1. Lấy dữ liệu
        $order = $orderModel->findOrder($order_id);
        if (!$order) { echo "Đơn không tồn tại"; die(); }
        $details = $orderModel->getOrderDetails($order_id);

        // 2. Lấy mẫu in
        if ($template_id) {
            $stmt = $db->query("SELECT content FROM print_templates WHERE id = :id");
            $stmt->execute([':id' => $template_id]);
        } else {
            $stmt = $db->query("SELECT content FROM print_templates WHERE is_default = 1 LIMIT 1");
            $stmt->execute();
        }
        $tpl = $stmt->fetch();
        
        // Nếu không có mẫu nào thì lấy đại cái mới nhất (fallback)
        if (!$tpl) {
            $stmt = $db->query("SELECT content FROM print_templates ORDER BY id DESC LIMIT 1");
            $stmt->execute();
            $tpl = $stmt->fetch();
            if (!$tpl) { echo "Chưa có mẫu in!"; die(); }
        }
        
        $content = $tpl->content;

        // ===========================================================
        // 3. TÍNH TOÁN & CHUẨN BỊ DỮ LIỆU (FIX LỖI TẠI ĐÂY)
        // ===========================================================
        
        // Khởi tạo biến trước vòng lặp để tránh lỗi Undefined variable
        $sumProduct = 0; 
        $i = 1;

        // Tạo sẵn khung HTML cho bảng tự động (để dùng cho {BANG_HANG_CHI_TIET})
        $tableHtml = '<table style="width:100%; border-collapse:collapse; font-size:13px; font-family:Arial;">
                        <thead>
                            <tr style="background-color:#f0f0f0;">
                                <th style="border:1px solid #333; padding:5px;">STT</th>
                                <th style="border:1px solid #333; padding:5px;">Tên Hàng</th>
                                <th style="border:1px solid #333; padding:5px;">SL</th>
                                <th style="border:1px solid #333; padding:5px; text-align:right;">Đơn giá</th>
                                <th style="border:1px solid #333; padding:5px; text-align:right;">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>';

        // Chạy vòng lặp 1 lần để tính tổng tiền VÀ tạo bảng tự động luôn
        foreach ($details as $item) {
            $thanhTien = $item->price * $item->quantity;
            $sumProduct += $thanhTien; // Cộng dồn tổng tiền hàng

            // Nối chuỗi cho bảng tự động
            $tableHtml .= '<tr>
                            <td style="border:1px solid #333; padding:5px; text-align:center;">'.$i++.'</td>
                            <td style="border:1px solid #333; padding:5px;">'.$item->product_name.'</td>
                            <td style="border:1px solid #333; padding:5px; text-align:center;">'.$item->quantity.'</td>
                            <td style="border:1px solid #333; padding:5px; text-align:right;">'.number_format($item->price).'</td>
                            <td style="border:1px solid #333; padding:5px; text-align:right;">'.number_format($thanhTien).'</td>
                          </tr>';
        }
        $tableHtml .= '</tbody></table>';

        // ===========================================================
        // 4. XỬ LÝ NÂNG CAO: NẾU NGƯỜI DÙNG TỰ VẼ BẢNG (REGEX)
        // ===========================================================
        $pattern = '/<tr[^>]*>.*?\{SP_[A-Z_]+\}.*?<\/tr>/is';
        
        if (preg_match($pattern, $content, $matches)) {
            $rowTemplate = $matches[0];
            $rowsHtml = '';
            $j = 1;

            foreach ($details as $item) {
                $thanhTien = $item->price * $item->quantity;
                $unit = 'Hộp'; 
                $giaGoc = $item->price;

                $tempRow = $rowTemplate;
                $rowMap = [
                    '{SP_STT}'        => $j++,
                    '{SP_MA}'         => $item->sku ?? '',
                    '{SP_TEN}'        => $item->product_name,
                    '{SP_DVT}'        => $unit,
                    '{SP_SL}'         => $item->quantity,
                    '{SP_GIA_LE}'     => number_format($giaGoc),
                    '{SP_GIA_CK}'     => number_format($item->price),
                    '{SP_THANH_TIEN}' => number_format($thanhTien)
                ];
                $tempRow = str_replace(array_keys($rowMap), array_values($rowMap), $tempRow);
                $rowsHtml .= $tempRow;
            }
            $content = str_replace($rowTemplate, $rowsHtml, $content);
        }

        // ===========================================================
        // 5. THAY THẾ CÁC BIẾN CHUNG
        // ===========================================================
        $map = [
            '{MA_DON}'           => $order->code,
            '{NGAY_TAO}'         => date('d/m/Y H:i', strtotime($order->created_at)),
            '{TEN_KHACH}'        => $order->customer_name,
            '{SDT_KHACH}'        => $order->customer_phone,
            '{DIA_CHI}'          => $order->shipping_address,
            
            '{BANG_HANG_CHI_TIET}' => $tableHtml, // Luôn có dữ liệu, không bị lỗi
            
            '{TONG_TIEN_HANG}'   => number_format($sumProduct) . ' đ',
            '{PHI_SHIP}'         => '0 đ',
            '{TONG_CONG}'        => number_format($order->total_money) . ' đ'
        ];

        echo str_replace(array_keys($map), array_values($map), $content);
        echo "<script>window.print();</script>";
    }
    
}
