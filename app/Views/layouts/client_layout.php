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
            <li><a href="/"><i class="fa-solid fa-house"></i> Trang chủ</a></li>
            <li><a href="/product"><i class="fa-solid fa-capsules"></i> Sản phẩm</a></li>
            <li><a href="/news"><i class="fa-solid fa-newspaper"></i> Tin tức & Sự kiện</a></li>
            <li><a href="#"><i class="fa-solid fa-phone"></i> Liên hệ</a></li>
            <li class="divider"></li>
            <li><a href="/cart"><i class="fa-solid fa-cart-shopping"></i> Giỏ hàng</a></li>
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

                <a href="/" class="logo-wrapper">
                    <div class="logo-icon">
                        <i class="fa-solid fa-layers-group"></i>
                    </div>
                    <div class="logo-text">
                        <span class="brand">Thuỷ Sản Giá Tốt</span>
                        <span class="slogan">Chất lượng cao - Niềm tin lớn</span>
                    </div>
                </a>

                <div class="search-container">
                    <form action="/product" method="GET" class="search-box-wrapper">
                        <input type="text" name="keyword"
                            placeholder="Tìm tên thuốc, bệnh lý..."
                            value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : ''; ?>"
                            required>
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

                    <a href="/cart" class=" btn-cart">
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

                        <a href="/auth/login" class="btn-login">
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
                    <li class="menu-item active"><a href="/">Trang chủ</a></li>

                    <li class="menu-item has-child">
                        <a href="#">Sản phẩm <i class="fa-solid fa-angle-down"></i></a>

                        <ul class="sub-menu">

                            <?php if (!empty($menu_categories)): ?>

                                <li>
                                    <a href="/product" style="font-weight: bold; color: #007bff;">
                                        Tất cả sản phẩm
                                    </a>
                                </li>

                                <?php foreach ($menu_categories as $cat): ?>
                                    <li>
                                        <a href="/product?cat=<?php echo $cat->id; ?>">
                                            <?php echo $cat->name; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>

                            <?php endif; ?>

                        </ul>
                    </li>


                    <li class="menu-item has-child">
                        <a href="#">Kiến thức nuôi tôm <i class="fa-solid fa-angle-down"></i></a>

                        <ul class="sub-menu">
                            <li><a href="/news" style="font-weight:bold; color:#007bff;">Tất cả bài viết</a></li>

                            <?php if (!empty($news_menu)): ?>
                                <?php foreach ($news_menu as $item): ?>
                                    <li>
                                        <a href="/news?cat=<?php echo $item->id; ?>">
                                            <?php echo $item->name; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </li>

                    <li class="menu-item"><a href="/about">Liên hệ</a></li>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if (isset($_SESSION['flash_success'])): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thêm vào giỏ thành công!',
                text: '<?php echo $_SESSION['flash_success']; ?>',

                // Cấu hình hiển thị giữa màn hình
                position: 'center',
                showConfirmButton: true,
                showCancelButton: true,

                // Nút bấm
                confirmButtonColor: '#28a745', // Màu xanh (Vào giỏ)
                cancelButtonColor: '#6c757d', // Màu xám (Mua tiếp)
                confirmButtonText: '<i class="fa-solid fa-cart-shopping"></i> Xem giỏ hàng',
                cancelButtonText: 'Mua tiếp'

            }).then((result) => {
                // Nếu khách bấm "Xem giỏ hàng" thì chuyển trang
                if (result.isConfirmed) {
                    window.location.href = '/cart';
                }
            });
        </script>

        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <script>
        // 1. GHI NHỚ VỊ TRÍ KHI BẤM NÚT "THÊM VÀO GIỎ"
        // Tìm tất cả các form có hành động thêm giỏ hàng
        const cartForms = document.querySelectorAll('form[action="/cart/add"]');

        cartForms.forEach(form => {
            form.addEventListener('submit', function() {
                // Lưu vị trí dọc (Y) hiện tại vào bộ nhớ trình duyệt
                localStorage.setItem('scrollPosition', window.scrollY);
            });
        });

        // 2. KHÔI PHỤC VỊ TRÍ SAU KHI LOAD LẠI TRANG
        window.addEventListener('load', function() {
            // Kiểm tra xem có vị trí đã lưu không
            const scrollPos = localStorage.getItem('scrollPosition');

            if (scrollPos) {
                // Cuộn ngay lập tức xuống vị trí cũ
                window.scrollTo(0, parseInt(scrollPos));

                // Xóa bộ nhớ để không ảnh hưởng các trang khác
                localStorage.removeItem('scrollPosition');
            }
        });
    </script>

    <script>
        const filterBtn = document.querySelector('.btn-open-filter');
        const sidebar = document.querySelector('.shop-sidebar');
        const closeFilter = document.querySelector('.btn-close-filter');
        const overlay = document.querySelector('.mobile-nav-overlay'); // Tận dụng overlay cũ

        if (filterBtn) {
            filterBtn.addEventListener('click', () => {
                sidebar.classList.add('active');
                overlay.classList.add('active');
            });
        }
        if (closeFilter) {
            closeFilter.addEventListener('click', () => {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });
        }
        // Bấm ra ngoài đóng luôn bộ lọc
        if (overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('active');
            });
        }
    </script>

</body>

</html>