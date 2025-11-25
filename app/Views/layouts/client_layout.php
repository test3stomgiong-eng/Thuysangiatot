<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title><?php echo isset($title) ? $title : $site_title; ?></title>
    <?php
    // Kiểm tra xem Controller có gửi mảng $css_files sang không
    if (isset($css_files) && is_array($css_files)) {
        foreach ($css_files as $file) {
            echo '<link rel="stylesheet" href="/assets/client/css/' . $file . '">';
        }
    }
    ?>
</head>

<body>
    <div class="mobile-nav-overlay"></div>

    <nav class="mobile-nav-drawer">
        <div class="mobile-nav-header">
            <span class="nav-title">MENU</span>
            <button class="btn-close-menu"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <ul class="mobile-nav-list">
            <li><a href="#"><i class="fa-solid fa-house"></i> Trang chủ</a></li>
            <li><a href="products.html"><i class="fa-solid fa-capsules"></i> Sản phẩm</a></li>
            <li><a href="#"><i class="fa-solid fa-book-medical"></i> Kiến thức nuôi tôm</a></li>
            <li><a href="news.html"><i class="fa-solid fa-newspaper"></i> Tin tức & Sự kiện</a></li>
            <li><a href="#"><i class="fa-solid fa-phone"></i> Liên hệ</a></li>
            <li class="divider"></li>
            <li><a href="#"><i class="fa-solid fa-user"></i> Đăng nhập / Đăng ký</a></li>
        </ul>
    </nav>

    <header class="header">
        <div class="top-bar">
            <div class="container">
                <span title="<?php echo $site_hotline_desc; ?>"><i class="fa-solid fa-phone"></i> Hotline: <?php echo !empty($site_hotline) ? $site_hotline : '0299xxxxxx'; ?></span>
                <span class="separator">|</span>
                <span>Hệ thống cửa hàng</span>
            </div>
        </div>

        <div class="main-header">
            <div class="container flex-row">

                <div class="mobile-menu-btn">
                    <i class="fa-solid fa-bars"></i>
                </div>

                <a href="/index" class="logo-wrapper">
                    <div class="logo-icon">
                        <i class="fa-solid fa-layers-group"></i>
                    </div>
                    <div class="logo-text">
                        <span class="brand">Thuỷ Sản Giá Tốt</span>
                        <span class="slogan">Chất lượng cao - Niềm tin lớn</span>
                    </div>
                </a>

                <div class="search-container">
                    <form class="search-box-wrapper">
                        <input type="text" placeholder="Tìm tên thuốc, bệnh lý...">
                        <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </form>
                    <div class="search-tags">
                        <a href="#">Siêu Hot T11</a>
                        <a href="#">Bổ gan</a>
                        <a href="#">Trị ký sinh trùng</a>
                        <a href="#">Xử lý nước</a>
                        <a href="#">Khoáng tạt</a>
                    </div>
                </div>

                <div class="header-actions">
                    <a href="/cart" class="btn-cart">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span>Giỏ hàng</span>
                    </a>
                    <a href="/login" class="btn-login">
                        <i class="fa-regular fa-circle-user"></i>
                        <span>Đăng nhập</span>
                    </a>
                </div>
            </div>
        </div>

        <nav class="main-nav">
            <div class="container">
                <ul class="menu-root">
                    <li class="menu-item active"><a href="/index">Trang chủ</a></li>
                    <li class="menu-item has-child">
                        <a href="products.html">Sản phẩm <i class="fa-solid fa-angle-down"></i></a>
                        <ul class="sub-menu">
                            <li><a href="#">Thuốc trị bệnh gan tụy</a></li>
                            <li><a href="#">Men vi sinh xử lý nước</a></li>
                            <li><a href="#">Khoáng tạt & Dinh dưỡng</a></li>
                            <li><a href="#">Diệt khuẩn & Ký sinh trùng</a></li>
                        </ul>
                    </li>
                    <li class="menu-item has-child">
                        <a href="news.html">Kiến thức nuôi tôm <i class="fa-solid fa-angle-down"></i></a>
                        <ul class="sub-menu">
                            <li><a href="#">Phác đồ điều trị</a></li>
                            <li><a href="#">Giá tôm hôm nay</a></li>
                            <li><a href="#">Kỹ thuật gây màu nước</a></li>
                        </ul>
                    </li>
                    <li class="menu-item"><a href="#">Liên hệ</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="mobile-welcome-bar">
        <span>Xin chào, khách!</span>
        <a href="#">Đăng nhập <i class="fa-solid fa-chevron-right"></i></a>
    </div>

    <!-- ROUTER -->

    <main>
        <?php
        // Đây là nơi nội dung trang con sẽ hiển thị
        if (file_exists($viewContent)) {
            require_once $viewContent;
        }
        ?>
    </main>

    <!-- END ROUTER -->
    <footer class="footer">
        <div class="container footer-grid">
            <div class="col">
                <h4>Về Chúng Tôi</h4>
                <ul>
                    <li>Giới thiệu công ty</li>
                    <li>Hệ thống cửa hàng</li>
                    <li>Tuyển dụng</li>
                </ul>
            </div>
            <div class="col">
                <h4>Danh Mục</h4>
                <ul>
                    <li>Thuốc thủy sản</li>
                    <li>Dụng cụ đo môi trường</li>
                    <li>Máy móc thiết bị</li>
                </ul>
            </div>
            <div class="col">
                <h4>Hỗ Trợ Khách Hàng</h4>
                <ul>
                    <li>Chính sách đổi trả</li>
                    <li>Giao hàng - Thanh toán</li>
                    <li>Tư vấn kỹ thuật: 1800 xxxx</li>
                </ul>
            </div>
            <div class="col newsletter">
                <h4>Đăng ký nhận tin</h4>
                <p>Nhận thông tin khuyến mãi và kỹ thuật nuôi mới nhất.</p>
                <div class="input-group">
                    <input type="email" placeholder="Email của bạn">
                    <button>Gửi</button>
                </div>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; 2025 <?php echo isset($title) ? $title : $site_title; ?>. All rights reserved.</p>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>

</html>