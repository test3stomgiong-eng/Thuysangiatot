<?php

namespace App\Core;

class App
{
    protected $controller = 'HomeController'; // Mặc định vào trang chủ
    protected $action = 'index';              // Hàm mặc định
    protected $params = [];
    protected $folder = 'Client';             // Mặc định là folder Client

    public function __construct()
    {
        $url = $this->getUrl();

        // 1. Xử lý Routing cho ADMIN
        // Nếu url bắt đầu bằng 'admin' -> Chuyển folder sang Admin
        if (isset($url[0]) && strtolower($url[0]) == 'admin') {
            $this->folder = 'Admin';
            array_shift($url); // Xóa chữ 'admin' khỏi mảng url

            // Nếu vào /admin mà không gõ gì thêm -> Mặc định gọi DashboardController
            if (empty($url)) {
                $this->controller = 'DashboardController';
            }
        }

        // 2. Xác định tên File Controller
        // 2. Xác định Controller
        if (isset($url[0])) {
            // Tạo tên file controller theo URL
            $urlController = ucfirst($url[0]) . "Controller";
            $checkFile = "../app/Controllers/" . $this->folder . "/" . $urlController . ".php";

            if (file_exists($checkFile)) {
                $this->controller = $urlController;
                unset($url[0]);
            } else {
                // 👇 THÊM ĐOẠN NÀY: Nếu gõ sai tên Controller thì báo lỗi 404 hoặc về trang chủ
                // Chứ không để nó tự gọi HomeController trong Admin gây lỗi
                if ($this->folder == 'Admin') {
                    // Nếu đang ở Admin mà gõ sai -> Về Dashboard
                    $this->controller = 'DashboardController';
                } else {
                    // Nếu ở Client mà gõ sai -> Về trang chủ hoặc trang 404
                    $this->controller = 'HomeController';
                }
            }
        } else {
            // Nếu không gõ gì sau /admin
            if ($this->folder == 'Admin') {
                $this->controller = 'DashboardController';
            }
        }

        // Gán namespace và Khởi tạo Controller
        require_once "../app/Controllers/" . $this->folder . "/" . $this->controller . ".php";
        $controllerClass = "App\\Controllers\\" . $this->folder . "\\" . $this->controller;

        // Tạo đối tượng Controller (Ví dụ: new HomeController())
        $this->controller = new $controllerClass;

        // 3. Xác định Action (Tên hàm trong class)
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->action = $url[1];
                unset($url[1]);
            }
        }

        // 4. Xử lý tham số (Params)
        $this->params = $url ? array_values($url) : [];

        // 5. Chạy hàm thực thi
        call_user_func_array([$this->controller, $this->action], $this->params);
    }

    // Hàm lấy URL từ trình duyệt
    public function getUrl()
    {
        $uri = $_SERVER['REQUEST_URI'];

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        $uri = trim($uri, '/');

        if (!empty($uri)) {
            return explode('/', filter_var($uri, FILTER_SANITIZE_URL));
        }
        return [];
    }
}
