<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TS-AQUA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/admin/admin.css">
</head>

<body>

    <div class="admin-overlay"></div>

    <div class="admin-wrapper">

        <aside class="sidebar">
            <div class="sidebar-brand">
                <i class="fa-solid fa-shield-cat"></i> <span>ADMIN TS</span>
            </div>
            <ul class="sidebar-menu">
                <li><a href="index.html" class="active"><i class="fa-solid fa-gauge"></i> <span>Tổng quan</span></a></li>
                <li><a href="products.html"><i class="fa-solid fa-box"></i> <span>Sản phẩm</span></a></li>
                <li><a href="orders.html"><i class="fa-solid fa-cart-shopping"></i> <span>Đơn hàng</span> <span class="badge">3</span></a></li>
                <li><a href="customers.html"><i class="fa-solid fa-users"></i> <span>Khách hàng</span></a></li>
                <li><a href="staff.html"><i class="fa-solid fa-id-card"></i> <span>Nhân viên</span></a></li>
                <li><a href="news.html"><i class="fa-solid fa-newspaper"></i> <span>Tin tức</span></a></li>
                <li class="divider"></li>
                <li><a href="#"><i class="fa-solid fa-gear"></i> <span>Cấu hình</span></a></li>
                <li><a href="#"><i class="fa-solid fa-right-from-bracket"></i> <span>Đăng xuất</span></a></li>
            </ul>
        </aside>

        <main class="main-content">

            <header class="top-header">
                <div class="menu-toggle"><i class="fa-solid fa-bars"></i></div>
                <div class="header-right">
                    <div class="user-dropdown" onclick="document.getElementById('userMenu').classList.toggle('show')">
                        <div class="user-info">
                            <span>Xin chào, Admin <i class="fa-solid fa-caret-down"></i></span>
                            <img src="https://placehold.co/40x40" alt="Admin">
                        </div>
                        <ul class="dropdown-menu" id="userMenu">
                            <li><a href="#"><i class="fa-regular fa-user"></i> Thông tin</a></li>
                            <li><a href="#" class="text-red"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a></li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- code layout -->
            <?php
            // Đây là nơi nội dung Dashboard, Sản phẩm... sẽ được nhồi vào
            if (file_exists($viewContent)) {
                require_once $viewContent;
            }
            ?>
            <!-- code -->
        </main>
    </div>

    <script>
        const toggleBtn = document.querySelector('.menu-toggle');
        const body = document.body;
        const overlay = document.querySelector('.admin-overlay');

        toggleBtn.addEventListener('click', () => {
            if (window.innerWidth > 768) {
                // PC: Thêm class để thu nhỏ menu
                body.classList.toggle('collapsed');
            } else {
                // Mobile: Thêm class để hiện menu trượt
                body.classList.toggle('mobile-open');
            }
        });

        // Bấm ra ngoài (vùng đen) thì đóng menu mobile
        overlay.addEventListener('click', () => {
            body.classList.remove('mobile-open');
        });

        // Tự động reset khi thay đổi kích thước màn hình
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                body.classList.remove('mobile-open');
            } else {
                body.classList.remove('collapsed');
            }
        });
    </script>
</body>

</html>