<?php

namespace App\Controllers\Client;

use App\Core\Controller;
use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $newsModel = new News();
        $keyword = $_GET['keyword'] ?? null;

        // Lấy danh sách tin (Sử dụng hàm getAllAdmin cũng được vì nó trả về list có search)
        $newsList = $newsModel->getAllAdmin($keyword);

        $data = [
            'title' => 'Tin tức & Kỹ thuật nuôi tôm',
            'newsList' => $newsList,
            'css_files' => ['style.css', 'news.css'] // Nếu bạn có file css riêng
        ];
        $this->view('Client/news', $data, 'client_layout');
    }

    public function detail($id)
    {
        $newsModel = new \App\Models\News();
        $prodModel = new \App\Models\Product();

        // 1. Lấy bài viết chi tiết
        $news = $newsModel->find($id);

        if (!$news) {
            header("Location: /news");
            exit();
        }

        // --- 👇 BỔ SUNG: TĂNG LƯỢT XEM (CÓ CHECK SESSION) 👇 ---
        $sessionKey = 'viewed_news_' . $id; // Key session riêng cho tin tức

        if (!isset($_SESSION[$sessionKey])) {
            // 1. Gọi Model tăng view trong DB (Hàm này mình đã thêm vào Model News ở bước trước)
            $newsModel->increaseView($id);

            // 2. Lưu session đánh dấu đã xem
            $_SESSION[$sessionKey] = true;

            // 3. Cập nhật biến $news để hiển thị số view mới nhất ra giao diện
            $news->views++;
        }
        // -------------------------------------------------------

        // 2. Lấy bài viết liên quan
        $related_news = $newsModel->getLatestNews(3);

        // 3. Lấy sản phẩm gợi ý
        $suggest_products = $prodModel->getNewProducts(3);

        $data = [
            'title'            => $news->title,
            'news'             => $news,
            'related_news'     => $related_news,
            'suggest_products' => $suggest_products,
            'css_files'        => ['style.css', 'news.css'] // Hoặc blog.css
        ];

        $this->view('Client/news_detail', $data, 'client_layout');
    }
}
