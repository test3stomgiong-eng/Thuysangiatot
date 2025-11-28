<div class="content-body">
    <div class="page-header-row">
        <h2 class="page-title">Danh sách sản phẩm</h2>
        <a href="/admin/product/add" class="btn-action btn-green">
            <i class="fa-solid fa-plus"></i> Thêm mới
        </a>
    </div>

    <form action="/admin/product" method="GET">
        <div class="filter-bar">
            <div class="search-wrapper">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="keyword"
                    value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : ''; ?>"
                    placeholder="Tìm kiếm tên hoặc mã SKU...">
            </div>

            <select class="filter-select" name="cat_id" onchange="this.form.submit()">
                <option value="">-- Tất cả danh mục --</option>
                <?php if (!empty($categories)): foreach ($categories as $c): ?>
                        <option value="<?php echo $c->id; ?>" <?php echo (isset($_GET['cat_id']) && $_GET['cat_id'] == $c->id) ? 'selected' : ''; ?>>
                            <?php echo $c->name; ?>
                        </option>
                <?php endforeach;
                endif; ?>
            </select>
        </div>
    </form>

    <div class="data-card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th width="80">Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá bán</th>
                        <th>Tồn kho</th>
                        <th>Trạng thái</th>
                        <th width="100">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $item): ?>
                            <tr>
                                <td data-label="ID">#<?php echo $item->id; ?></td>

                                <td data-label="Hình ảnh">
                                    <?php
                                    $img_src = !empty($item->main_image) ? '/assets/uploads/products/' . $item->main_image : 'https://placehold.co/50x50';
                                    ?>
                                    <img src="<?php echo $img_src; ?>" class="table-img" alt="SP" style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #eee;">
                                </td>

                                <td data-label="Tên sản phẩm">
                                    <strong><?php echo $item->name; ?></strong>
                                    <br>
                                    <span class="sub-text" style="color:#888; font-size:12px;">SKU: <?php echo $item->sku; ?></span>
                                </td>

                                <td data-label="Danh mục">
                                    <?php echo $item->category_name ?? '---'; ?>
                                </td>

                                <td data-label="Giá bán" class="text-red" style="color:red; font-weight:bold;">
                                    <?php echo number_format($item->price, 0, ',', '.'); ?>đ
                                </td>

                                <td data-label="Tồn kho">
                                    <?php
                                    if ($item->stock < 0) {
                                        // Nếu âm -> Hiện màu đỏ cảnh báo "Đang nợ khách"
                                        echo '<span style="color:red; font-weight:bold;">Âm (' . $item->stock . ')</span>';
                                    } elseif ($item->stock == 0) {
                                        echo '<span style="color:orange;">Hết hàng (0)</span>';
                                    } else {
                                        // Nếu dương -> Hiện bình thường
                                        echo $item->stock;
                                    }
                                    ?>
                                </td>

                                <td data-label="Trạng thái">
                                    <?php if ($item->status == 1): ?>
                                        <span class="status completed" style="color:green; font-weight:bold;">Đang bán</span>
                                    <?php elseif ($item->status == 2): ?>
                                        <span class="status pending" style="color:orange; font-weight:bold;">Hết hàng</span>
                                    <?php else: ?>
                                        <span class="status" style="color:gray;">Ẩn</span>
                                    <?php endif; ?>
                                </td>

                                <td data-label="Hành động">
                                    <div class="action-btns">
                                        <a href="/admin/product/edit/<?php echo $item->id; ?>" class="btn-icon edit" title="Sửa">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <a href="/admin/product/delete/<?php echo $item->id; ?>"
                                            class="btn-icon delete"
                                            title="Xóa"
                                            onclick="return confirm('Bạn có chắc muốn xóa sản phẩm: <?php echo $item->name; ?>?');">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 30px; color: #999;">
                                Không tìm thấy sản phẩm nào.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>