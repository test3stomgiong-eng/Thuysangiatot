<?php
namespace App\Controllers\Admin;
use App\Core\Controller;
use App\Models\Setting;

class SettingController extends Controller {

    public function __construct() {
        if (!isset($_SESSION['customer_user']) || $_SESSION['customer_user']['role'] !== 'admin') {
            header("Location: /"); exit();
        }
    }

    // 1. HIỆN FORM CẤU HÌNH CHUNG
    public function index() {
        $settingModel = new Setting();
        $settings = $settingModel->getSettings(); // Lấy dữ liệu cũ ra

        $data = [
            'title'    => 'Cấu hình Website',
            'settings' => $settings
        ];
        $this->view('Admin/setting-general', $data, 'admin_layout');
    }

    // 2. XỬ LÝ LƯU (POST)
    public function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $settingModel = new Setting();

            // Danh sách các key cần lưu (Trừ logo ra xử lý riêng)
            $keys = [
                'site_title', 'site_email', 'site_hotline', 'site_address', 
                'social_facebook', 'social_zalo', 'seo_description', 'seo_keywords'
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
    private function uploadLogo($file) {
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
}