<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\News;
use App\Models\Category;

class NewsController extends Controller
{

    public function __construct()
    {
        // Kiểm tra quyền Admin
        if (!isset($_SESSION['customer_user']) || $_SESSION['customer_user']['role'] !== 'admin') {
            header("Location: /");
            exit();
        }
    }

    // -----------------------------------------------------------
    // 1. DANH SÁCH TIN TỨC
    // URL: /admin/news
    // -----------------------------------------------------------
    public function index()
    {
        $newsModel = new News();

        // Lấy từ khóa tìm kiếm
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : null;

        $newsList = $newsModel->getAllAdmin($keyword);

        $data = [
            'title'    => 'Quản lý Tin tức',
            'newsList' => $newsList
        ];

        // 👇 Gọi View danh sách: 'Admin/news'
        $this->view('Admin/news', $data, 'admin_layout');
    }

    // -----------------------------------------------------------
    // 2. FORM THÊM / SỬA (Gộp chung)
    // URL: /admin/news/form (Thêm) hoặc /admin/news/form/ID (Sửa)
    // -----------------------------------------------------------
    // app/Controllers/Admin/NewsController.php

    public function form($id = null)
    {
        $news = null;
        if ($id) {
            $newsModel = new News();
            $news = $newsModel->find($id);
        }

        // 👇 GỌI MODEL DANH MỤC TIN TỨC (MỚI)
        $newsCateModel = new \App\Models\NewsCategory();

        // Lấy danh sách đã sắp xếp theo cây
        $categories = $newsCateModel->getTree();

        $data = [
            'title'      => $id ? 'Sửa bài viết' : 'Thêm bài viết',
            'news'       => $news,
            'categories' => $categories
        ];

        $this->view('Admin/news_add', $data, 'admin_layout');
    }

    // -----------------------------------------------------------
    // 3. XỬ LÝ LƯU (INSERT / UPDATE)
    // URL: /admin/news/save
    // -----------------------------------------------------------
    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id = !empty($_POST['id']) ? $_POST['id'] : null;

            // Xử lý ảnh đại diện (Thumbnail)
            $thumbnail = $_POST['old_thumbnail'] ?? '';
            if (!empty($_FILES['thumbnail']['name'])) {
                $thumbnail = $this->uploadFile($_FILES['thumbnail']);
            }

            // Tạo slug tự động nếu người dùng để trống
            $slug = !empty($_POST['slug']) ? $_POST['slug'] : $this->toSlug($_POST['title']);

            $data = [
                'id'          => $id,
                'title'       => $_POST['title'],
                'slug'        => $slug,
                'category_id' => $_POST['category_id'],
                'summary'     => $_POST['summary'],
                'content'     => $_POST['content'], // Nội dung từ CKEditor
                'status'      => $_POST['status'],
                'thumbnail'   => $thumbnail,
                'author_id'   => $_SESSION['customer_user']['id'] // Lấy ID admin đang đăng nhập
            ];

            $newsModel = new News();

            if ($id) {
                // Nếu có ID -> Cập nhật
                $newsModel->update($data);
            } else {
                // Nếu không có ID -> Thêm mới
                $newsModel->create($data);
            }

            // Xong thì quay về danh sách
            header("Location: /admin/news");
            exit();
        }
    }

    // -----------------------------------------------------------
    // 4. XÓA TIN TỨC
    // URL: /admin/news/delete/ID
    // -----------------------------------------------------------
    public function delete($id)
    {
        $newsModel = new News();

        // Tìm bài viết để lấy tên ảnh cũ xóa đi cho sạch server
        $news = $newsModel->find($id);

        if ($news && !empty($news->thumbnail)) {
            $path = ROOT_PATH . '/public/assets/uploads/news/' . $news->thumbnail;
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $newsModel->delete($id);
        header("Location: /admin/news");
        exit();
    }

    // --- HÀM PHỤ: UPLOAD ẢNH RIÊNG CHO TIN TỨC ---
    private function uploadFile($file)
    {
        // Lưu vào thư mục: public/assets/uploads/news/
        $targetDir = ROOT_PATH . "/public/assets/uploads/news/";

        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $fileName = 'news_' . time() . '_' . rand(100, 999) . '.' . $ext;

        if (move_uploaded_file($file['tmp_name'], $targetDir . $fileName)) {
            return $fileName;
        }
        return '';
    }

    // --- HÀM PHỤ: TẠO SLUG ---
    private function toSlug($str)
    {
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return $str;
    }
}
