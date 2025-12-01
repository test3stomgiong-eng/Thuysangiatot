<section class="hero-banner container section-padding">
    <div class="banner-grid">

        <div class="banner-main">
            <?php
            // Kiểm tra nếu có link trong DB thì lấy, không thì để #
            $linkMain = !empty($banner_main_link) ? $banner_main_link : '#';

            // Kiểm tra nếu có ảnh upload thì lấy, không thì dùng ảnh mẫu (Placeholder)
            $srcMain = !empty($banner_main_img)
                ? '/assets/uploads/banners/' . $banner_main_img
                : 'https://placehold.co/800x400/008a33/ffffff?text=Khuyen+Mai+Lon+Mung+Vu+Mua';
            ?>

            <a href="<?php echo $linkMain; ?>">
                <img src="<?php echo $srcMain; ?>" alt="Khuyến mãi chính">
            </a>

            <div class="slider-nav">
                <button class="prev"><i class="fa-solid fa-chevron-left"></i></button>
                <button class="next"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>

        <div class="banner-side">

            <?php
            $linkSub1 = !empty($banner_sub1_link) ? $banner_sub1_link : '#';
            $srcSub1  = !empty($banner_sub1_img)
                ? '/assets/uploads/banners/' . $banner_sub1_img
                : 'https://placehold.co/400x190/f39c12/ffffff?text=Deal+Soc+Trong+Ngay';
            $altSub1  = !empty($banner_sub1_title) ? $banner_sub1_title : 'Deal Sốc Trong Ngày';
            ?>
            <a href="<?php echo $linkSub1; ?>" class="banner-item">
                <img src="<?php echo $srcSub1; ?>" alt="<?php echo $altSub1; ?>">
            </a>

            <?php
            $linkSub2 = !empty($banner_sub2_link) ? $banner_sub2_link : '#';
            $srcSub2  = !empty($banner_sub2_img)
                ? '/assets/uploads/banners/' . $banner_sub2_img
                : 'https://placehold.co/400x190/2980b9/ffffff?text=Combo+Tiet+Kiem';
            $altSub2  = !empty($banner_sub2_title) ? $banner_sub2_title : 'Combo Tiết Kiệm';
            ?>
            <a href="<?php echo $linkSub2; ?>" class="banner-item">
                <img src="<?php echo $srcSub2; ?>" alt="<?php echo $altSub2; ?>">
            </a>

        </div>
    </div>
</section>

<section class="mobile-quick-menu container">
    <div class="quick-menu-grid">
        <a href="#" class="quick-item">
            <i class="fa-solid fa-user-doctor"></i>
            <span>Dược sĩ tư vấn</span>
        </a>
        <a href="#" class="quick-item">
            <i class="fa-solid fa-file-prescription"></i>
            <span>Mua thuốc theo đơn</span>
        </a>
        <a href="#" class="quick-item">
            <i class="fa-solid fa-house-medical"></i>
            <span>Hệ thống nhà thuốc</span>
        </a>
        <a href="#" class="quick-item">
            <i class="fa-solid fa-hand-holding-medical"></i>
            <span>Sức khỏe tổng quát</span>
        </a>
    </div>
</section>

<section class="categories container section-padding">
    <h2 class="section-title" style="margin-bottom: 20px; font-size: 20px; font-weight: 700; color: #333;">
        DANH MỤC SẢN PHẨM
    </h2>

    <div class="category-grid">
        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $cat): ?>

                <?php
                // 1. LỌC BỎ TIN TỨC (Giữ nguyên)
                if (isset($cat->type) && $cat->type == 'news') continue;
                if ($cat->id == 6 || $cat->parent_id == 6) continue;

                // 2. LOGIC QUAN TRỌNG NHẤT (SỬA Ở ĐÂY) 👇

                // CŨ (Sai ý bạn): Chỉ lấy Cha (Gốc)
                // if ($cat->parent_id == 0) ...

                // MỚI (Đúng ý bạn): Chỉ lấy CON (Có cha)
                // Điều kiện: parent_id LỚN HƠN 0
                if ($cat->parent_id == 0) continue;
                ?>

                <a href="/product?cat=<?php echo $cat->id; ?>" style="text-decoration: none; color: inherit;">
                    <div class="cat-item">
                        <div class="icon-box">
                            <?php if (!empty($cat->image)): ?>
                                <img src="/assets/uploads/categories/<?php echo $cat->image; ?>"
                                    alt="<?php echo $cat->name; ?>"
                                    style="width: 40px; height: 40px; object-fit: contain;">
                            <?php elseif (!empty($cat->icon_class)): ?>
                                <i class="<?php echo $cat->icon_class; ?>"></i>
                            <?php else: ?>
                                <i class="fa-solid fa-layer-group"></i>
                            <?php endif; ?>
                        </div>
                        <p><?php echo $cat->name; ?></p>
                    </div>
                </a>

            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<section class="products container section-padding bg-light">
    <div class="section-header">
        <h2 class="section-title text-orange"><i class="fa-solid fa-bolt"></i> DEAL SIÊU KHỦNG</h2>
        <a href="#" class="view-all">Xem tất cả <i class="fa-solid fa-caret-right"></i></a>
    </div>

    <div class="product-grid">
        <article class="product-card">
            <div class="badge">-15%</div>
            <div class="img-box"><img src="https://placehold.co/200x200?text=Khoang+Tat" alt="SP"></div>
            <div class="info">
                <h3>Khoáng tạt nguyên liệu (Bao 25kg)</h3>
                <div class="price">
                    <span class="current">450.000đ</span>
                    <span class="old">530.000đ</span>
                </div>
                <div class="card-actions">
                    <a href="product-detail.html" class="btn-action btn-view"><i class="fa-regular fa-eye"></i> Xem</a>
                    <button class="btn-action btn-cart-add"><i class="fa-solid fa-cart-plus"></i></button>
                </div>
            </div>
        </article>

        <article class="product-card">
            <div class="img-box"><img src="https://placehold.co/200x200?text=Yucca" alt="SP"></div>
            <div class="info">
                <h3>Yucca Hấp thụ khí độc cấp tốc</h3>
                <div class="price"><span class="current">180.000đ</span></div>
                <div class="card-actions">
                    <button class="btn-action btn-view"><i class="fa-regular fa-eye"></i> Xem</button>
                    <button class="btn-action btn-cart-add"><i class="fa-solid fa-cart-plus"></i></button>
                </div>
            </div>
        </article>

        <article class="product-card">
            <div class="badge">Tặng 1</div>
            <div class="img-box"><img src="https://placehold.co/200x200?text=Vit+C" alt="SP"></div>
            <div class="info">
                <h3>Vitamin C tạt ao (Lon 1kg)</h3>
                <div class="price"><span class="current">120.000đ</span></div>
                <div class="card-actions">
                    <button class="btn-action btn-view"><i class="fa-regular fa-eye"></i> Xem</button>
                    <button class="btn-action btn-cart-add"><i class="fa-solid fa-cart-plus"></i></button>
                </div>
            </div>
        </article>

        <article class="product-card">
            <div class="img-box"><img src="https://placehold.co/200x200?text=Men+Tieu+Hoa" alt="SP"></div>
            <div class="info">
                <h3>Men tiêu hóa đường ruột tôm</h3>
                <div class="price"><span class="current">250.000đ</span></div>
                <div class="card-actions">
                    <button class="btn-action btn-view"><i class="fa-regular fa-eye"></i> Xem</button>
                    <button class="btn-action btn-cart-add"><i class="fa-solid fa-cart-plus"></i></button>
                </div>
            </div>
        </article>

        <article class="product-card">
            <div class="badge">Hot</div>
            <div class="img-box"><img src="https://placehold.co/200x200?text=Diet+Khuan" alt="SP"></div>
            <div class="info">
                <h3>Diệt khuẩn BKC 80% (Can 1L)</h3>
                <div class="price"><span class="current">165.000đ</span></div>
                <div class="card-actions">
                    <button class="btn-action btn-view"><i class="fa-regular fa-eye"></i> Xem</button>
                    <button class="btn-action btn-cart-add"><i class="fa-solid fa-cart-plus"></i></button>
                </div>
            </div>
        </article>
    </div>
</section>
<?php if (!empty($new_products)): ?>
    <section class="products container section-padding">
        <div class="section-header">
            <h2 class="section-title" style="color: var(--primary-color);"><i class="fa-solid fa-star"></i> SẢN PHẨM MỚI</h2>
            <a href="#" class="view-all">Xem tất cả <i class="fa-solid fa-caret-right"></i></a>
        </div>
        <div class="product-grid">
            <?php foreach ($new_products as $item): ?>
                <?php
                // Đường dẫn ảnh mặc định
                $img_path = '/assets/uploads/products/' . $item->main_image;

                // Nếu DB không có tên ảnh thì dùng ảnh placeholder
                if (empty($item->main_image)) {
                    $img_path = '/assets/images/no-image.png';
                }
                ?>
                <article class="product-card">
                    <div class="img-box"><img src="<?php echo $img_path; ?>" alt="<?php echo $item->name; ?>"></div>
                    <div class="info">
                        <h3><?php echo $item->name; ?></h3>
                        <div class="price">
                            <?php if ($item->sale_price > 0 && $item->sale_price < $item->price): ?>

                                <span class="current">
                                    <?php echo number_format($item->sale_price, 0, ',', '.'); ?>đ
                                </span>

                                <span class="old">
                                    <?php echo number_format($item->price, 0, ',', '.'); ?>đ
                                </span>

                            <?php else: ?>

                                <span class="current">
                                    <?php echo number_format($item->price, 0, ',', '.'); ?>đ
                                </span>

                            <?php endif; ?>
                        </div>
                        <div class="card-actions">
                            <a href="/product/detail/<?php echo $item->id; ?>" class="btn-action btn-view">Xem</a>
                            <button class="btn-action btn-cart-add"><i class="fa-solid fa-cart-plus"></i></button>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
            <article class="product-card">
                <div class="img-box"><img src="https://placehold.co/200x200?text=Vi+Sinh" alt="SP"></div>
                <div class="info">
                    <h3>Vi sinh xử lý đáy (Gói 500g)</h3>
                    <div class="price"><span class="current">190.000đ</span></div>
                    <div class="card-actions">
                        <button class="btn-action btn-view">Xem</button>
                        <button class="btn-action btn-cart-add"><i class="fa-solid fa-cart-plus"></i></button>
                    </div>
                </div>
            </article>
            <article class="product-card">
                <div class="img-box"><img src="https://placehold.co/200x200?text=Tang+Trong" alt="SP"></div>
                <div class="info">
                    <h3>Thuốc tăng trọng tôm thẻ</h3>
                    <div class="price"><span class="current">410.000đ</span></div>
                    <div class="card-actions">
                        <button class="btn-action btn-view">Xem</button>
                        <button class="btn-action btn-cart-add"><i class="fa-solid fa-cart-plus"></i></button>
                    </div>
                </div>
            </article>
            <article class="product-card">
                <div class="img-box"><img src="https://placehold.co/200x200?text=EDTA" alt="SP"></div>
                <div class="info">
                    <h3>EDTA Khử phèn (Bao 5kg)</h3>
                    <div class="price"><span class="current">150.000đ</span></div>
                    <div class="card-actions">
                        <button class="btn-action btn-view">Xem</button>
                        <button class="btn-action btn-cart-add"><i class="fa-solid fa-cart-plus"></i></button>
                    </div>
                </div>
            </article>
            <article class="product-card">
                <div class="img-box"><img src="https://placehold.co/200x200?text=Soda" alt="SP"></div>
                <div class="info">
                    <h3>Soda lạnh tăng kiềm (Bao 25kg)</h3>
                    <div class="price"><span class="current">220.000đ</span></div>
                    <div class="card-actions">
                        <button class="btn-action btn-view">Xem</button>
                        <button class="btn-action btn-cart-add"><i class="fa-solid fa-cart-plus"></i></button>
                    </div>
                </div>
            </article>
        </div>

    </section>
<?php else: ?>
    <p style="text-align: center; color: red;">Chưa có sản phẩm nào được cập nhật.</p>
<?php endif; ?>

<section class="products container section-padding bg-light">
    <div class="section-header">
        <h2 class="section-title" style="color: #c0392b;"><i class="fa-solid fa-kit-medical"></i> KHÁNG SINH ĐẶC TRỊ</h2>
        <a href="#" class="view-all">Xem tất cả <i class="fa-solid fa-caret-right"></i></a>
    </div>
    <div class="product-grid">
        <article class="product-card">
            <div class="img-box"><img src="https://placehold.co/200x200?text=Doxy" alt="SP"></div>
            <div class="info">
                <h3>Kháng sinh Doxycycline</h3>
                <div class="price"><span class="current">1.200.000đ</span></div>
                <div class="card-actions">
                    <button class="btn-action btn-view">Xem</button>
                    <button class="btn-action btn-cart-add"><i class="fa-solid fa-cart-plus"></i></button>
                </div>
            </div>
        </article>
        <article class="product-card">
            <div class="img-box"><img src="https://placehold.co/200x200?text=Cefo" alt="SP"></div>
            <div class="info">
                <h3>Kháng sinh Cefotaxime</h3>
                <div class="price"><span class="current">1.800.000đ</span></div>
                <div class="card-actions">
                    <button class="btn-action btn-view">Xem</button>
                    <button class="btn-action btn-cart-add"><i class="fa-solid fa-cart-plus"></i></button>
                </div>
            </div>
        </article>
        <article class="product-card">
            <div class="img-box"><img src="https://placehold.co/200x200?text=Flor" alt="SP"></div>
            <div class="info">
                <h3>Florfenicol (Chai 1L)</h3>
                <div class="price"><span class="current">650.000đ</span></div>
                <div class="card-actions">
                    <button class="btn-action btn-view">Xem</button>
                    <button class="btn-action btn-cart-add"><i class="fa-solid fa-cart-plus"></i></button>
                </div>
            </div>
        </article>
        <article class="product-card">
            <div class="img-box"><img src="https://placehold.co/200x200?text=Tri+Gan" alt="SP"></div>
            <div class="info">
                <h3>Đặc trị sưng gan, vàng gan</h3>
                <div class="price"><span class="current">280.000đ</span></div>
                <div class="card-actions">
                    <button class="btn-action btn-view">Xem</button>
                    <button class="btn-action btn-cart-add"><i class="fa-solid fa-cart-plus"></i></button>
                </div>
            </div>
        </article>
        <article class="product-card">
            <div class="img-box"><img src="https://placehold.co/200x200?text=Tri+Ruot" alt="SP"></div>
            <div class="info">
                <h3>Đặc trị phân trắng (Gói 100g)</h3>
                <div class="price"><span class="current">95.000đ</span></div>
                <div class="card-actions">
                    <button class="btn-action btn-view">Xem</button>
                    <button class="btn-action btn-cart-add"><i class="fa-solid fa-cart-plus"></i></button>
                </div>
            </div>
        </article>
    </div>
</section>

<section class="news container section-padding" style="margin: 50px auto;">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2 class="section-title" style="margin:0; text-transform:uppercase; font-size:24px;">Thông tin kỹ thuật - Dịch bệnh</h2>
        <a href="/news" style="color:#28a745; font-weight:bold; text-decoration:none;">Xem tất cả &rarr;</a>
    </div>

    <div class="news-layout">

        <?php if (!empty($latest_news)): ?>

            <?php
            $bigNews = $latest_news[0];
            $imgBig = !empty($bigNews->thumbnail) ? '/assets/uploads/news/' . $bigNews->thumbnail : 'https://placehold.co/600x350?text=No+Image';
            ?>
            <article class="news-big">
                <a href="/news/detail/<?php echo $bigNews->id; ?>">
                    <img src="<?php echo $imgBig; ?>" alt="<?php echo $bigNews->title; ?>">
                </a>
                <h3 style="margin: 15px 0 10px; font-size: 20px;">
                    <a href="/news/detail/<?php echo $bigNews->id; ?>" style="text-decoration:none; color:#333; font-weight:bold;">
                        <?php echo $bigNews->title; ?>
                    </a>
                </h3>
                <p style="color:#666; line-height:1.5; margin:0;">
                    <?php echo (strlen($bigNews->summary) > 150) ? substr($bigNews->summary, 0, 150) . '...' : $bigNews->summary; ?>
                </p>
            </article>

            <div class="news-list">
                <?php
                // Lấy 3 bài tiếp theo
                $smallList = array_slice($latest_news, 1, 3);
                ?>

                <?php foreach ($smallList as $item): ?>
                    <?php
                    $imgSmall = !empty($item->thumbnail) ? '/assets/uploads/news/' . $item->thumbnail : 'https://placehold.co/150x100?text=No+Image';
                    ?>
                    <article class="news-small">
                        <a href="/news/detail/<?php echo $item->id; ?>">
                            <img src="<?php echo $imgSmall; ?>" alt="<?php echo $item->title; ?>">
                        </a>
                        <div class="news-text">
                            <h4 style="margin:0 0 5px; font-size:16px; line-height:1.4;">
                                <a href="/news/detail/<?php echo $item->id; ?>" style="text-decoration:none; color:#333;">
                                    <?php echo $item->title; ?>
                                </a>
                            </h4>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <p>Chưa có tin tức nào.</p>
        <?php endif; ?>

    </div>
</section>