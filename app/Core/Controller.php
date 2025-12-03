<?php
namespace App\Core;

// Nạp các Model cần dùng chung
require_once "../app/Models/Setting.php";
require_once "../app/Models/Category.php"; 
// require_once "../app/Models/NewsCategory.php"; // Nếu bạn có file này thì mở comment ra, không thì query trực tiếp cũng được

use App\Models\Setting;
use App\Models\Category;

class Controller
{
    // Biến lưu trữ dữ liệu dùng chung
    protected $settings = [];
    protected $menuCategories = []; // Menu Sản phẩm
    protected $newsMenu = [];       // Menu Tin tức (Mới)

    public function __construct()
    {
        // 1. Tự động tải Cấu hình (Settings)
        $settingModel = new Setting();
        $this->settings = $settingModel->getSettings();

        // 2. Tự động tải Menu
        $cateModel = new Category(); // Dùng tạm model này để query cũng được vì nó có hàm query()

        // --- A. MENU SẢN PHẨM (Lấy con của ID 1 trong bảng product_categories) ---
        $sqlProd = "SELECT * FROM product_categories WHERE parent_id = 1 AND status = 1 ORDER BY id ASC";
        $stmt = $cateModel->query($sqlProd);
        $stmt->execute();
        $this->menuCategories = $stmt->fetchAll();

        // --- B. MENU KIẾN THỨC (Lấy con của ID 7 trong bảng news_categories) ---
        // 👇 ĐÃ SỬA: Đổi tên bảng thành `news_categories`
        // Lưu ý: Bạn cần chắc chắn trong bảng này có danh mục cha ID = 7 nhé
        $sqlNews = "SELECT * FROM news_categories WHERE parent_id = 7 AND status = 1 ORDER BY id ASC";
        
        $stmtNews = $cateModel->query($sqlNews);
        $stmtNews->execute();
        $this->newsMenu = $stmtNews->fetchAll();
    }

    public function model($model)
    {
        require_once "../app/Models/" . $model . ".php";
        return new $model;
    }

    public function view($view, $data = [], $layout = 'client_layout')
    {
        // 1. Trộn Settings
        $data = array_merge($data, $this->settings);
        
        // 2. Trộn Menu
        $data['menu_categories'] = $this->menuCategories;
        $data['news_menu']       = $this->newsMenu;

        // 3. Extract
        extract($data);

        // 4. Require View
        $viewContent = "../app/Views/" . $view . ".php";
        if (file_exists("../app/Views/Layouts/" . $layout . ".php")) {
            require_once "../app/Views/Layouts/" . $layout . ".php";
        } else {
            if (file_exists($viewContent)) {
                require_once $viewContent;
            } else {
                echo "View not found: " . $view;
            }
        }
    }
}