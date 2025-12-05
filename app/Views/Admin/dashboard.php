<div class="content-body">
    <h2 class="page-title">Tổng quan kinh doanh</h2>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <h4>Doanh thu thực tế</h4>
                <h3><?php echo number_format($revenue ?? 0, 0, ',', '.'); ?>đ</h3>
            </div>
            <div class="stat-icon bg-blue"><i class="fa-solid fa-sack-dollar"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h4>Tổng đơn hàng</h4>
                <h3><?php echo number_format($count_order ?? 0); ?></h3>
            </div>
            <div class="stat-icon bg-green"><i class="fa-solid fa-cart-plus"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h4>Sản phẩm kho</h4>
                <h3><?php echo number_format($count_product ?? 0); ?></h3>
            </div>
            <div class="stat-icon bg-orange"><i class="fa-solid fa-box-open"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h4>Khách hàng</h4>
                <h3><?php echo number_format($count_user ?? 0); ?></h3>
            </div>
            <div class="stat-icon bg-red"><i class="fa-solid fa-users"></i></div>
        </div>
    </div>

    <div class="data-card">
        <div class="card-header">
            <h3>Đơn hàng mới nhất</h3>
            <a href="/admin/order" class="btn-action btn-blue" style="text-decoration:none; font-size:13px;">Xem tất cả</a>
        </div>
        
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Mã Đơn</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recent_orders)): ?>
                        <?php foreach ($recent_orders as $item): ?>
                            <tr>
                                <td><strong>#<?php echo $item->code; ?></strong></td>
                                
                                <td>
                                    <strong><?php echo $item->customer_name; ?></strong><br>
                                    <span class="sub-text" style="font-size:11px; color:#888;"><?php echo $item->customer_phone; ?></span>
                                </td>

                                <td>
                                    <?php echo date('d/m/Y', strtotime($item->created_at)); ?>
                                </td>

                                <td style="color:#d0021b; font-weight:bold;">
                                    <?php echo number_format($item->total_money, 0, ',', '.'); ?>đ
                                </td>

                                <td>
                                    <?php 
                                        switch ($item->status) {
                                            case 'pending': echo '<span class="status pending" style="color:orange; font-weight:bold;">Chờ xử lý</span>'; break;
                                            case 'shipping': echo '<span class="status shipping" style="color:#17a2b8; font-weight:bold;">Đang giao</span>'; break;
                                            case 'completed': echo '<span class="status completed" style="color:green; font-weight:bold;">Hoàn thành</span>'; break;
                                            case 'cancelled': echo '<span class="status cancelled" style="color:red; font-weight:bold;">Đã hủy</span>'; break;
                                        }
                                    ?>
                                </td>

                                <td>
                                    <a href="/admin/order/detail/<?php echo $item->id; ?>" class="btn-view" style="text-decoration:none; color:#007bff; font-weight:bold;">
                                        Chi tiết
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px; color: #999;">Chưa có đơn hàng nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>