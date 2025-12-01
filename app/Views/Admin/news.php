<div class="content-body">
    <div class="page-header-row">
        <h2 class="page-title">Quản lý Tin tức</h2>
        <a href="/admin/news/form" class="btn-action btn-blue"><i class="fa-solid fa-plus"></i> Viết bài mới</a>
    </div>

    <form action="/admin/news" method="GET">
        <div class="filter-bar">
            <div class="search-wrapper" style="width: 100%;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="keyword" value="<?php echo $_GET['keyword'] ?? ''; ?>" placeholder="Tìm tiêu đề bài viết...">
            </div>
        </div>
    </form>

    <div class="data-card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th width="80">Ảnh</th>
                        <th>Tiêu đề bài viết</th>
                        <th>Danh mục</th>
                        <th>Ngày đăng</th>
                        <th>Trạng thái</th>
                        <th width="100">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($newsList)): ?>
                        <?php foreach ($newsList as $item): ?>
                            <tr>
                                <td data-label="ID">#<?php echo $item->id; ?></td>

                                <td data-label="Ảnh">
                                    <?php
                                    $img = !empty($item->thumbnail) ? '/assets/uploads/news/' . $item->thumbnail : 'https://placehold.co/50x50';
                                    ?>
                                    <img src="<?php echo $img; ?>" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px; border:1px solid #eee;">
                                </td>

                                <td data-label="Tiêu đề">
                                    <strong><?php echo $item->title; ?></strong><br>
                                    <span style="font-size: 11px; color: #888;">Tác giả: <?php echo $item->author_name ?? 'Admin'; ?></span>
                                </td>

                                <td data-label="Danh mục">
                                    <span style="background: #f0f2f5; padding: 4px 8px; border-radius: 4px; font-size: 12px; color: #555; font-weight: 500;">
                                        <?php echo !empty($item->category_name) ? $item->category_name : '---'; ?>
                                    </span>
                                </td>

                                <td data-label="Ngày đăng">
                                    <?php echo date('d/m/Y', strtotime($item->created_at)); ?>
                                </td>

                                <td data-label="Trạng thái">
                                    <?php echo ($item->status == 1) ? '<span class="badge bg-success">Hiện</span>' : '<span class="badge bg-secondary">Ẩn</span>'; ?>
                                </td>

                                <td data-label="Hành động">
                                    <div class="action-btns">
                                        <a href="/admin/news/form/<?php echo $item->id; ?>" class="btn-icon edit" title="Sửa">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <a href="/admin/news/delete/<?php echo $item->id; ?>"
                                            class="btn-icon delete"
                                            onclick="return confirm('Xóa bài viết này?');" title="Xóa">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center p-3">Chưa có bài viết nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>