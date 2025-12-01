<div class="content-body">
    <div class="page-header-row">
        <h2 class="page-title">Danh mục Tin tức</h2>
        <a href="/admin/newscategory/form" class="btn-action btn-blue">
            <i class="fa-solid fa-plus"></i> Thêm danh mục
        </a>
    </div>

    <div class="filter-bar">
        <div class="search-wrapper" style="width:100%">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Tìm kiếm danh mục tin...">
        </div>
    </div>

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
                                <td style="text-align: center; font-weight: bold; color: #555;">
                                    <?php 
                                        if ($item->parent_id == 0) {
                                            $stt++; echo $stt;
                                        } 
                                    ?>
                                </td>
                                
                                <td>
                                    <?php if ($item->parent_id > 0): ?>
                                        <div style="padding-left: 30px; color: #555;">
                                            <i class="fa-solid fa-turn-up" style="transform: rotate(90deg); margin-right: 5px; color: #999;"></i> 
                                            <?php echo $item->name; ?>
                                        </div>
                                    <?php else: ?>
                                        <strong style="color: #007bff; text-transform: uppercase;"><?php echo $item->name; ?></strong>
                                    <?php endif; ?>
                                </td>

                                <td><?php echo $item->slug; ?></td>

                                <td>
                                    <?php echo !empty($item->parent_name) ? '<strong>'.$item->parent_name.'</strong>' : '<span style="color:#ccc; font-style:italic;">Gốc (Root)</span>'; ?>
                                </td>

                                <td>
                                    <?php echo ($item->status == 1) ? '<span class="badge bg-success" style="color:green; font-weight:bold;">Hiển thị</span>' : '<span class="badge bg-secondary" style="color:orange; font-weight:bold;">Ẩn</span>'; ?>
                                </td>

                                <td>
                                    <div class="action-btns">
                                        <a href="/admin/newscategory/form/<?php echo $item->id; ?>" class="btn-icon edit"><i class="fa-solid fa-pen"></i></a>
                                        <a href="/admin/newscategory/delete/<?php echo $item->id; ?>" class="btn-icon delete" onclick="return confirm('Xóa danh mục này?');"><i class="fa-solid fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center p-3">Chưa có danh mục nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>