<div class="content-body">
    <div class="page-header-row">
        <h2 class="page-title"><?php echo isset($news) ? 'Cập nhật bài viết' : 'Viết bài mới'; ?></h2>
        <div class="actions">
            <a href="/admin/news" class="btn-action btn-gray">Hủy bỏ</a>
            <button class="btn-action btn-green" onclick="document.getElementById('form-news').submit()">
                <i class="fa-solid fa-floppy-disk"></i> Lưu bài viết
            </button>
        </div>
    </div>

    <form id="form-news" action="/admin/news/save" method="POST" enctype="multipart/form-data" class="form-layout">

        <?php if (isset($news)): ?>
            <input type="hidden" name="id" value="<?php echo $news->id; ?>">
        <?php endif; ?>

        <div class="form-col-main">
            <div class="data-card form-card">
                <div class="form-group">
                    <label>Tiêu đề bài viết <span style="color:red">*</span></label>
                    <input type="text" name="title" id="newsTitle" onkeyup="generateSlug()"
                        required placeholder="Nhập tiêu đề..."
                        value="<?php echo isset($news) ? $news->title : ''; ?>">
                </div>

                <div class="form-group">
                    <label>Slug (Đường dẫn - Để trống tự tạo)</label>
                    <input type="text" name="slug" id="newsSlug"
                        placeholder="tu-dong-tao-slug"
                        value="<?php echo isset($news) ? $news->slug : ''; ?>">
                </div>

                <div class="form-group">
                    <label>Mô tả ngắn (Sapo)</label>
                    <textarea name="summary" rows="3" class="form-control"><?php echo isset($news) ? $news->summary : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label>Nội dung chi tiết</label>
                    <textarea name="content" id="editor-content"><?php echo isset($news) ? $news->content : ''; ?></textarea>
                </div>
            </div>
        </div>

        <div class="form-col-side">
            <div class="data-card form-card">
                <div class="form-group">
                    <label>Danh mục tin tức <span style="color:red">*</span></label>

                    <select name="category_id" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
                        <option value="">-- Chọn danh mục --</option>

                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>

                                <?php
                                // 1. LOGIC KHÓA DANH MỤC CHA
                                // Nếu level = 0 (Cha) -> Thêm 'disabled' để không cho chọn
                                $isDisabled = ($cat->level == 0) ? 'disabled' : '';

                                // Style cho đẹp: Cha thì in đậm, nền xám nhẹ
                                $style = ($cat->level == 0) ? 'font-weight:bold; background-color:#f2f2f2; color:#333;' : '';
                                ?>

                                <option value="<?php echo $cat->id; ?>"
                                    <?php echo $isDisabled; ?>
                                    style="<?php echo $style; ?>"
                                    <?php echo (isset($news) && $news->category_id == $cat->id) ? 'selected' : ''; ?>>

                                    <?php
                                    // 2. HIỂN THỊ TÊN PHÂN CẤP
                                    if ($cat->level == 0) {
                                        // Là Cha: In hoa
                                        echo mb_strtoupper($cat->name, 'UTF-8');
                                    } else {
                                        // Là Con: Thụt đầu dòng
                                        echo str_repeat('|--- ', $cat->level) . $cat->name;
                                    }
                                    ?>
                                </option>

                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>Chưa có danh mục nào</option>
                        <?php endif; ?>
                    </select>

                    <div style="margin-top: 5px; text-align: right;">
                        <a href="/admin/newscategory/form" target="_blank" style="font-size: 12px; color: #007bff; text-decoration: none;">
                            <i class="fa-solid fa-plus-circle"></i> Tạo thêm danh mục
                        </a>
                    </div>
                </div>

                <div class="form-group">
                    <label>Ảnh đại diện (Thumbnail)</label>

                    <div class="image-upload-box" onclick="document.getElementById('thumbInput').click()"
                        style="border:2px dashed #ddd; padding:10px; text-align:center; cursor:pointer; position: relative; min-height: 150px; display:flex; align-items:center; justify-content:center;">

                        <?php
                        $thumbSrc = (isset($news) && !empty($news->thumbnail)) ? '/assets/uploads/news/' . $news->thumbnail : '';
                        $displayImg = !empty($thumbSrc) ? 'block' : 'none';
                        $displayPlace = !empty($thumbSrc) ? 'none' : 'block';
                        ?>

                        <img id="img-preview" src="<?php echo $thumbSrc; ?>"
                            style="max-width: 100%; max-height: 200px; display: <?php echo $displayImg; ?>;">

                        <div id="placeholder" style="display: <?php echo $displayPlace; ?>;">
                            <i class="fa-solid fa-cloud-arrow-up" style="font-size: 30px; color: #ccc;"></i>
                            <p style="margin: 5px 0; color: #999;">Bấm để tải ảnh</p>
                        </div>

                        <input type="file" name="thumbnail" id="thumbInput" hidden onchange="preview(this)">

                        <input type="hidden" name="old_thumbnail" value="<?php echo isset($news) ? $news->thumbnail : ''; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Trạng thái</label>
                    <select name="status" style="width:100%; padding:8px;">
                        <option value="1" <?php echo (isset($news) && $news->status == 1) ? 'selected' : ''; ?>>Hiển thị</option>
                        <option value="0" <?php echo (isset($news) && $news->status == 0) ? 'selected' : ''; ?>>Ẩn</option>
                    </select>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
<script>
    // 1. Cấu hình CKEditor (Có upload ảnh)
    CKEDITOR.replace('editor-content', {
        height: 450,
        filebrowserUploadUrl: '/admin/product/uploadCkEditor', // Tận dụng hàm upload của ProductController
        filebrowserUploadMethod: 'form'
    });

    // 2. Preview ảnh thumbnail
    function preview(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('img-preview').src = e.target.result;
                document.getElementById('img-preview').style.display = 'block';
                document.getElementById('placeholder').style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<script>
    // 1. HÀM XEM TRƯỚC ẢNH (PREVIEW)
    function preview(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                // Lấy thẻ ảnh và thẻ placeholder
                var img = document.getElementById('img-preview');
                var placeholder = document.getElementById('placeholder');

                // Gán đường dẫn ảnh vừa chọn vào src
                img.src = e.target.result;

                // Hiển thị ảnh, ẩn placeholder
                img.style.display = 'block';
                placeholder.style.display = 'none';
            }

            // Đọc file
            reader.readAsDataURL(input.files[0]);
        }
    }

    // 2. HÀM TẠO SLUG TỰ ĐỘNG
    function generateSlug() {
        var title = document.getElementById('newsTitle').value;
        var slug = title.toLowerCase();

        // Đổi ký tự có dấu thành không dấu
        slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
        slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
        slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
        slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ơ|ờ|ở|ỡ|ợ/gi, 'o');
        slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
        slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
        slug = slug.replace(/đ/gi, 'd');

        // Xóa ký tự đặc biệt
        slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');

        // Đổi khoảng trắng thành gạch ngang
        slug = slug.replace(/ /gi, "-");
        slug = slug.replace(/\-\-\-\-\-/gi, '-');
        slug = slug.replace(/\-\-\-\-/gi, '-');
        slug = slug.replace(/\-\-\-/gi, '-');
        slug = slug.replace(/\-\-/gi, '-');

        // Xóa gạch ngang đầu cuối
        slug = '@' + slug + '@';
        slug = slug.replace(/\@\-|\-\@|\@/gi, '');

        // Gán vào ô Slug
        document.getElementById('newsSlug').value = slug;
    }
</script>