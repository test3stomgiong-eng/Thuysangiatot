<div class="content-body">
    <div class="page-header-row">
        <h2 class="page-title"><?php echo isset($category) ? 'Cập nhật danh mục tin' : 'Thêm danh mục tin'; ?></h2>
        <div class="actions">
            <a href="/admin/newscategory" class="btn-action btn-gray">Hủy bỏ</a>
            <button class="btn-action btn-green" onclick="document.getElementById('form-cat').submit()">Lưu lại</button>
        </div>
    </div>

    <div style="max-width: 600px; margin: 0 auto;">
        <form id="form-cat" action="/admin/newscategory/save" method="POST" class="data-card form-card">

            <?php if (isset($category)): ?>
                <input type="hidden" name="id" value="<?php echo $category->id; ?>">
            <?php endif; ?>

            <div class="form-group">
                <label>Tên danh mục <span style="color:red">*</span></label>
                <input type="text" name="name" id="catName" onkeyup="generateSlug()"
                    required class="form-control"
                    value="<?php echo isset($category) ? $category->name : ''; ?>">
            </div>

            <div class="form-group">
                <label>Slug</label>
                <input type="text" name="slug" id="catSlug"
                    class="form-control"
                    value="<?php echo isset($category) ? $category->slug : ''; ?>">
            </div>

            <div class="form-group">
                <label>Danh mục cha</label>
                <select name="parent_id" class="form-control" style="width:100%; padding:8px;">
                    <option value="0">-- Là danh mục gốc --</option>
                    <?php if (!empty($categories)): foreach ($categories as $cat): ?>
                            <?php if (isset($category) && $category->id == $cat->id) continue; ?>

                            <option value="<?php echo $cat->id; ?>" <?php echo (isset($category) && $category->parent_id == $cat->id) ? 'selected' : ''; ?>>
                                <?php
                                if (isset($cat->level) && $cat->level > 0) echo str_repeat('|--- ', $cat->level) . $cat->name;
                                else echo mb_strtoupper($cat->name);
                                ?>
                            </option>
                    <?php endforeach;
                    endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Trạng thái</label>
                <select name="status" style="width:100%; padding:8px;">
                    <option value="1" <?php echo (isset($category) && $category->status == 1) ? 'selected' : ''; ?>>Hiển thị</option>
                    <option value="0" <?php echo (isset($category) && $category->status == 0) ? 'selected' : ''; ?>>Ẩn</option>
                </select>
            </div>

        </form>
    </div>
</div>
<script>
    function generateSlug() {
        // 1. Lấy giá trị từ ô Tên
        var title = document.getElementById('catName').value;

        // 2. Chuyển đổi thành Slug
        var slug = title.toLowerCase(); // Đổi thành chữ thường

        // Đổi ký tự có dấu thành không dấu
        slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
        slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
        slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
        slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
        slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
        slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
        slug = slug.replace(/đ/gi, 'd');

        // Xóa các ký tự đặc biệt
        slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');

        // Đổi khoảng trắng thành gạch ngang
        slug = slug.replace(/ /gi, "-");

        // Đổi nhiều gạch ngang liên tiếp thành 1 gạch ngang
        slug = slug.replace(/\-\-\-\-\-/gi, '-');
        slug = slug.replace(/\-\-\-\-/gi, '-');
        slug = slug.replace(/\-\-\-/gi, '-');
        slug = slug.replace(/\-\-/gi, '-');

        // Xóa các ký tự gạch ngang ở đầu và cuối
        slug = '@' + slug + '@';
        slug = slug.replace(/\@\-|\-\@|\@/gi, '');

        // 3. Gán giá trị vào ô Slug
        document.getElementById('catSlug').value = slug;
    }
</script>