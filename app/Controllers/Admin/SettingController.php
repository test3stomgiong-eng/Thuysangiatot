<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Setting;

class SettingController extends Controller
{

    public function __construct()
    {
        if (!isset($_SESSION['customer_user']) || $_SESSION['customer_user']['role'] !== 'admin') {
            header("Location: /");
            exit();
        }
    }

    // 1. HIỆN FORM CẤU HÌNH CHUNG
    public function index()
    {
        $settingModel = new Setting();
        $settings = $settingModel->getSettings(); // Lấy dữ liệu cũ ra

        $data = [
            'title'    => 'Cấu hình Website',
            'settings' => $settings
        ];
        $this->view('Admin/setting-general', $data, 'admin_layout');
    }

    // 2. XỬ LÝ LƯU (POST)
    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $settingModel = new Setting();

            // Danh sách các key cần lưu (Trừ logo ra xử lý riêng)
            $keys = [
                'site_title',
                'site_email',
                'site_hotline',
                'site_address',
                'social_facebook',
                'social_zalo',
                'seo_description',
                'seo_keywords'
            ];

            // Lưu các trường text
            foreach ($keys as $key) {
                if (isset($_POST[$key])) {
                    $settingModel->updateValue($key, $_POST[$key]);
                }
            }

            // Xử lý Upload Logo (Nếu có chọn ảnh mới)
            if (!empty($_FILES['site_logo']['name'])) {
                $logoName = $this->uploadLogo($_FILES['site_logo']);
                if ($logoName) {
                    $settingModel->updateValue('site_logo', $logoName);
                }
            }

            echo "<script>alert('Cập nhật cấu hình thành công!'); window.location.href='/admin/setting';</script>";
        }
    }

    // Hàm upload logo riêng
    private function uploadLogo($file)
    {
        $targetDir = ROOT_PATH . '/public/assets/uploads/common/'; // Tạo thư mục common nhé
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $fileName = 'logo.' . $ext; // Đặt tên cố định là logo.png/jpg luôn cho gọn
        $targetFile = $targetDir . $fileName;

        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg'])) {
            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                return 'assets/uploads/common/' . $fileName; // Trả về đường dẫn để lưu DB
            }
        }
        return false;
    }

    // ============================================================
    // PHẦN 2: CẤU HÌNH BANNER (THÊM MỚI VÀO ĐÂY)
    // ============================================================

    // 1. Hiển thị Form Banner
    public function banner()
    {
        $settingModel = new Setting();
        $settings = $settingModel->getSettings(); // Lấy tất cả cấu hình

        $data = [
            'title'    => 'Cấu hình Banner Quảng cáo',
            'settings' => $settings
        ];
        // Tạo thêm view mới tên là setting_banner.php
        $this->view('Admin/setting_banner', $data, 'admin_layout');
    }

    // 2. Lưu Banner
    public function saveBanner()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $settingModel = new Setting();

            // Danh sách các file ảnh banner cần xử lý
            $imageFields = ['banner_main_img', 'banner_sub1_img', 'banner_sub2_img'];

            foreach ($imageFields as $field) {
                // Nếu có upload ảnh mới
                if (!empty($_FILES[$field]['name'])) {
                    // Gọi hàm upload chung
                    $fileName = $this->uploadFile($_FILES[$field]);
                    if ($fileName) {
                        $settingModel->updateValue($field, $fileName);
                    }
                }
            }

            // Lưu các trường văn bản (Link, Title...)
            $textFields = ['banner_main_link', 'banner_sub1_link', 'banner_sub1_title', 'banner_sub2_link', 'banner_sub2_title'];

            foreach ($textFields as $field) {
                if (isset($_POST[$field])) {
                    $settingModel->updateValue($field, $_POST[$field]);
                }
            }

            echo "<script>alert('Cập nhật banner thành công!'); window.location.href='/admin/setting/banner';</script>";
        }
    }

    // ============================================================
    // HÀM UPLOAD DÙNG CHUNG (SỬA LẠI HÀM CŨ CHO LINH HOẠT)
    // ============================================================
    private function uploadFile($file)
    {
        // Lưu vào thư mục banners cho gọn
        $targetDir = ROOT_PATH . "/public/assets/uploads/banners/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Tạo tên ngẫu nhiên để tránh trùng lặp (banner_12345.jpg)
        $fileName = 'banner_' . time() . '_' . rand(100, 999) . '.' . $ext;

        // Nếu là logo thì đặt tên cố định (tùy bạn chọn cách nào)
        // if ($file['name'] == 'logo') $fileName = 'logo.' . $ext;

        if (move_uploaded_file($file['tmp_name'], $targetDir . $fileName)) {
            // Chỉ trả về tên file (ngắn gọn) để lưu DB
            return $fileName;
        }
        return false;
    }
}
