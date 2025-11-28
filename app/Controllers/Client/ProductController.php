<?php

namespace App\Controllers\Client;

use App\Core\Controller;
use App\Models\Product;

class ProductController extends Controller
{

    // 1. TRANG DANH SÁCH SẢN PHẨM (/product)
    public function index()
    {
        $productModel = new Product();
        $cat_id = isset($_GET['cat']) ? $_GET['cat'] : null;

        $products = $productModel->getAllClient($cat_id);

        $data = [
            'title'     => 'Danh sách sản phẩm',
            'products'  => $products,
            'css_files' => ['style.css', 'products.css']
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

        $productModel = new Product();
        $product = $productModel->find($id);

        if (!$product) {
            echo "Sản phẩm không tồn tại!";
            return;
        }

        $gallery = $productModel->getGallery($id);
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
