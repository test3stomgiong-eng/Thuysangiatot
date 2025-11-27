<div class="content-body">
    <div class="page-header-row">
        <h2 class="page-title">Cập nhật: <span style="color:#007bff"><?php echo $category->name; ?></span></h2>
        <div class="actions">
            <a href="/admin/category" class="btn-action btn-gray">Hủy bỏ</a>
            <button class="btn-action btn-blue" onclick="document.getElementById('form-edit-cat').submit();">
                <i class="fa-solid fa-save"></i> Lưu thay đổi
            </button>
        </div>
    </div>

    <div style="max-width: 800px; margin: 0 auto;">

        <form id="form-edit-cat" action="/admin/category/update" method="POST" class="data-card form-card">

            <input type="hidden" name="id" value="<?php echo $category->id; ?>">

            <h3 class="card-title">Thông tin danh mục</h3>

            <div class="form-group">
                <label>Tên danh mục <span class="required" style="color:red">*</span></label>
                <input type="text" name="name" id="catName" required
                    value="<?php echo $category->name; ?>"
                    placeholder="Ví dụ: Men vi sinh..." onkeyup="generateSlug()">
            </div>

            <div class="form-group">
                <label>Đường dẫn (Slug)</label>
                <input type="text" name="slug" id="catSlug"
                    value="<?php echo $category->slug; ?>">
            </div>

            <div class="form-group">
                <label>Danh mục cha (Parent ID)</label>

                <select name="parent_id" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd;">

                    <option value="0" <?php echo ($category->parent_id == 0) ? 'selected' : ''; ?>>
                        -- Là danh mục gốc --
                    </option>

                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $cat): ?>

                            <?php if ($cat->id != $category->id): ?>

                                <option value="<?php echo $cat->id; ?>"
                                    <?php echo ($cat->id == $category->parent_id) ? 'selected' : ''; ?>>

                                    <?php echo $cat->name; ?>
                                </option>

                            <?php endif; ?>

                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Mô tả</label>
                <textarea name="description" rows="3"><?php echo $category->description; ?></textarea>
            </div>

            <div class="form-group">
                <label>Trạng thái</label>
                <div class="checkbox-group">
                    <label style="margin-right: 15px;">
                        <input type="radio" name="status" value="1" <?php echo ($category->status == 1) ? 'checked' : ''; ?>> Hiển thị
                    </label>
                    <label>
                        <input type="radio" name="status" value="0" <?php echo ($category->status == 0) ? 'checked' : ''; ?>> Ẩn tạm thời
                    </label>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
    function generateSlug() {
        var title = document.getElementById('catName').value;
        var slug = title.toLowerCase();
        slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
        slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
        slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
        slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
        slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
        slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
        slug = slug.replace(/đ/gi, 'd');
        slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
        slug = slug.replace(/ /gi, "-");
        slug = slug.replace(/\-\-\-\-\-/gi, '-');
        slug = slug.replace(/\-\-\-\-/gi, '-');
        slug = slug.replace(/\-\-\-/gi, '-');
        slug = slug.replace(/\-\-/gi, '-');
        slug = '@' + slug + '@';
        slug = slug.replace(/\@\-|\-\@|\@/gi, '');
        document.getElementById('catSlug').value = slug;
    }
</script>