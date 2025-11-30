<div class="content-body">
    <div class="page-header-row">
        <h2 class="page-title">Danh sách Khách hàng</h2>
    </div>

    <form action="/admin/customer" method="GET">
        <div class="filter-bar">
            <div class="search-wrapper" style="width: 100%;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="keyword"
                    value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : ''; ?>"
                    placeholder="Tìm theo tên, số điện thoại, email...">
            </div>
        </div>
    </form>

    <div class="data-card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th>Họ và tên</th>
                        <th>Liên hệ</th>
                        <th>Ngày đăng ký</th>
                        <th>Trạng thái</th>
                        <th>Đơn hàng</th>
                        <th width="100">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($customers)): ?>
                        <?php foreach ($customers as $cus): ?>
                            <tr>
                                <td data-label="ID">#<?php echo $cus->id; ?></td>

                                <td data-label="Họ tên">
                                    <strong><?php echo $cus->fullname; ?></strong>
                                </td>

                                <td data-label="Liên hệ">
                                    <div style="font-size: 13px;">
                                        <div style="margin-bottom:3px;">
                                            <i class="fa-solid fa-phone" style="color:#888; width:15px;"></i>
                                            <a href="tel:<?php echo $cus->phone; ?>" style="color:#333; text-decoration:none;"><?php echo $cus->phone; ?></a>
                                        </div>
                                        <?php if (!empty($cus->email)): ?>
                                            <div>
                                                <i class="fa-solid fa-envelope" style="color:#888; width:15px;"></i>
                                                <?php echo $cus->email; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td data-label="Ngày ĐK">
                                    <?php echo date('d/m/Y', strtotime($cus->created_at)); ?>
                                </td>

                                <td data-label="Trạng thái">
                                    <?php if ($cus->status == 1): ?>
                                        <span class="badge bg-success">Hoạt động</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Đã khóa</span>
                                    <?php endif; ?>
                                </td>
                                <td data-label="Đơn hàng">

                                    <a href="/admin/customer/history/<?php echo $cus->id; ?>" class="btn-icon edit" title="Xem lịch sử mua hàng" style="color: #007bff; border-color: #007bff; margin-right: 5px;">
                                        <i class="fa-solid fa-address-card"></i>
                                    </a>

        </div>
        </td>
        <td data-label="Hành động">
            <div class="action-btns">

                <?php if ($cus->status == 1): ?>
                    <a href="/admin/customer/toggleStatus/<?php echo $cus->id; ?>/1"
                        class="btn-icon delete" title="Khóa tài khoản này"
                        onclick="return confirm('Bạn muốn KHÓA tài khoản này? Khách sẽ không thể đăng nhập mua hàng.');">
                        <i class="fa-solid fa-lock"></i>
                    </a>
                <?php else: ?>
                    <a href="/admin/customer/toggleStatus/<?php echo $cus->id; ?>/0"
                        class="btn-icon edit" title="Mở khóa tài khoản"
                        style="color:green; border-color:green;">
                        <i class="fa-solid fa-lock-open"></i>
                    </a>
                <?php endif; ?>

                <a href="/admin/customer/delete/<?php echo $cus->id; ?>"
                    class="btn-icon delete" title="Xóa vĩnh viễn"
                    onclick="return confirm('CẢNH BÁO: Xóa khách hàng này sẽ làm mất lịch sử đơn hàng cũ của họ. Bạn có chắc không?');">
                    <i class="fa-solid fa-trash"></i>
                </a>
                <a href="/admin/customer/edit/<?php echo $cus->id; ?>" class="btn-icon edit" title="Sửa thông tin">
                    <i class="fa-solid fa-pen"></i>
                </a>
            </div>
        </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="6" style="text-align: center; padding: 30px; color: #999;">
            Chưa có khách hàng nào đăng ký.
        </td>
    </tr>
<?php endif; ?>
</tbody>
</table>
    </div>
</div>
</div>