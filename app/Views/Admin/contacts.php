<div class="content-body">
    <div class="page-header-row">
        <h2 class="page-title">Danh sách Khách hàng liên hệ</h2>
    </div>

    <div class="data-card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th>Họ tên</th>
                        <th>Thông tin</th>
                        <th width="40%">Nội dung</th>
                        <th>Ngày gửi</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($contacts)): foreach ($contacts as $c): ?>
                            <tr>
                                <td>#<?php echo $c->id; ?></td>
                                <td><strong><?php echo $c->fullname; ?></strong></td>
                                <td>
                                    <i class="fa-solid fa-envelope"></i> <?php echo $c->email; ?><br>
                                    <i class="fa-solid fa-phone"></i> <?php echo $c->phone; ?>
                                </td>
                                <td><?php echo $c->message; ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($c->created_at)); ?></td>
                                <td>
                                    <div class="action-btns">

                                        <a href="/admin/contact/detail/<?php echo $c->id; ?>" class="btn-icon edit" title="Xem chi tiết">
                                            <i class="fa-regular fa-eye"></i>
                                        </a>

                                        <a href="/admin/contact/delete/<?php echo $c->id; ?>" class="btn-icon delete" onclick="return confirm('Xóa tin nhắn này?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>

                                    </div>
                                </td>
                            </tr>
                        <?php endforeach;
                    else: ?>
                        <tr>
                            <td colspan="6" class="text-center p-3">Chưa có liên hệ nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>