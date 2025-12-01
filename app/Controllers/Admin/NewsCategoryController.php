<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Models\NewsCategory;

class NewsCategoryController extends Controller {

    public function __construct() {
        if (!isset($_SESSION['customer_user']) || $_SESSION['customer_user']['role'] !== 'admin') {
            header("Location: /"); exit();
        }
    }

    // 1. DANH SÁCH (Giống hệt ảnh bạn gửi)
    public function index() {
        $model = new NewsCategory();
        $rawData = $model->getAll();

        // Sắp xếp đệ quy (Cha - Con)
        $sortedCategories = [];
        $this->recursiveSort($rawData, 0, 0, $sortedCategories);

        $data = [
            'title' => 'Danh mục Tin tức',
            'categories' => $sortedCategories
        ];
        
        // Gọi View danh sách
        $this->view('Admin/news_categories', $data, 'admin_layout');
    }

    // 2. FORM THÊM / SỬA
    public function form($id = null) {
        $model = new NewsCategory();
        $category = null;
        
        if ($id) {
            $category = $model->find($id);
        }
        
        // Lấy danh sách để chọn cha
        $rawData = $model->getAll();
        $sortedCategories = [];
        $this->recursiveSort($rawData, 0, 0, $sortedCategories);

        $data = [
            'title' => $id ? 'Sửa danh mục tin' : 'Thêm danh mục tin',
            'category' => $category,
            'categories' => $sortedCategories
        ];

        $this->view('Admin/news_category_form', $data, 'admin_layout');
    }

    // 3. LƯU (STORE / UPDATE)
    public function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = !empty($_POST['id']) ? $_POST['id'] : null;
            
            $data = [
                'id'        => $id,
                'name'      => $_POST['name'],
                'slug'      => !empty($_POST['slug']) ? $_POST['slug'] : $this->toSlug($_POST['name']),
                'parent_id' => $_POST['parent_id'] ?? 0,
                'status'    => $_POST['status']
            ];

            $model = new NewsCategory();
            if ($id) {
                $model->update($data);
            } else {
                $model->create($data);
            }
            header("Location: /admin/newscategory");
        }
    }

    // 4. XÓA
    public function delete($id) {
        $model = new NewsCategory();
        $model->delete($id);
        header("Location: /admin/newscategory");
    }

    // Hàm đệ quy sắp xếp
    private function recursiveSort($source, $parent_id, $level, &$result) {
        if (!empty($source)) {
            foreach ($source as $value) {
                if ($value->parent_id == $parent_id) {
                    $value->level = $level;
                    $result[] = $value;
                    $this->recursiveSort($source, $value->id, $level + 1, $result);
                }
            }
        }
    }

    // Hàm tạo slug
    private function toSlug($str) {
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