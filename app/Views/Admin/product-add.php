<div class="content-body">

    <div class="page-header-row">
        <h2 class="page-title">Thêm sản phẩm mới</h2>
        <div class="actions">
            <a href="/admin/product" class="btn-action btn-gray"><i class="fa-solid fa-xmark"></i> Hủy bỏ</a>

            <button class="btn-action btn-green" onclick="document.getElementById('form-product').submit()">
                <i class="fa-solid fa-floppy-disk"></i> Lưu sản phẩm
            </button>
        </div>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border: 1px solid #f5c6cb; border-radius: 5px;">
            <ul style="margin: 0; padding-left: 20px;">
                <?php foreach ($errors as $err): ?>
                    <li><?php echo $err; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form id="form-product" action="/admin/product/store" method="POST" enctype="multipart/form-data" class="form-layout">

        <div class="form-col-main">

            <div class="data-card form-card">
                <h3 class="card-title">Thông tin cơ bản</h3>

                <div class="form-group">
                    <label>Tên sản phẩm <span class="required" style="color:red">*</span></label>
                    <input type="text" name="name" required placeholder="Nhập tên thuốc, chế phẩm...">
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label>Mã sản phẩm (SKU)</label>
                        <input type="text" name="sku" placeholder="VD: SP001">
                    </div>

                    <div class="form-group">
                        <label>Danh mục <span style="color:red">*</span></label>
                        <select name="category_id" required>
                            <option value="">-- Chọn danh mục --</option>

                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $cat): ?>

                                    <option value="<?php echo $cat->id; ?>">
                                        <?php
                                        // XỬ LÝ HIỂN THỊ CÂY THƯ MỤC

                                        // Nếu là danh mục CON (level > 0)
                                        if (isset($cat->level) && $cat->level > 0) {
                                            // Thụt đầu dòng: |--- Tên con
                                            echo str_repeat('|--- ', $cat->level) . $cat->name;
                                        }
                                        // Nếu là danh mục CHA (Gốc)
                                        else {
                                            // In hoa cho nổi bật: TÊN CHA
                                            echo mb_strtoupper($cat->name, 'UTF-8');
                                        }
                                        ?>
                                    </option>

                                <?php endforeach; ?>
                            <?php endif; ?>

                        </select>
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label>Giá bán (VNĐ) <span class="required" style="color:red">*</span></label>
                        <input type="number" name="price" required placeholder="0">
                    </div>
                    <div class="form-group">
                        <label>Giá khuyến mãi</label>
                        <input type="number" name="sale_price" placeholder="0">
                    </div>
                </div>
            </div>

            <div class="data-card form-card">
                <h3 class="card-title">Nội dung chi tiết</h3>

                <div class="form-group">
                    <label>1. Công dụng sản phẩm</label>
                    <textarea name="uses" id="editor-usage"></textarea>
                </div>

                <div class="form-group">
                    <label>2. Thành phần</label>
                    <textarea name="ingredients" id="editor-ingredients"></textarea>
                </div>

                <div class="form-group">
                    <label>3. Hướng dẫn sử dụng</label>
                    <textarea name="usage_instruction" id="editor-guide"></textarea>
                </div>

                <div class="form-group">
                    <label>4. Thông tin khác (Lưu ý/Bảo quản)</label>
                    <textarea name="note" id="editor-other"></textarea>
                </div>
            </div>
        </div>

        <div class="form-col-side">

            <div class="data-card form-card">
                <h3 class="card-title">Hình ảnh</h3>

                <div class="main-image-upload">
                    <label style="font-size:13px; font-weight:600; margin-bottom:8px; display:block; color:#555;">Ảnh đại diện chính</label>
                    <div class="image-upload-box main-box" onclick="document.getElementById('mainImgInput').click()">
                        <img id="previewMain" src="" class="img-hidden">
                        <div class="upload-placeholder" id="placeholderMain">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <span>Tải ảnh lên</span>
                        </div>
                        <input type="file" name="main_image" id="mainImgInput" hidden onchange="previewImage(event, 'previewMain', 'placeholderMain')">
                    </div>
                </div>

                <div class="sub-images-area">
                    <label style="font-size:13px; font-weight:600; margin-bottom:8px; display:block; color:#555;">Ảnh kèm theo (3 hình)</label>
                    <div class="sub-images-grid">

                        <div class="sub-image-item" onclick="document.getElementById('subImg1').click()">
                            <img id="previewSub1" class="img-hidden">
                            <div class="icon-plus" id="iconSub1"><i class="fa-solid fa-plus"></i></div>
                            <input type="file" name="gallery[]" id="subImg1" hidden onchange="previewImage(event, 'previewSub1', 'iconSub1')">
                        </div>

                        <div class="sub-image-item" onclick="document.getElementById('subImg2').click()">
                            <img id="previewSub2" class="img-hidden">
                            <div class="icon-plus" id="iconSub2"><i class="fa-solid fa-plus"></i></div>
                            <input type="file" name="gallery[]" id="subImg2" hidden onchange="previewImage(event, 'previewSub2', 'iconSub2')">
                        </div>

                        <div class="sub-image-item" onclick="document.getElementById('subImg3').click()">
                            <img id="previewSub3" class="img-hidden">
                            <div class="icon-plus" id="iconSub3"><i class="fa-solid fa-plus"></i></div>
                            <input type="file" name="gallery[]" id="subImg3" hidden onchange="previewImage(event, 'previewSub3', 'iconSub3')">
                        </div>

                    </div>
                </div>
            </div>

            <div class="data-card form-card">
                <h3 class="card-title">Tổ chức</h3>
                <div class="form-group">
                    <label>Trạng thái</label>
                    <select name="status">
                        <option value="1">Đang bán</option>
                        <option value="0">Ẩn</option>
                        <option value="2">Hết hàng</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tồn kho</label>
                    <input type="number" name="stock" value="100">
                </div>
                <div class="form-group checkbox-group">
                    <label><input type="checkbox" name="is_featured" value="1"> Sản phẩm nổi bật</label>
                    <label><input type="checkbox" name="is_new" value="1"> Sản phẩm mới</label>
                </div>
            </div>

            <div class="data-card form-card" style="border-top: 3px solid #ffc107;">
                <h3 class="card-title">Cấu hình Khuyến mãi</h3>

                <div class="form-group">
                    <label>Loại khuyến mãi</label>
                    <select name="promo_type" id="promoType" onchange="togglePromo()">
                        <option value="none">Không áp dụng</option>
                        <option value="same">Mua X tặng Y (Cùng loại)</option>
                        <option value="gift">Mua SP tặng Quà khác</option>
                    </select>
                </div>

                <div id="promoConfig" style="display: none;">
                    <div class="form-row-2">
                        <div class="form-group">
                            <label>Mua (SL)</label>
                            <input type="number" name="promo_buy" value="1" min="1">
                        </div>
                        <div class="form-group">
                            <label>Tặng (SL)</label>
                            <input type="number" name="promo_get" value="1" min="1">
                        </div>
                    </div>

                    <div class="form-group" id="giftSelect" style="display: none;">
                        <label>Sản phẩm quà tặng <span style="color:red">*</span></label>

                        <select name="promo_gift_id" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
                            <option value="0">-- Chọn sản phẩm tặng kèm --</option>

                            <?php if (!empty($all_products)): ?>
                                <?php foreach ($all_products as $p): ?>

                                    <option value="<?php echo $p->id; ?>">
                                        <?php echo $p->name; ?>
                                    </option>

                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>

                        <small style="color:#666;">Khách mua sẽ được tặng kèm sản phẩm này.</small>
                    </div>

                </div>
            </div>

        </div>
    </form>

</div>

<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>

<script>
    // 1. Hàm xem trước ảnh (Preview Image)
    function previewImage(event, imgId, placeholderId) {
        const reader = new FileReader();
        reader.onload = function() {
            const img = document.getElementById(imgId);
            const placeholder = document.getElementById(placeholderId);
            img.src = reader.result;
            img.classList.remove('img-hidden');
            img.classList.add('img-show');
            if (placeholder) placeholder.style.display = 'none';
        };
        if (event.target.files[0]) reader.readAsDataURL(event.target.files[0]);
    }

    // 2. CẤU HÌNH CKEDITOR 4
    const editorIds = ['editor-usage', 'editor-ingredients', 'editor-guide', 'editor-other'];

    editorIds.forEach(id => {
        if (document.getElementById(id)) {
            CKEDITOR.replace(id, {
                height: 250,
                language: 'vi',

                // Cấu hình Toolbar gọn gàng
                toolbar: [{
                        name: 'styles',
                        items: ['Format', 'Font', 'FontSize']
                    },
                    {
                        name: 'colors',
                        items: ['TextColor', 'BGColor']
                    },
                    {
                        name: 'basicstyles',
                        items: ['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat']
                    },
                    {
                        name: 'paragraph',
                        items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']
                    },
                    {
                        name: 'links',
                        items: ['Link', 'Unlink']
                    },
                    {
                        name: 'insert',
                        items: ['Image', 'Table', 'HorizontalRule']
                    }, // Nút Image
                    {
                        name: 'tools',
                        items: ['Maximize', 'ShowBlocks']
                    }
                ],

                // --- KẾT NỐI VỚI CONTROLLER UPLOAD ẢNH ---
                filebrowserUploadUrl: '/admin/product/uploadCkEditor',
                filebrowserUploadMethod: 'form'
            });
        }
    });
</script>

<script>
    function togglePromo() {
        var type = document.getElementById('promoType').value;
        var config = document.getElementById('promoConfig');
        var gift = document.getElementById('giftSelect');

        if (type === 'none') {
            config.style.display = 'none';
        } else {
            config.style.display = 'block';
            if (type === 'gift') {
                gift.style.display = 'block';
            } else {
                gift.style.display = 'none';
            }
        }
    }
</script>