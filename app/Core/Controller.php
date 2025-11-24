<?php

namespace App\Core;

// Nhớ nạp Model Setting
require_once "../app/Models/Setting.php";

use App\Models\Setting;

class Controller
{
    // Biến lưu trữ settings dùng chung
    protected $settings = [];

    public function __construct()
    {
        // Tự động tải settings mỗi khi Controller khởi chạy
        $settingModel = new Setting();
        $this->settings = $settingModel->getSettings();
    }

    public function model($model)
    {
        require_once "../app/Models/" . $model . ".php";
        return new $model;
    }

    public function view($view, $data = [], $layout = 'client_layout')
    {
        // 1. Trộn biến $settings vào dữ liệu gửi sang View
        // Để bên View bạn có thể dùng biến $site_title, $site_hotline...
        $data = array_merge($data, $this->settings);

        // 2. Extract dữ liệu ra thành biến
        extract($data);

        // 3. Xử lý view như cũ
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
