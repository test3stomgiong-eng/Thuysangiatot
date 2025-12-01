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

        // 2. Kiểm tra quyền Admin
        if ($_SESSION['customer_user']['role'] !== 'admin') {
            header("Location: /");
            exit();
        }
    }

    // --- HÀM HỖ TRỢ: SẮP XẾP CÂY THƯ MỤC ---
    private function recursiveSort($source, $parent_id = 0, $level = 0, &$result = [])
    {
        if (!empty($source)) {
            foreach ($source as $key => $value) {
                if ($value->parent_id == $parent_id) {
                    $value->level = $level; // Gán cấp độ để thụt dòng
                    $result[] = $value;
                    $this->recursiveSort($source, $value->id, $level + 1, $result);
                }
            }
        }
    }

    // ---------------------------------------------------------
    // 1. HIỆN DANH SÁCH DANH MỤC
    // URL: /admin/category
    // ---------------------------------------------------------
    public function index()
    {
        $cateModel = new Category();

        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : null;
        $status  = isset($_GET['status']) ? $_GET['status'] : null;

        $categories = $cateModel->getAll($keyword, $status);

        // Nếu không tìm kiếm -> Sắp xếp theo cây thư mục cho đẹp
        if (empty($keyword) && ($status === null || $status === '')) {
            $sortedCategories = [];
            $this->recursiveSort($categories, 0, 0, $sortedCategories);
            $categories = $sortedCategories;
        }

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
    public function add()
    {
        $cateModel = new Category();
        $rawCategories = $cateModel->getAll();
        
        // Sắp xếp để dropdown hiển thị phân cấp
        $categories = [];
        $this->recursiveSort($rawCategories, 0, 0, $categories);

        $data = [
            'title'      => 'Thêm danh mục mới',
            'categories' => $categories 
        ];

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
            $parent_id = isset($_POST['parent_id']) ? $_POST['parent_id'] : 0;
            $desc = isset($_POST['description']) ? $_POST['description'] : '';
            $status = $_POST['status'];
            
            // Lấy Icon Class
            $icon_class = !empty($_POST['icon_class']) ? $_POST['icon_class'] : 'fa-solid fa-folder-open';

            // Xử lý Upload Ảnh
            $image = '';
            if (!empty($_FILES['image']['name'])) {
                $image = $this->uploadIcon($_FILES['image']);
            }

            $cateModel = new Category();
            $cateModel->create([
                'name'        => $name,
                'slug'        => $slug,
                'parent_id'   => $parent_id,
                'description' => $desc,
                'status'      => $status,
                'image'       => $image,      // <-- Mới
                'icon_class'  => $icon_class  // <-- Mới
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
        
        // Xóa ảnh cũ nếu có
        $category = $cateModel->find($id);
        if ($category && !empty($category->image)) {
            $path = ROOT_PATH . '/public/assets/uploads/categories/' . $category->image;
            if (file_exists($path)) unlink($path);
        }

        $cateModel->delete($id);
        header("Location: /admin/category");
    }

    // ---------------------------------------------------------
    // 5. HIỆN FORM SỬA (GET)
    // ---------------------------------------------------------
    public function edit($id)
    {
        $cateModel = new Category();
        $category = $cateModel->find($id);

        if (!$category) {
            header("Location: /admin/category");
            exit();
        }

        // Lấy danh sách cha và sắp xếp
        $rawCategories = $cateModel->getAll();
        $categories = [];
        $this->recursiveSort($rawCategories, 0, 0, $categories);

        $data = [
            'title'      => 'Chỉnh sửa danh mục',
            'category'   => $category,
            'categories' => $categories
        ];

        $this->view('Admin/category-edit', $data, 'admin_layout');
    }

    // ---------------------------------------------------------
    // 6. XỬ LÝ CẬP NHẬT (POST)
    // ---------------------------------------------------------
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $slug = !empty($_POST['slug']) ? $_POST['slug'] : $this->toSlug($name);
            $parent_id = isset($_POST['parent_id']) ? $_POST['parent_id'] : 0;
            $desc = isset($_POST['description']) ? $_POST['description'] : '';
            $status = $_POST['status'];
            $icon_class = !empty($_POST['icon_class']) ? $_POST['icon_class'] : 'fa-solid fa-folder-open';

            // Xử lý ảnh
            $image = $_POST['old_image']; // Giữ ảnh cũ mặc định
            if (!empty($_FILES['image']['name'])) {
                $newImage = $this->uploadIcon($_FILES['image']);
                if ($newImage) {
                    $image = $newImage;
                    // Xóa ảnh cũ vật lý
                    if (!empty($_POST['old_image'])) {
                        $oldPath = ROOT_PATH . '/public/assets/uploads/categories/' . $_POST['old_image'];
                        if (file_exists($oldPath)) unlink($oldPath);
                    }
                }
            }

            $cateModel = new Category();
            $cateModel->update([
                'id'          => $id,
                'name'        => $name,
                'slug'        => $slug,
                'parent_id'   => $parent_id,
                'description' => $desc,
                'status'      => $status,
                'image'       => $image,     // <-- Mới
                'icon_class'  => $icon_class // <-- Mới
            ]);

            header("Location: /admin/category");
        }
    }

    // --- HÀM PHỤ: UPLOAD ẢNH DANH MỤC ---
    private function uploadIcon($file) {
        $targetDir = ROOT_PATH . "/public/assets/uploads/categories/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $fileName = 'cat_' . time() . '_' . rand(100,999) . '.' . $ext;
        
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