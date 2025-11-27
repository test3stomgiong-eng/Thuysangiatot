<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Category;

class CategoryController extends Controller
{

    public function __construct()
    {
        // 1. Kiểm tra đăng nhập
        if (!isset($_SESSION['customer_user'])) {
            header("Location: /auth/login");
            exit();
        }

        // 2. Kiểm tra quyền Admin (Nếu không phải admin thì đá về trang chủ)
        if ($_SESSION['customer_user']['role'] !== 'admin') {
            header("Location: /");
            exit();
        }
    }

    private function recursiveSort($source, $parent_id = 0, $level = 0, &$result = [])
    {
        if (!empty($source)) {
            foreach ($source as $key => $value) {
                // Nếu item này là con của parent_id đang xét
                if ($value->parent_id == $parent_id) {
                    // Gán thêm thuộc tính level để biết thụt dòng bao nhiêu
                    $value->level = $level;

                    // Đưa vào mảng kết quả
                    $result[] = $value;

                    // Xóa khỏi mảng gốc cho nhẹ (tùy chọn)
                    // unset($source[$key]);

                    // Tiếp tục tìm con của ông này (Đệ quy)
                    $this->recursiveSort($source, $value->id, $level + 1, $result);
                }
            }
        }
    }

    // ---------------------------------------------------------
    // 1. HIỆN DANH SÁCH DANH MỤC
    // URL: /admin/category
    // ---------------------------------------------------------
    // app/Controllers/Admin/CategoryController.php

    public function index()
    {
        $cateModel = new Category();

        // 1. Lấy dữ liệu từ thanh tìm kiếm (GET)
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : null;
        $status  = isset($_GET['status']) ? $_GET['status'] : null;

        // 2. Gọi Model lấy dữ liệu đã lọc
        $categories = $cateModel->getAll($keyword, $status);

        // 3. Xử lý hiển thị
        // Nếu KHÔNG tìm kiếm -> Sắp xếp đẹp theo cây thư mục (Cha/Con)
        if (empty($keyword) && ($status === null || $status === '')) {
            $sortedCategories = [];
            $this->recursiveSort($categories, 0, 0, $sortedCategories);
            $categories = $sortedCategories;
        }
        // Nếu ĐANG tìm kiếm -> Giữ nguyên danh sách phẳng (Flat list) để hiển thị kết quả tìm được

        $data = [
            'title' => 'Quản lý Danh mục',
            'categories' => $categories
        ];

        $this->view('Admin/categories', $data, 'admin_layout');
    }

    // ---------------------------------------------------------
    // 2. HIỆN FORM THÊM MỚI
    // URL: /admin/category/add
    // ---------------------------------------------------------
    // app/Controllers/Admin/CategoryController.php

    public function add()
    {
        // 1. Gọi Model lấy tất cả danh mục
        $cateModel = new Category();
        $categories = $cateModel->getAll();

        // 2. Gửi biến $categories sang View
        $data = [
            'title'      => 'Thêm danh mục mới',
            'categories' => $categories // <--- QUAN TRỌNG: Phải có dòng này mới có dữ liệu
        ];

        // Load view
        $this->view('Admin/category-add', $data, 'admin_layout');
    }

    // ---------------------------------------------------------
    // 3. XỬ LÝ LƯU DANH MỤC (POST)
    // URL: /admin/category/store
    // ---------------------------------------------------------
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $slug = !empty($_POST['slug']) ? $_POST['slug'] : $this->toSlug($name);

            // 👇 THÊM DÒNG NÀY: Lấy parent_id từ form (nếu không có thì mặc định là 0)
            $parent_id = isset($_POST['parent_id']) ? $_POST['parent_id'] : 0;

            $desc = isset($_POST['description']) ? $_POST['description'] : '';
            $status = $_POST['status'];

            $cateModel = new Category();
            $cateModel->create([
                'name'        => $name,
                'slug'        => $slug,
                'parent_id'   => $parent_id,
                'description' => $desc,
                'status'      => $status
            ]);

            header("Location: /admin/category");
        }
    }

    // ---------------------------------------------------------
    // 4. XÓA DANH MỤC
    // URL: /admin/category/delete/ID
    // ---------------------------------------------------------
    public function delete($id)
    {
        $cateModel = new Category();
        $cateModel->delete($id);
        header("Location: /admin/category");
    }

    // 
    // app/Controllers/Admin/CategoryController.php

    // 1. Hiện Form Sửa (GET)
    public function edit($id)
    {
        $cateModel = new Category();

        // Lấy thông tin danh mục cần sửa
        $category = $cateModel->find($id);

        // Lấy danh sách để chọn cha (Parent)
        $allCategories = $cateModel->getAll();

        if (!$category) {
            // Nếu ID không tồn tại thì về danh sách
            header("Location: /admin/category");
            exit();
        }

        $data = [
            'title'      => 'Chỉnh sửa danh mục',
            'category'   => $category,      // Dữ liệu cũ
            'categories' => $allCategories  // List danh mục cha
        ];

        // Load view sửa (Bạn tạo file này ở bước 3)
        $this->view('Admin/category-edit', $data, 'admin_layout');
    }

    // 2. Xử lý Lưu cập nhật (POST)
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id']; // ID của danh mục đang sửa
            $name = $_POST['name'];
            $slug = !empty($_POST['slug']) ? $_POST['slug'] : $this->toSlug($name);

            // 👇 QUAN TRỌNG: Lấy parent_id (nếu không chọn thì là 0)
            $parent_id = isset($_POST['parent_id']) ? $_POST['parent_id'] : 0;

            $desc = isset($_POST['description']) ? $_POST['description'] : '';
            $status = $_POST['status'];

            $cateModel = new Category();
            $cateModel->update([
                'id'          => $id,
                'name'        => $name,
                'slug'        => $slug,
                'parent_id'   => $parent_id, // 👈 Truyền sang Model
                'description' => $desc,
                'status'      => $status
            ]);

            header("Location: /admin/category");
        }
    }

    // --- HÀM PHỤ: CHUYỂN TIẾNG VIỆT CÓ DẤU THÀNH SLUG ---
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
