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
            <?php if (isset($_SESSION['customer_user'])): ?>

                <li class="mobile-user-group">
                    <a href="javascript:void(0)" class="menu-toggle" onclick="toggleMobileSubmenu(this)">
                        <i class="fa-solid fa-circle-user"></i>
                        <span>Chào, <?php echo $_SESSION['customer_user']['fullname']; ?></span>
                        <i class="fa-solid fa-chevron-down arrow-icon" style="float: right; font-size: 12px; margin-top: 4px;"></i>
                    </a>

                    <ul class="mobile-sub-menu" style="display: none; background-color: #f9f9f9;">

                        <?php
                        // Kiểm tra nếu có quyền role = 1 (Admin)
                        if (isset($_SESSION['customer_user']['role']) && $_SESSION['customer_user']['role'] == 'admin'):
                        ?>
                            <li>
                                <a href="/admin/dashboard" style="color: #d63031 !important; padding-left: 40px; font-weight: bold;">
                                    <i class="fa-solid fa-user-gear"></i> Trang quản trị
                                </a>
                            </li>
                        <?php endif; ?>

                        <li>
                            <a href="/profile" style="padding-left: 40px;">
                                <i class="fa-solid fa-id-card"></i> Tài khoản
                            </a>
                        </li>
                        <li>
                            <a href="/cart" style="padding-left: 40px;">
                                <i class="fa-solid fa-box"></i> Đơn hàng
                            </a>
                        </li>
                        <li>
                            <a href="/auth/logout" style="padding-left: 40px; color: #dc3545;">
                                <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                            </a>
                        </li>
                    </ul>
                </li>

            <?php else: ?>

                <li>
                    <a href="/auth/login">
                        <i class="fa-solid fa-user"></i> Đăng nhập / Đăng ký
                    </a>
                </li>

            <?php endif; ?>
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

                <!-- Giỏ hàng -->
                <!-- <div class="header-actions">
                    <a href="/cart" class="btn-cart">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span>Giỏ hàng</span>
                    </a>
                    <a href="/login" class="btn-login">
                        <i class="fa-regular fa-circle-user"></i>
                        <span>Đăng nhập</span>
                    </a>
                </div> -->

                <div class="header-user">

                    <a href="/cart" class="btn-action btn-cart">
                        <div class="icon-wrapper">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <?php
                            // Logic đếm giỏ hàng
                            $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                            if ($cartCount > 0):
                            ?>
                                <span class="cart-badge"><?php echo $cartCount; ?></span>
                            <?php endif; ?>
                        </div>
                        <span>Giỏ hàng</span>
                    </a>

                    <?php if (isset($_SESSION['customer_user'])): ?>

                        <div class="user-dropdown">
                            <div class="user-trigger">
                                <span class="welcome-text">Xin chào, <b><?php echo $_SESSION['customer_user']['fullname']; ?></b></span>
                                <i class="fa-solid fa-caret-down"></i>
                            </div>

                            <div class="dropdown-menu">
                                <ul class="dropdown-content">

                                    <?php
                                    // Kiểm tra nếu có quyền role = 1 (Admin)
                                    if (isset($_SESSION['customer_user']['role']) && $_SESSION['customer_user']['role'] == 'admin'):
                                    ?>
                                        <li>
                                            <a href="/admin/dashboard" style="color: #d63031 !important; font-weight: bold;">
                                                <i class="fa-solid fa-user-gear"></i> Trang quản trị
                                            </a>
                                        </li>
                                        <li class="divider"></li> <?php endif; ?>
                                    <li><a href="/account/profile"><i class="fa-solid fa-user"></i> Quản lý tài khoản</a></li>
                                    <li><a href="/account/orders"><i class="fa-solid fa-box-open"></i> Đơn hàng</a></li>
                                    <li class="divider"></li>
                                    <li><a href="/auth/logout" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a></li>
                                </ul>
                            </div>
                        </div>

                    <?php else: ?>

                        <a href="/auth/login" class="btn-action btn-login">
                            <i class="fa-regular fa-circle-user"></i>
                            <span>Đăng nhập</span>
                        </a>

                    <?php endif; ?>

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
        <span> <?php
                if (isset($_SESSION['customer_user'])) {
                    // Đã đăng nhập: Hiện tên
                    echo 'Xin chào, <b>' . $_SESSION['customer_user']['fullname'] . '!</b>';
                } else {
                    // Chưa đăng nhập: Hiện chào khách
                    echo 'Xin chào, Khách!';
                }
                ?></span>

        <?php if (isset($_SESSION['customer_user'])): ?>

            <a href="/auth/logout" style="color: #dc3545;"> Đăng xuất <i class="fa-solid fa-right-from-bracket"></i>
            </a>

        <?php else: ?>

            <a href="/auth/login">
                Đăng nhập <i class="fa-solid fa-chevron-right"></i>
            </a>

        <?php endif; ?>
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

    <script src="/assets/client/js/main.js"></script>
    <script>
        function toggleMobileSubmenu(element) {
            // 1. Tìm menu con ngay bên cạnh thẻ a vừa click
            var submenu = element.nextElementSibling;

            // 2. Toggle hiển thị
            if (submenu.style.display === "none" || submenu.style.display === "") {
                submenu.style.display = "block";
                element.classList.add("active"); // Để xoay mũi tên
            } else {
                submenu.style.display = "none";
                element.classList.remove("active");
            }
        }
    </script>
</body>

</html>