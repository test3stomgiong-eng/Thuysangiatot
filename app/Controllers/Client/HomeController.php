<?php
namespace App\Controllers\Client;
use App\Core\Controller;
use App\Models\Product;
use App\Models\Category; // 👈 Thêm
use App\Models\News;     // 👈 Thêm

class HomeController extends Controller {
    
    public function index() {
        // 1. Khởi tạo các Model
        $productModel = new Product();
        $cateModel    = new Category();
        $newsModel    = new News();

        // 2. Lấy dữ liệu
        // Sản phẩm
        $new_products  = $productModel->getNewProducts(8);
        $sale_products = $productModel->getSaleProducts(4);
        
        // Danh mục (Lấy danh mục thuốc, bỏ tin tức id=6)
        // Nếu bạn chưa có hàm getProductCategories ở Model Category thì dùng getAll lọc tạm
        $categories = $cateModel->getProductCategories(); 

        // Tin tức mới nhất (Lấy 4 bài)
        // Bạn cần thêm hàm getLatestNews($limit) vào Model News nhé (code ở dưới)
        $latest_news = $newsModel->getLatestNews(4);

        // 3. Gửi sang View
        $data = [
            'title'         => 'Thuỷ Sản Giá Tốt - Chất lượng cao',
            'new_products'  => $new_products,
            'sale_products' => $sale_products,
            'categories'    => $categories,  // 👈 Biến mới
            'latest_news'   => $latest_news, // 👈 Biến mới
            'css_files'     => ['style.css', 'home.css']
        ];

        $this->view('Client/home', $data, 'client_layout');
    }
}