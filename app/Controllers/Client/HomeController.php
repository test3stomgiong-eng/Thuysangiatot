<?php
namespace App\Controllers\Client;
use App\Core\Controller;
use App\Models\Product; // Gọi Model Product

class HomeController extends Controller {
    public function index() {
        // 1. Khởi tạo Model
        $productModel = new Product();

        // 2. Lấy dữ liệu từ DB
        // (Bạn đảm bảo file app/Models/Product.php đã có hàm getNewProducts và getSaleProducts nhé)
        $new_products = $productModel->getNewProducts(8);
        $sale_products = $productModel->getSaleProducts();

        // 3. Đóng gói gửi sang View
        $data = [
            'title'         => 'Trang chủ - Thuốc Thuỷ Sản',
            'new_products'  => $new_products,
            'sale_products' => $sale_products,
            
            // Load file CSS riêng cho trang chủ
            'css_files'     => ['style.css', 'products.css']
        ];

        // 4. Gọi View
        $this->view('Client/home', $data, 'client_layout');
    }
}