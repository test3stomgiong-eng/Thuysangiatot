<?php
// app/Core/Autoload.php

spl_autoload_register(function ($class_name) {
    // 1. Xác định thư mục gốc của namespace (App\) tương ứng với thư mục app/
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../'; // Trỏ ra ngoài thư mục Core, về lại thư mục app

    // 2. Kiểm tra xem class đang gọi có dùng namespace App\ không
    $len = strlen($prefix);
    if (strncmp($prefix, $class_name, $len) !== 0) {
        return;
    }

    // 3. Lấy tên class sau khi bỏ prefix (ví dụ: Controllers\Client\Home)
    $relative_class = substr($class_name, $len);

    // 4. Tạo đường dẫn file (thay dấu \ thành / để chạy được trên cả Windows/Linux)
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // 5. Nếu file tồn tại thì require nó
    if (file_exists($file)) {
        require $file;
    }
});