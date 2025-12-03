<?php
namespace App\Controllers\Client;
use App\Core\Controller;

class AboutController extends Controller {
    
    public function index() {
        // Chuẩn bị dữ liệu (nếu cần lấy thông tin công ty từ DB thì gọi Model Setting)
        // Ở đây mình dùng tĩnh cho đơn giản
        
        $data = [
            'title'     => 'Về chúng tôi - Thuỷ Sản Giá Tốt',
            // Bạn có thể tạo file about.css riêng hoặc viết inline
            'css_files' => ['style.css'] 
        ];

        $this->view('Client/about', $data, 'client_layout');
    }
}