<div class="content-body">

    <div class="page-header-row">
        <h2 class="page-title">Cấu hình cửa hàng</h2>
        <div class="actions">
            <button class="btn-action btn-green" onclick="document.getElementById('form-setting').submit()">
                <i class="fa-solid fa-floppy-disk"></i> Lưu cấu hình
            </button>
        </div>
    </div>

    <form id="form-setting" action="/admin/setting/save" method="POST" enctype="multipart/form-data" class="form-layout">

        <div class="form-col-main">

            <div class="data-card form-card">
                <h3 class="card-title">Thông tin chung cửa hàng</h3>

                <div class="form-group">
                    <label>Tên cửa hàng (Site Title)</label>
                    <input type="text" name="site_title" value="<?php echo $settings['site_title'] ?? ''; ?>"
                        placeholder="VD: CÔNG TY TNHH KHOA HỌC CÔNG NGHỆ 3S">
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label>Hotline liên hệ</label>
                        <input type="text" name="site_hotline" value="<?php echo $settings['site_hotline'] ?? ''; ?>" placeholder="0912...">
                    </div>
                    <div class="form-group">
                        <label>Email hỗ trợ</label>
                        <input type="text" name="site_email" value="<?php echo $settings['site_email'] ?? ''; ?>" placeholder="admin@example.com">
                    </div>
                </div>

                <div class="form-group">
                    <label>Địa chỉ văn phòng</label>
                    <textarea name="site_address" rows="2" placeholder="Số 123, Đường ABC..."><?php echo $settings['site_address'] ?? ''; ?></textarea>
                </div>
            </div>

            <div class="data-card form-card">
                <h3 class="card-title">Tối ưu SEO (Google)</h3>
                <p style="font-size: 13px; color: #666; margin-bottom: 15px;">Thiết lập tiêu đề và mô tả thẻ meta giúp khách hàng tìm thấy cửa hàng của bạn trên Google.</p>

                <div class="form-group">
                    <div style="display:flex; justify-content:space-between;">
                        <label>Tiêu đề trang</label>
                        <span style="font-size:12px; color:#999;" id="count-title">0/70 ký tự</span>
                    </div>
                    <input type="text" name="seo_title" id="input-title"
                        value="<?php echo $settings['seo_title'] ?? ''; ?>"
                        placeholder="VD: Thuốc Thủy Sản Giá Tốt Nhất Việt Nam" onkeyup="countChar(this, 70, 'count-title')">
                </div>

                <div class="form-group">
                    <div style="display:flex; justify-content:space-between;">
                        <label>Mô tả trang (Meta Description)</label>
                        <span style="font-size:12px; color:#999;" id="count-desc">0/320 ký tự</span>
                    </div>
                    <textarea name="seo_description" id="input-desc" rows="4"
                        placeholder="Mô tả ngắn gọn về cửa hàng của bạn để hiển thị trên kết quả tìm kiếm..."
                        onkeyup="countChar(this, 320, 'count-desc')"><?php echo $settings['seo_description'] ?? ''; ?></textarea>
                </div>
            </div>

        </div>

        <div class="form-col-side">

            <div class="data-card form-card">
                <h3 class="card-title">Logo & Icon</h3>

                <div class="main-image-upload">
                    <div class="image-upload-box main-box" onclick="document.getElementById('logoInput').click()"
                        style="padding: 20px; background: #f9f9f9; text-align: center; border: 2px dashed #ddd; cursor: pointer;">

                        <?php
                        $logoSrc = !empty($settings['site_logo']) ? '/' . $settings['site_logo'] : '';
                        ?>

                        <img id="previewLogo" src="<?php echo $logoSrc; ?>?v=<?php echo time(); ?>"
                            style="max-width: 100%; max-height: 100px; <?php echo empty($logoSrc) ? 'display:none;' : ''; ?>">

                        <div id="placeholderLogo" style="<?php echo !empty($logoSrc) ? 'display:none;' : ''; ?>">
                            <i class="fa-regular fa-image" style="font-size: 30px; color: #ccc;"></i>
                            <p style="margin: 5px 0 0; font-size: 12px; color: #666;">Tải logo lên</p>
                        </div>

                        <input type="file" name="site_logo" id="logoInput" hidden onchange="previewImage(this, 'previewLogo', 'placeholderLogo')">
                    </div>
                </div>
            </div>

            <div class="data-card form-card">
                <h3 class="card-title">Mạng xã hội</h3>

                <div class="form-group">
                    <label><i class="fa-brands fa-facebook" style="color: #1877f2;"></i> Fanpage Facebook</label>
                    <input type="text" name="social_facebook" value="<?php echo $settings['social_facebook'] ?? ''; ?>" placeholder="https://facebook.com/...">
                </div>

                <div class="form-group">
                    <label><i class="fa-solid fa-comment-dots" style="color: #0068ff;"></i> Zalo OA / Cá nhân</label>
                    <input type="text" name="social_zalo" value="<?php echo $settings['social_zalo'] ?? ''; ?>" placeholder="https://zalo.me/...">
                </div>

                <div class="form-group">
                    <label><i class="fa-brands fa-tiktok" style="color: #000;"></i> Tiktok</label>
                    <input type="text" name="social_tiktok" value="<?php echo $settings['social_tiktok'] ?? ''; ?>" placeholder="Link kênh Tiktok...">
                </div>
            </div>

            <!-- <div class="data-card form-card">
                <h3 class="card-title">Google Analytics / Ads</h3>
                <div class="form-group">
                    <label>Google Analytics ID</label>
                    <input type="text" name="google_analytics" value="<?php echo $settings['google_analytics'] ?? ''; ?>" placeholder="G-XXXXXXXXXX">
                </div>
            </div> -->
            <div class="data-card form-card" style="background: #e3f2fd; border: 1px solid #90caf9;">
                <h3 class="card-title" style="color: #0d47a1;">
                    <i class="fa-solid fa-print"></i> Cấu hình in ấn
                </h3>

                <p style="font-size: 13px; color: #555; margin-bottom: 15px; line-height: 1.5;">
                    Thiết kế và quản lý các mẫu in hóa đơn (A4, A5, K80) cho đơn hàng.
                </p>

                <a href="/admin/template" class="btn-action"
                    style="display: block; text-align: center; background: #fff; color: #007bff; border: 1px solid #007bff; text-decoration: none; border-radius: 4px; padding: 10px; font-weight: bold; transition: 0.3s;">
                    <i class="fa-solid fa-gear"></i> Quản lý mẫu in
                </a>
            </div>

        </div>
</div>


</form>
</div>

<script>
    // 1. Hàm xem trước ảnh
    function previewImage(input, imgId, placeholderId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.getElementById(imgId);
                var ph = document.getElementById(placeholderId);
                img.src = e.target.result;
                img.style.display = 'block';
                if (ph) ph.style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // 2. Hàm đếm ký tự (Giống Haravan)
    function countChar(input, max, displayId) {
        var len = input.value.length;
        document.getElementById(displayId).innerText = len + '/' + max + ' ký tự';

        if (len > max) {
            document.getElementById(displayId).style.color = 'red';
        } else {
            document.getElementById(displayId).style.color = '#999';
        }
    }

    // Chạy đếm ký tự lúc mới vào trang (để hiện số ký tự cũ)
    window.onload = function() {
        countChar(document.getElementById('input-title'), 70, 'count-title');
        countChar(document.getElementById('input-desc'), 320, 'count-desc');
    };
</script>