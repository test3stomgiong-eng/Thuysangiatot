<?php
// public/index.php
session_start();
// 1. Định nghĩa đường dẫn gốc (để sau này dễ gọi file)
define('ROOT_PATH', dirname(__DIR__)); 

// 2. Gọi file Autoload vừa tạo ở trên
require_once ROOT_PATH . '/app/Core/Autoload.php';

// 3. Sử dụng namespace và Khởi chạy App
use App\Core\App;

$app = new App();