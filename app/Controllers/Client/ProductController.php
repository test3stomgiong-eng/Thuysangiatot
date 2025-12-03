<?php

namespace App\Controllers\Client;

use App\Core\Controller;
use App\Models\Product;

class ProductController extends Controller
{

    // 1. TRANG DANH SÁCH SẢN PHẨM (/product)
    // app/Controllers/Client/ProductController.php

    public function index()
    {
        $productModel = new \App\Models\Product();
        $cateModel = new \App\Models\Category();

        // 1. NHẬN DỮ LIỆU TỪ URL (FILTER)
        $cat_id    = isset($_GET['cat']) ? $_GET['cat'] : null;
        $min_price = isset($_GET['min_price']) ? $_GET['min_price'] : null;
        $max_price = isset($_GET['max_price']) ? $_GET['max_price'] : null;
        $sort      = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

        // 👇 QUAN TRỌNG: Lấy từ khóa tìm kiếm
        $keyword   = isset($_GET['keyword']) ? trim($_GET['keyword']) : null;

        // 2. XỬ LÝ PHÂN TRANG (PAGINATION)
        $page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        $limit = 12; // Số sản phẩm mỗi trang
        $offset = ($page - 1) * $limit;

        // 3. GỌI MODEL (ĐÚNG THỨ TỰ THAM SỐ)

        // Bước A: Đếm tổng số lượng để tính số trang
        $totalProducts = $productModel->countAllClient($cat_id, $min_price, $max_price, $keyword);
        $totalPages = ceil($totalProducts / $limit);

        // Bước B: Lấy danh sách sản phẩm (Truyền đủ 7 tham số)
        $products = $productModel->getAllClient(
            $cat_id,
            $min_price,
            $max_price,
            $sort,
            $limit,
            $offset,
            $keyword // 👈 Đừng quên biến này ở cuối
        );

        // Lấy danh mục cho Sidebar
        $categories = $cateModel->getProductCategories();

        $data = [
            'title'         => 'Danh sách sản phẩm',
            'products'      => $products,
            'categories'    => $categories,
            'css_files'     => ['style.css', 'products.css'],

            // 👇 Truyền dữ liệu phân trang và tìm kiếm sang View
            'currentPage'   => $page,
            'totalPages'    => $totalPages,
            'totalProducts' => $totalProducts,
            'keyword'       => $keyword
        ];

        $this->view('Client/products', $data, 'client_layout');
    }
    
    // 2. TRANG CHI TIẾT SẢN PHẨM (/product/detail/ID)
    public function detail($id = null)
    {
        if (!$id) {
            header("Location: /");
            exit();
        }

        $productModel = new \App\Models\Product();
        $product = $productModel->find($id);

        if (!$product) {
            // Nếu muốn chuyên nghiệp hơn thì chuyển về trang 404
            echo "Sản phẩm không tồn tại!";
            return;
        }

        // --- 👇 BỔ SUNG: TĂNG LƯỢT XEM (CÓ CHECK SESSION) 👇 ---
        $sessionKey = 'viewed_product_' . $id; // Key: viewed_product_15

        if (!isset($_SESSION[$sessionKey])) {
            // 1. Gọi Model tăng view trong DB
            $productModel->increaseView($id);

            // 2. Lưu session để đánh dấu "đã xem" (F5 sẽ không tăng nữa)
            $_SESSION[$sessionKey] = true;

            // 3. Tăng số hiển thị ngay lập tức cho khách thấy (ảo giác realtime)
            $product->views++;
        }
        // -------------------------------------------------------

        $gallery = $productModel->getGallery($id);

        // Lấy 4 sản phẩm liên quan (Trừ sản phẩm đang xem)
        $related = $productModel->getRelatedProducts($product->category_id, $product->id, 4);

        $data = [
            'title'            => $product->name,
            'product'          => $product,
            'gallery'          => $gallery,
            'related_products' => $related,
            'css_files'        => ['style.css', 'product-detail.css']
        ];

        $this->view('Client/product_detail', $data, 'client_layout');
    }
}
