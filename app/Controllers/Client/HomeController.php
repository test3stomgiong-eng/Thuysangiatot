<?php

namespace App\Controllers\Client;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Category; // 👈 Thêm
use App\Models\News;     // 👈 Thêm

class HomeController extends Controller
{

    public function index()
    {
        // 1. Khởi tạo các Model
        $productModel = new Product();
        $cateModel    = new Category();
        $newsModel    = new News();

        // 2. Lấy dữ liệu
        // Sản phẩm
        $new_products  = $productModel->getNewProducts(5);
        $sale_products = $productModel->getSaleProducts(4);



        $id_khang_sinh = 11;
        $antibiotic_products = $productModel->getProductsByCategory($id_khang_sinh, 5);

        // 3. 👇 MỚI: Men vi sinh (Giả sử ID = 3)
        $probiotic_products = $productModel->getProductsByCategory(3, 5);

        // 4. 👇 MỚI: Sản phẩm xem nhiều (Hot)
        $top_viewed = $productModel->getTopViewed(5);
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
            'antibiotic_products' => $antibiotic_products,

            'probiotic_products'  => $probiotic_products,
            'top_viewed'          => $top_viewed,

            'categories'    => $categories,
            'latest_news'   => $latest_news,
            'css_files'     => ['style.css', 'home.css']
        ];

        $this->view('Client/home', $data, 'client_layout');
    }
}
