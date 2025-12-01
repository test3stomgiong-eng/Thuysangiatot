<div class="content-body">
    <div class="page-header-row">
        <div>
            <h2 class="page-title">Cấu hình Banner</h2>
            <p style="color: #888; font-size: 13px; margin: 0;">Quản lý hình ảnh quảng cáo ngoài trang chủ</p>
        </div>
        <button class="btn-action btn-green" onclick="document.getElementById('form-banner').submit()">
            <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
        </button>
    </div>

    <form id="form-banner" action="/admin/setting/saveBanner" method="POST" enctype="multipart/form-data">

        <div class="banner-layout-wrapper">

            <div class="col-main">
                <div class="card-box">
                    <div class="card-header">
                        <h3><i class="fa-regular fa-image"></i> Banner Chính (Slider)</h3>
                        <small>Kích thước khuyên dùng: 800x400px</small>
                    </div>

                    <div class="upload-zone main-zone" onclick="document.getElementById('mainInput').click()">
                        <?php
                        $imgMain = !empty($settings['banner_main_img']) ? '/assets/uploads/banners/' . $settings['banner_main_img'] : '';
                        $hasMain = !empty($imgMain);
                        ?>

                        <img id="previewMain" src="<?php echo $hasMain ? $imgMain : 'https://placehold.co/800x400?text=Upload+Banner'; ?>" class="preview-img">

                        <div class="upload-overlay">
                            <i class="fa-solid fa-camera"></i>
                            <span>Thay đổi ảnh</span>
                        </div>

                        <input type="file" name="banner_main_img" id="mainInput" hidden onchange="previewBanner(this, 'previewMain')">
                    </div>

                    <div class="input-group">
                        <label>Đường dẫn khi click (Link)</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-link"></i>
                            <input type="text" name="banner_main_link" value="<?php echo $settings['banner_main_link']; ?>" placeholder="https://...">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-side">

                <div class="card-box mb-3">
                    <div class="card-header">
                        <h3>Banner Phụ 1 (Trên)</h3>
                        <small>Size: 400x190px</small>
                    </div>

                    <div class="upload-zone sub-zone" onclick="document.getElementById('sub1Input').click()">
                        <?php
                        $imgSub1 = !empty($settings['banner_sub1_img']) ? '/assets/uploads/banners/' . $settings['banner_sub1_img'] : '';
                        $hasSub1 = !empty($imgSub1);
                        ?>
                        <img id="previewSub1" src="<?php echo $hasSub1 ? $imgSub1 : 'https://placehold.co/400x190?text=Banner+Nho'; ?>" class="preview-img">

                        <div class="upload-overlay">
                            <i class="fa-solid fa-pen"></i>
                        </div>
                        <input type="file" name="banner_sub1_img" id="sub1Input" hidden onchange="previewBanner(this, 'previewSub1')">
                    </div>

                    <div class="input-row">
                        <div class="input-group">
                            <label>Tiêu đề</label>
                            <input type="text" name="banner_sub1_title" value="<?php echo $settings['banner_sub1_title']; ?>" placeholder="Deal Sốc...">
                        </div>
                        <div class="input-group">
                            <label>Link</label>
                            <input type="text" name="banner_sub1_link" value="<?php echo $settings['banner_sub1_link']; ?>" placeholder="#">
                        </div>
                    </div>
                </div>

                <div class="card-box">
                    <div class="card-header">
                        <h3>Banner Phụ 2 (Dưới)</h3>
                        <small>Size: 400x190px</small>
                    </div>

                    <div class="upload-zone sub-zone" onclick="document.getElementById('sub2Input').click()">
                        <?php
                        $imgSub2 = !empty($settings['banner_sub2_img']) ? '/assets/uploads/banners/' . $settings['banner_sub2_img'] : '';
                        $hasSub2 = !empty($imgSub2);
                        ?>
                        <img id="previewSub2" src="<?php echo $hasSub2 ? $imgSub2 : 'https://placehold.co/400x190?text=Banner+Nho'; ?>" class="preview-img">

                        <div class="upload-overlay">
                            <i class="fa-solid fa-pen"></i>
                        </div>
                        <input type="file" name="banner_sub2_img" id="sub2Input" hidden onchange="previewBanner(this, 'previewSub2')">
                    </div>

                    <div class="input-row">
                        <div class="input-group">
                            <label>Tiêu đề</label>
                            <input type="text" name="banner_sub2_title" value="<?php echo $settings['banner_sub2_title']; ?>" placeholder="Combo...">
                        </div>
                        <div class="input-group">
                            <label>Link</label>
                            <input type="text" name="banner_sub2_link" value="<?php echo $settings['banner_sub2_link']; ?>" placeholder="#">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

<style>
    /* Layout 2 cột giống trang chủ */
    .banner-layout-wrapper {
        display: flex;
        gap: 20px;
        margin-top: 20px;
    }

    .col-main {
        flex: 6;
    }

    /* 60% */
    .col-side {
        flex: 4;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* 40% */

    /* Card Box */
    .card-box {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        padding: 20px;
        border: 1px solid #eee;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 10px;
    }

    .card-header h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #333;
    }

    .card-header small {
        color: #999;
        font-size: 12px;
    }

    /* Vùng Upload Ảnh Sành Điệu */
    .upload-zone {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        border: 2px dashed #e0e0e0;
        transition: 0.3s;
        background: #f9f9f9;
    }

    .upload-zone:hover {
        border-color: #007bff;
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.15);
    }

    .main-zone {
        height: 350px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sub-zone {
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .preview-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Ảnh luôn lấp đầy khung */
        display: block;
    }

    /* Hiệu ứng Overlay khi Hover */
    .upload-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
        opacity: 0;
        transition: 0.3s;
    }

    .upload-zone:hover .upload-overlay {
        opacity: 1;
    }

    .upload-overlay i {
        font-size: 24px;
        margin-bottom: 5px;
    }

    .upload-overlay span {
        font-size: 12px;
        font-weight: 500;
    }

    /* Input Fields đẹp hơn */
    .input-group {
        margin-top: 15px;
    }

    .input-group label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #555;
        margin-bottom: 5px;
    }

    .input-wrapper {
        position: relative;
    }

    .input-wrapper i {
        position: absolute;
        left: 10px;
        top: 10px;
        color: #aaa;
    }

    .input-wrapper input {
        padding-left: 30px;
    }

    input[type="text"] {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 13px;
        transition: 0.2s;
        box-sizing: border-box;
    }

    input[type="text"]:focus {
        border-color: #007bff;
        outline: none;
    }

    .input-row {
        display: flex;
        gap: 10px;
    }

    .input-row .input-group {
        flex: 1;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .banner-layout-wrapper {
            flex-direction: column;
        }

        .main-zone {
            height: 200px;
        }
    }
</style>

<script>
    function previewBanner(input, imgId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(imgId).src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>