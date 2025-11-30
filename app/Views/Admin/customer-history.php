<div class="content-body">
    <div class="page-header-row">
        <h2 class="page-title">Hồ sơ khách hàng</h2>
        <a href="/admin/customer" class="btn-action btn-gray"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
    </div>

    <div class="row" style="display: flex; gap: 20px; flex-wrap: wrap;">
        
        <div class="col-info" style="flex: 1; min-width: 300px;">
            <div class="data-card">
                <h3 class="card-title" style="border-bottom: 1px solid #eee; padding-bottom: 10px;">Thông tin cá nhân</h3>
                
                <div style="text-align: center; margin-bottom: 20px;">
                    <img src="https://placehold.co/100x100?text=User" style="border-radius: 50%; margin-bottom: 10px;">
                    <h3 style="margin: 0;"><?php echo $customer->fullname; ?></h3>
                    <span class="badge <?php echo ($customer->status == 1) ? 'bg-success' : 'bg-danger'; ?>">
                        <?php echo ($customer->status == 1) ? 'Hoạt động' : 'Đã khóa'; ?>
                    </span>
                </div>

                <ul style="list-style: none; padding: 0; line-height: 2;">
                    <li><i class="fa-solid fa-phone" style="width: 20px; color: #888;"></i> <strong><?php echo $customer->phone; ?></strong></li>
                    <li><i class="fa-solid fa-envelope" style="width: 20px; color: #888;"></i> <?php echo !empty($customer->email) ? $customer->email : '---'; ?></li>
                    <li><i class="fa-solid fa-map-marker-alt" style="width: 20px; color: #888;"></i> <?php echo !empty($customer->address) ? $customer->address : 'Chưa cập nhật địa chỉ'; ?></li>
                    <li><i class="fa-solid fa-calendar" style="width: 20px; color: #888;"></i> Tham gia: <?php echo date('d/m/Y', strtotime($customer->created_at)); ?></li>
                </ul>
            </div>
        </div>

        <div class="col-orders" style="flex: 2; min-width: 400px;">
            <div class="data-card">
                <h3 class="card-title" style="border-bottom: 1px solid #eee; padding-bottom: 10px;">
                    Lịch sử đơn hàng (<?php echo count($orders); ?>)
                </h3>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Ngày mua</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($orders)): ?>
                                <?php foreach ($orders as $item): ?>
                                    <tr>
                                        <td><strong style="color: #007bff;">#<?php echo $item->code; ?></strong></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($item->created_at)); ?></td>
                                        <td style="color: red; font-weight: bold;">
                                            <?php echo number_format($item->total_money, 0, ',', '.'); ?>đ
                                        </td>
                                        <td>
                                            <?php 
                                                switch ($item->status) {
                                                    case 'pending': echo '<span class="badge bg-warning text-dark">Chờ xử lý</span>'; break;
                                                    case 'shipping': echo '<span class="badge bg-info text-dark">Đang giao</span>'; break;
                                                    case 'completed': echo '<span class="badge bg-success">Hoàn thành</span>'; break;
                                                    case 'cancelled': echo '<span class="badge bg-danger">Đã hủy</span>'; break;
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="/admin/order/detail/<?php echo $item->id; ?>" class="btn-icon edit" title="Xem đơn hàng">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; color: #999; padding: 20px;">Khách này chưa mua đơn nào.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>