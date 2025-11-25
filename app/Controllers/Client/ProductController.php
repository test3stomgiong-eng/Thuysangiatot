<?php

namespace App\Controllers\Client;

use App\Core\Controller;
use App\Models\Product;

class ProductController extends Controller
{

    public function detail($id = null)
    {
        // 1. Kiểm tra ID
        if (!$id) {
            header("Location: /"); // Không có ID thì về trang chủ
            exit();
        }

        // 2. Gọi Model lấy dữ liệu
        $productModel = new Product();
        $product = $productModel->find($id);

        // 3. Nếu không tìm thấy sản phẩm (ID sai)
        if (!$product) {
            echo "Sản phẩm không tồn tại!";
            // Hoặc load view 404
            return;
        }

        $gallery = $productModel->getGallery($id);

        $related_products = $productModel->getRelatedProducts($product->category_id, $product->id);

        // 4. Gửi dữ liệu sang View
        $data = [
            'title'     => $product->name, // Title tab trình duyệt là tên SP
            'product'   => $product,       // Biến chứa toàn bộ thông tin SP
            'gallery'   => $gallery,
            'related_products' => $related_products,
            // Load file CSS riêng cho trang chi tiết (Bạn nhớ tạo file này)
            'css_files'     => ['style.css', 'product-detail.css']
        ];

        $this->view('Client/product-detail', $data, 'client_layout');
    }
}
