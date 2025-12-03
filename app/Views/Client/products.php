<div class="breadcrumb container">
    <a href="/">Trang chủ</a> <i class="fa-solid fa-angle-right"></i>
    <span>Tất cả sản phẩm</span>
</div>

<section class="shop-page container section-padding">

    <div class="mobile-filter-bar">
        <button class="btn-open-filter"><i class="fa-solid fa-filter"></i> Bộ lọc sản phẩm</button>
        <select class="mobile-sort" onchange="window.location.href=this.value">
            <option value="?sort=newest">Mới nhất</option>
            <option value="?sort=price_asc">Giá tăng dần</option>
            <option value="?sort=price_desc">Giá giảm dần</option>
        </select>
    </div>

    <div class="shop-layout">

        <aside class="shop-sidebar">
            <div class="sidebar-header-mobile">
                <span>Lọc sản phẩm</span>
                <i class="fa-solid fa-xmark btn-close-filter"></i>
            </div>

            <div class="filter-widget">
                <h3 class="widget-title">Danh mục sản phẩm</h3>
                <ul class="filter-list">

                    <?php
                    $currentCat = isset($_GET['cat']) ? $_GET['cat'] : '';
                    $isActiveAll = ($currentCat == '') ? 'active' : '';
                    ?>
                    <li>
                        <a href="/product" class="filter-link <?php echo $isActiveAll; ?>">
                            <?php if ($isActiveAll): ?>
                                <i class="fa-solid fa-circle-check" style="color: #28a745;"></i>
                            <?php else: ?>
                                <i class="fa-regular fa-circle"></i>
                            <?php endif; ?>
                            Tất cả
                        </a>
                    </li>

                    <?php
                    if (!empty($categories)):
                        foreach ($categories as $cat):

                            // --- LOGIC LỌC: CHỈ LẤY CON CỦA ID = 1 ---

                            // 1. Bỏ qua danh mục Tin tức (ID=6)
                            if ($cat->id == 6 || $cat->parent_id == 6) continue;

                            // 2. CHỈ LẤY CON CỦA "THUỐC THỦY SẢN" (ID = 1)
                            // Nếu parent_id KHÁC 1 thì bỏ qua (nghĩa là bỏ qua Cha, và bỏ qua con của ông khác)
                            if ($cat->parent_id != 1) continue;

                            // Kiểm tra Active
                            $isActive = ($currentCat == $cat->id) ? 'active' : '';
                    ?>
                            <li>
                                <a href="/product?cat=<?php echo $cat->id; ?>" class="filter-link <?php echo $isActive; ?>">
                                    <?php if ($isActive): ?>
                                        <i class="fa-solid fa-circle-check" style="color: #28a745;"></i>
                                    <?php else: ?>
                                        <i class="fa-regular fa-circle"></i>
                                    <?php endif; ?>

                                    <?php echo $cat->name; ?>
                                </a>
                            </li>

                    <?php endforeach;
                    endif; ?>

                </ul>
            </div>

            <div class="filter-widget">
                <h3 class="widget-title">Khoảng giá</h3>

                <form action="/product" method="GET" class="price-filter">

                    <?php if (isset($_GET['cat'])): ?>
                        <input type="hidden" name="cat" value="<?php echo $_GET['cat']; ?>">
                    <?php endif; ?>

                    <?php if (isset($_GET['sort'])): ?>
                        <input type="hidden" name="sort" value="<?php echo $_GET['sort']; ?>">
                    <?php endif; ?>

                    <div class="price-inputs">
                        <input type="number" name="min_price" placeholder="0"
                            value="<?php echo isset($_GET['min_price']) ? $_GET['min_price'] : ''; ?>">
                        <span>-</span>
                        <input type="number" name="max_price" placeholder="Max"
                            value="<?php echo isset($_GET['max_price']) ? $_GET['max_price'] : ''; ?>">
                    </div>

                    <button type="submit" class="btn-filter-price">Áp dụng</button>
                </form>
            </div>
        </aside>

        <main class="shop-content">

            <div class="shop-toolbar">
                <span class="result-count">Hiển thị <strong><?php echo count($products); ?></strong> sản phẩm</span>
                <div class="sort-dropdown">
                    <label>Sắp xếp theo:</label>
                    <select onchange="window.location.href=this.value">
                        <option value="?sort=newest">Mới nhất</option>
                        <option value="?sort=price_asc">Giá: Thấp đến Cao</option>
                        <option value="?sort=price_desc">Giá: Cao đến Thấp</option>
                    </select>
                </div>
            </div>

            <div class="product-grid shop-grid">

                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $item): ?>

                        <article class="product-card">

                            <?php
                            // 1. Ưu tiên hiển thị SALE (Giảm giá %)
                            if ($item->sale_price > 0 && $item->sale_price < $item->price) {
                                $percent = round((($item->price - $item->sale_price) / $item->price) * 100);
                                // Badge màu đỏ cho giảm giá
                                echo '<div class="badge" style="background-color: #d0021b;">-' . $percent . '%</div>';
                            }

                            // 2. Nếu không sale -> Kiểm tra KHUYẾN MÃI MUA TẶNG
                            elseif (isset($item->promo_type) && $item->promo_type != 'none') {
                                // Mua X Tặng Y (Cùng loại) - Màu cam
                                if ($item->promo_type == 'same') {
                                    echo '<div class="badge" style="background-color: #ff9800; font-size: 11px; padding: 3px 5px;">
                        Mua ' . $item->promo_buy . ' Tặng ' . $item->promo_get . '
                      </div>';
                                }
                                // Tặng Quà Khác - Màu hồng đậm
                                elseif ($item->promo_type == 'gift') {
                                    echo '<div class="badge" style="background-color: #e91e63; font-size: 11px; padding: 3px 5px;">
                        <i class="fa-solid fa-gift"></i> Tặng Quà
                      </div>';
                                }
                            }

                            // 3. Nếu không KM -> Kiểm tra HOT
                            elseif (isset($item->is_featured) && $item->is_featured == 1) {
                                echo '<div class="badge" style="background-color: #ff5722;">Hot</div>';
                            }

                            // 4. Cuối cùng -> Kiểm tra NEW
                            elseif (isset($item->is_new) && $item->is_new == 1) {
                                echo '<div class="badge" style="background-color: #28a745;">New</div>';
                            }
                            ?>
                            <div class="img-box" style="height: 200px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #fff; border-bottom: 1px solid #eee;">
                                <?php
                                $imgSrc = !empty($item->main_image) ? '/assets/uploads/products/' . $item->main_image : 'https://placehold.co/200x200?text=No+Img';
                                ?>
                                <a href="/product/detail/<?php echo $item->id; ?>" style="display: block; width: 100%; height: 100%;">
                                    <img src="<?php echo $imgSrc; ?>" alt="<?php echo $item->name; ?>" style="width: 100%; height: 100%; object-fit: contain;">
                                </a>
                            </div>

                            <div class="info">
                                <h3>
                                    <a href="/product/detail/<?php echo $item->id; ?>" style="text-decoration:none; color:inherit;">
                                        <?php echo $item->name; ?>
                                    </a>
                                </h3>

                                <div class="price">
                                    <?php if ($item->sale_price > 0 && $item->sale_price < $item->price): ?>
                                        <span class="current"><?php echo number_format($item->sale_price, 0, ',', '.'); ?>đ</span>
                                        <span class="old" style="text-decoration:line-through; color:#999; font-size:13px; margin-left:5px;">
                                            <?php echo number_format($item->price, 0, ',', '.'); ?>đ
                                        </span>
                                    <?php else: ?>
                                        <span class="current"><?php echo number_format($item->price, 0, ',', '.'); ?>đ</span>
                                    <?php endif; ?>
                                </div>

                                <form action="/cart/add" method="POST" style="display:inline;">
                                    <div class="card-actions">
                                        <a href="/product/detail/<?php echo $item->id; ?>" class="btn-action btn-view" style="text-decoration:none; line-height:normal; display:inline-block; text-align:center;">
                                            Xem
                                        </a>

                                        <input type="hidden" name="product_id" value="<?php echo $item->id; ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn-action btn-cart-add">
                                            <i class="fa-solid fa-cart-plus"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </article>

                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align:center; width:100%; padding:30px; color:#999;">
                        <i class="fa-solid fa-box-open" style="font-size:40px;"></i><br>
                        Không tìm thấy sản phẩm nào.
                    </p>
                <?php endif; ?>

            </div>

            <!-- <div class="pagination">
                <a href="#" class="page-link prev"><i class="fa-solid fa-angle-left"></i></a>
                <a href="#" class="page-link active">1</a>
                <a href="#" class="page-link">2</a>
                <a href="#" class="page-link next"><i class="fa-solid fa-angle-right"></i></a>
            </div> -->

        </main>
    </div>
</section>