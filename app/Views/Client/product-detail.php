<div class="breadcrumb container">
    <a href="/index">Trang chủ</a> <i class="fa-solid fa-angle-right"></i>
    <a href="#"><?php echo $product->category_name; ?></a> <i class="fa-solid fa-angle-right"></i>
    <span><?php echo $product->name; ?></span>
</div>

<section class="product-main container section-padding">
    <div class="product-main-grid">
        <div class="product-gallery">
            <div class="main-image">
                <div class="voucher-tag">Mới</div>
                <img src="/assets/uploads/products/<?php echo $product->main_image; ?>" alt="Main Product" id="mainImg">
            </div>
            <div class="thumb-list">

                <img src="/assets/uploads/products/<?php echo $product->main_image; ?>"
                    onclick="changeImage(this.src)">

                <?php if (!empty($gallery)): ?>
                    <?php foreach ($gallery as $img): ?>

                        <img src="/assets/uploads/products/<?php echo $img->image_url; ?>"
                            onclick="changeImage(this.src)">

                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>

        <div class="product-info">
            <div class="brand-sku">
                <span>Thương hiệu: <strong>Công ty TNHH Khoa Học Công Nghệ 3S</strong></span>
                <span class="sku">SKU: <?php echo $product->sku; ?></span>
            </div>
            <h1 class="product-title"><?php echo $product->name; ?></h1>

            <div class="price-box">

                <?php if ($product->sale_price > 0 && $product->sale_price < $product->price): ?>

                    <span class="current-price">
                        <?php echo number_format($product->sale_price, 0, ',', '.'); ?>đ
                    </span>

                    <span class="old">
                        <?php echo number_format($product->price, 0, ',', '.'); ?>đ
                    </span>

                <?php else: ?>

                    <span class="current-price">
                        <?php echo number_format($product->price, 0, ',', '.'); ?>đ
                    </span>

                <?php endif; ?>

                <!-- <span class="unit"></span> -->
            </div>

            <div class="short-desc">
                <p><strong>Công dụng chính:</strong></p>
                <ul>
                    <li>Phân hủy mùn bã hữu cơ, thức ăn thừa.</li>
                    <li>Giảm khí độc NH3, NO2 trong ao nuôi.</li>
                    <li>Ổn định màu nước, gây màu trà đẹp.</li>
                </ul>
            </div>

            <div class="purchase-controls">
                <form action="/cart/add" method="POST">
                    <div class="qty-control">
                        <label>Số lượng:</label>
                        <div class="input-group">
                            <button onclick="decreaseQty()">-</button>
                            <input type="number" id="qty" value="1" min="1">
                            <button onclick="increaseQty()">+</button>
                        </div>
                    </div>

                    <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">


                    <div class="btn-group">
                        <?php if ($product->stock <= 0): ?>
                            <p style="color:orange; font-size:12px; margin-top:5px;">
                                * Thời gian giao hàng có thể chậm hơn 1-2 ngày, xin quý khách hàng thông cảm.
                            </p>
                        <?php endif; ?>
                        <button type="submit" name="action" value="buy_now" class="btn-buy-now">
                            MUA NGAY <span>(Giao nhanh toàn quốc)</span>
                        </button>


                        <div class="btn-row-2">
                            <button type="submit" name="action" value="add_cart" class="btn-add-cart">
                                Thêm giỏ hàng
                            </button>

                            <a href="https://zalo.me/0123456789" target="_blank" class="btn-find-store" style="display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                Tư vấn Zalo
                            </a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

<section class="product-content-area container">
    <div class="content-grid">

        <div class="content-left">
            <div class="content-tabs">
                <?php if (!empty($product->uses)): ?>
                    <div class="tab-item active" onclick="openTab(event, 'tab-congdung')">Công dụng</div>
                <?php endif; ?>
                <?php if (!empty($product->ingredients)): ?>
                    <div class="tab-item" onclick="openTab(event, 'tab-thanhphan')">Thành phần</div>
                <?php endif; ?>
                <?php if (!empty($product->usage_instruction)): ?>
                    <div class="tab-item" onclick="openTab(event, 'tab-huongdan')">Hướng dẫn sử dụng</div>
                <?php endif; ?>
                <?php if (!empty($product->note)): ?>
                    <div class="tab-item" onclick="openTab(event, 'tab-luuy')">Lưu ý</div>
                <?php endif; ?>
            </div>

            <div class="content-body">

                <div id="tab-congdung" class="tab-content active">
                    <?php echo $product->uses; ?>
                </div>

                <div id="tab-thanhphan" class="tab-content">
                    <?php echo $product->ingredients; ?>
                </div>

                <div id="tab-huongdan" class="tab-content">
                    <?php echo $product->usage_instruction; ?>
                </div>

                <div id="tab-luuy" class="tab-content">
                    <?php echo $product->note; ?>
                </div>

            </div>
        </div>

        <div class="sidebar-right">
            <h3 class="sidebar-title">Sản phẩm cùng loại</h3>

            <div class="sidebar-list">
                <?php if (!empty($related_products)): ?>

                    <?php foreach ($related_products as $item): ?>

                        <?php
                        // 1. Xử lý ảnh (Nếu không có ảnh thì dùng ảnh placeholder 80x80)
                        $img_src = !empty($item->main_image)
                            ? '/assets/uploads/' . $item->main_image
                            : 'https://placehold.co/80x80?text=No+Image';
                        ?>

                        <a href="/product/detail/<?php echo $item->id; ?>" class="sidebar-item">

                            <img src="<?php echo $img_src; ?>" alt="<?php echo $item->name; ?>">

                            <div class="info">
                                <h4><?php echo $item->name; ?></h4>

                                <span class="price" style="color: #d0021b; font-weight: bold;">
                                    <?php
                                    if ($item->sale_price > 0 && $item->sale_price < $item->price) {
                                        echo number_format($item->sale_price, 0, ',', '.') . 'đ';
                                    } else {
                                        echo number_format($item->price, 0, ',', '.') . 'đ';
                                    }
                                    ?>
                                </span>
                            </div>
                        </a>

                    <?php endforeach; ?>

                <?php else: ?>
                    <p style="color: #888; padding: 10px;">Chưa có sản phẩm liên quan.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>
</section>

<script>
    // 1. Xử lý Ảnh & Số lượng
    function changeImage(src) {
        document.getElementById('mainImg').src = src;
    }

    function increaseQty() {
        var el = document.getElementById('qty');
        el.value = parseInt(el.value) + 1;
    }

    function decreaseQty() {
        var el = document.getElementById('qty');
        if (el.value > 1) el.value = parseInt(el.value) - 1;
    }

    // 2. Xử lý Tabs (Chuyển đổi nội dung)
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;

        // Ẩn tất cả nội dung tab
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
            tabcontent[i].classList.remove("active");
        }

        // Bỏ active ở tất cả các nút tab
        tablinks = document.getElementsByClassName("tab-item");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // Hiện tab được chọn và thêm class active cho nút đó
        document.getElementById(tabName).style.display = "block";
        // Thêm một chút delay để animation (nếu có) hoạt động mượt hơn
        setTimeout(() => {
            document.getElementById(tabName).classList.add("active");
        }, 10);

        evt.currentTarget.className += " active";
    }
</script>