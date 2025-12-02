<div class="content-body">

    <div class="page-header-row">
        <h2 class="page-title">Cập nhật: <span style="color:#007bff"><?php echo $product->name; ?></span></h2>

        <div class="actions">
            <a href="/admin/product" class="btn-action btn-gray"><i class="fa-solid fa-xmark"></i> Hủy bỏ</a>

            <button class="btn-action btn-green" onclick="document.getElementById('form-product-edit').submit()">
                <i class="fa-solid fa-floppy-disk"></i> Cập nhật
            </button>
        </div>
    </div>

    <form id="form-product-edit" action="/admin/product/update/<?php echo $product->id; ?>" method="POST" enctype="multipart/form-data" class="form-layout">

        <input type="hidden" name="id" value="<?php echo $product->id; ?>">

        <div class="form-col-main">

            <div class="data-card form-card">
                <h3 class="card-title">Thông tin cơ bản</h3>

                <div class="form-group">
                    <label>Tên sản phẩm <span class="required" style="color:red">*</span></label>
                    <input type="text" name="name" required value="<?php echo $product->name; ?>" placeholder="Nhập tên thuốc...">
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label>Mã sản phẩm (SKU)</label>
                        <input type="text" name="sku" value="<?php echo $product->sku; ?>" placeholder="VD: SP001">
                    </div>

                    <div class="form-group">
                        <label>Danh mục <span style="color:red">*</span></label>
                        <select name="category_id" required>
                            <option value="">-- Chọn danh mục --</option>

                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $cat): ?>

                                    <option value="<?php echo $cat->id; ?>" <?php echo ($cat->id == $product->category_id) ? 'selected' : ''; ?>>
                                        <?php
                                        // 2. Logic HIỂN THỊ CÂY PHÂN CẤP (Cha in hoa, Con thụt dòng)
                                        if (isset($cat->level) && $cat->level > 0) {
                                            echo str_repeat('|--- ', $cat->level) . $cat->name;
                                        } else {
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
                        <input type="number" name="price" required value="<?php echo $product->price; ?>">
                    </div>
                    <div class="form-group">
                        <label>Giá khuyến mãi</label>
                        <input type="number" name="sale_price" value="<?php echo $product->sale_price; ?>">
                    </div>
                </div>
            </div>

            <div class="data-card form-card">
                <h3 class="card-title">Nội dung chi tiết</h3>
                <div class="form-group">
                    <label>1. Công dụng sản phẩm</label>
                    <textarea name="uses" id="editor-usage"><?php echo $product->uses; ?></textarea>
                </div>

                <div class="form-group">
                    <label>2. Thành phần</label>
                    <textarea name="ingredients" id="editor-ingredients"><?php echo $product->ingredients; ?></textarea>
                </div>

                <div class="form-group">
                    <label>3. Hướng dẫn sử dụng</label>
                    <textarea name="usage_instruction" id="editor-guide"><?php echo $product->usage_instruction; ?></textarea>
                </div>

                <div class="form-group">
                    <label>4. Thông tin khác (Lưu ý/Bảo quản)</label>
                    <textarea name="note" id="editor-other"><?php echo $product->note; ?></textarea>
                </div>
            </div>
        </div>

        <div class="form-col-side">

            <div class="data-card form-card">
                <h3 class="card-title">Hình ảnh</h3>

                <div class="main-image-upload">
                    <label style="font-size:13px; font-weight:600; margin-bottom:8px; display:block; color:#555;">Ảnh đại diện (Chọn để thay đổi)</label>

                    <?php
                    $mainImgSrc = !empty($product->main_image) ? '/assets/uploads/products/' . $product->main_image : '';
                    $hasMainImg = !empty($mainImgSrc);
                    ?>

                    <div class="image-upload-box main-box" onclick="document.getElementById('mainImgInput').click()" style="cursor:pointer; border:2px dashed #ddd; padding:10px; text-align:center; position:relative;">

                        <img id="previewMain" src="<?php echo $mainImgSrc; ?>" style="max-width:100%; <?php echo !$hasMainImg ? 'display:none;' : ''; ?>">

                        <div id="placeholderMain" style="<?php echo $hasMainImg ? 'display:none;' : ''; ?>">
                            <i class="fa-solid fa-cloud-arrow-up"></i> <span>Tải ảnh mới</span>
                        </div>

                        <input type="file" name="main_image" id="mainImgInput" hidden onchange="previewImage(event, 'previewMain', 'placeholderMain')">

                        <input type="hidden" name="old_main_image" value="<?php echo $product->main_image; ?>">
                    </div>
                </div>

                <div class="sub-images-area" style="margin-top:20px;">
                    <label style="font-size:13px; font-weight:600; margin-bottom:8px; display:block; color:#555;">Album ảnh hiện tại</label>

                    <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 15px;">
                        <?php if (!empty($gallery)): foreach ($gallery as $img): ?>
                                <div style="position: relative; width: 70px; height: 70px; border: 1px solid #ddd;">
                                    <img src="/assets/uploads/products/<?php echo $img->image_url; ?>" style="width:100%; height:100%; object-fit:cover;">

                                    <a href="/admin/product/deleteGallery/<?php echo $img->id; ?>"
                                        onclick="return confirm('Xóa ảnh này khỏi album?')"
                                        style="position: absolute; top:-5px; right:-5px; background:red; color:white; border-radius:50%; width:20px; height:20px; text-align:center; line-height:20px; font-size:12px; text-decoration:none;">
                                        <i class="fa-solid fa-xmark"></i>
                                    </a>
                                </div>
                        <?php endforeach;
                        endif; ?>
                    </div>

                    <label style="font-size:13px; font-weight:600; margin-bottom:8px; display:block; color:#555;">Thêm ảnh mới vào Album</label>
                    <input type="file" name="gallery[]" multiple class="form-control" style="width:100%;">
                    <small style="color:#888">Giữ Ctrl để chọn nhiều ảnh</small>
                </div>
            </div>

            <div class="data-card form-card">
                <h3 class="card-title">Tổ chức</h3>
                <div class="form-group">
                    <label>Trạng thái</label>
                    <select name="status">
                        <option value="1" <?php echo ($product->status == 1) ? 'selected' : ''; ?>>Đang bán</option>
                        <option value="0" <?php echo ($product->status == 0) ? 'selected' : ''; ?>>Ẩn</option>
                        <option value="2" <?php echo ($product->status == 2) ? 'selected' : ''; ?>>Hết hàng</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tồn kho</label>
                    <input type="number" name="stock" value="<?php echo $product->stock; ?>">
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

                    <div class="form-group" id="giftSelect" style="display: <?php echo ($product->promo_type == 'gift') ? 'block' : 'none'; ?>;">
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
                    </div>

                </div>
            </div>

        </div>
    </form>

</div>

<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
<script>
    function previewImage(event, imgId, placeholderId) {
        if (event.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function() {
                const img = document.getElementById(imgId);
                const placeholder = document.getElementById(placeholderId);
                img.src = reader.result;
                img.classList.remove('img-hidden');
                img.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    }

    const editorIds = ['editor-usage', 'editor-ingredients', 'editor-guide', 'editor-other'];
    editorIds.forEach(id => {
        if (document.getElementById(id)) {
            CKEDITOR.replace(id, {
                height: 250,
                language: 'vi',
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
                        items: ['Bold', 'Italic', 'Underline', 'Strike']
                    },
                    {
                        name: 'paragraph',
                        items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight']
                    },
                    {
                        name: 'links',
                        items: ['Link', 'Unlink']
                    },
                    {
                        name: 'insert',
                        items: ['Image', 'Table']
                    },
                    {
                        name: 'tools',
                        items: ['Maximize']
                    }
                ],
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