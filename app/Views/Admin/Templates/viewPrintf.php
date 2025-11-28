<div class="content-body">
    <div class="page-header-row">
        <h2 class="page-title">Quản lý Mẫu in hóa đơn</h2>
        
        <a href="/admin/template/printForm" class="btn-action btn-blue">
            <i class="fa-solid fa-plus"></i> Thêm mẫu mới
        </a>
    </div>

    <div class="data-card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th width="50" style="text-align:center;">ID</th>
                        <th>Tên mẫu in</th>
                        <th>Loại mặc định</th>
                        <th>Ngày tạo</th>
                        <th width="100">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($templates)): ?>
                        <?php foreach ($templates as $tpl): ?>
                            <tr>
                                <td data-label="ID" style="text-align:center;">
                                    #<?php echo $tpl->id; ?>
                                </td>
                                
                                <td data-label="Tên mẫu">
                                    <strong><?php echo $tpl->name; ?></strong>
                                </td>

                                <td data-label="Mặc định">
                                    <?php if ($tpl->is_default == 1): ?>
                                        <span class="badge bg-success">Đang dùng</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary" style="opacity:0.5;">-</span>
                                    <?php endif; ?>
                                </td>

                                <td data-label="Ngày tạo">
                                    <?php echo date('d/m/Y', strtotime($tpl->created_at)); ?>
                                </td>

                                <td data-label="Hành động">
                                    <div class="action-btns">
                                        <a href="/admin/template/printForm/<?php echo $tpl->id; ?>" class="btn-icon edit" title="Sửa thiết kế">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        
                                        <?php if ($tpl->is_default == 0): ?>
                                            <a href="/admin/template/delete/<?php echo $tpl->id; ?>" 
                                               class="btn-icon delete" 
                                               onclick="return confirm('Bạn có chắc muốn xóa mẫu in này?');">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 30px; color: #999;">
                                Chưa có mẫu in nào. Hãy bấm "Thêm mẫu mới".
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>