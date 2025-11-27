<div class="content-body">
    <div class="page-header-row">
        <h2 class="page-title">Danh mục sản phẩm</h2>
        <a href="/admin/category/add" class="btn-action btn-blue">
            <i class="fa-solid fa-plus"></i> Thêm danh mục
        </a>
    </div>

    <form action="/admin/category" method="GET">

        <div class="filter-bar">
            <div class="search-wrapper">
                <i class="fa-solid fa-magnifying-glass"></i>

                <input type="text" name="keyword"
                    value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : ''; ?>"
                    placeholder="Tìm kiếm danh mục...">
            </div>

            <select class="filter-select" name="status" onchange="this.form.submit()">
                <option value="">Tất cả trạng thái</option>

                <option value="1" <?php echo (isset($_GET['status']) && $_GET['status'] == '1') ? 'selected' : ''; ?>>
                    Đang hiện
                </option>

                <option value="0" <?php echo (isset($_GET['status']) && $_GET['status'] == '0') ? 'selected' : ''; ?>>
                    Đang ẩn
                </option>
            </select>
        </div>

    </form>

    <div class="data-card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th width="50" style="text-align: center;">STT</th>
                        <th>Tên danh mục</th>
                        <th>Slug (Đường dẫn)</th>
                        <th>Cấp cha</th>
                        <th>Trạng thái</th>
                        <th width="120">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($categories)): ?>

                        <?php $stt = 0; ?>

                        <?php foreach ($categories as $item): ?>
                            <tr>
                                <td data-label="STT" style="text-align: center; font-weight: bold; color: #555;">
                                    <?php
                                    // Chỉ đánh số nếu là DANH MỤC GỐC (Cha)
                                    if ($item->parent_id == 0) {
                                        $stt++;
                                        echo $stt;
                                    }
                                    ?>
                                </td>

                                <td data-label="Tên danh mục">
                                    <?php if ($item->parent_id > 0): ?>
                                        <div style="padding-left: 40px; color: #555;">
                                            <i class="fa-solid fa-turn-up" style="transform: rotate(90deg); margin-right: 5px; color: #999;"></i>
                                            <?php echo $item->name; ?>
                                        </div>
                                    <?php else: ?>
                                        <strong style="color: #007bff; text-transform: uppercase;"><?php echo $item->name; ?></strong>
                                    <?php endif; ?>
                                </td>

                                <td data-label="Slug (Đường dẫn)">
                                    <?php echo $item->slug; ?>
                                </td>

                                <td data-label="Cấp cha">
                                    <?php if (!empty($item->parent_name)): ?>
                                        <strong><?php echo $item->parent_name; ?></strong>
                                    <?php else: ?>
                                        <span class="sub-text" style="color: #ccc; font-style: italic;">Gốc (Root)</span>
                                    <?php endif; ?>
                                </td>

                                <td data-label="Trạng thái">
                                    <?php if ($item->status == 1): ?>
                                        <span class="status completed" style="color: green; font-weight: bold;">Hiển thị</span>
                                    <?php else: ?>
                                        <span class="status pending" style="color: orange; font-weight: bold;">Đang ẩn</span>
                                    <?php endif; ?>
                                </td>

                                <td data-label="Hành động">
                                    <div class="action-btns">
                                        <a href="/admin/category/edit/<?php echo $item->id; ?>" class="btn-icon edit" style="margin-right: 10px;">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <a href="/admin/category/delete/<?php echo $item->id; ?>"
                                            class="btn-icon delete"
                                            onclick="return confirm('Bạn có chắc muốn xóa danh mục: <?php echo $item->name; ?>?');">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px;">Chưa có danh mục nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>