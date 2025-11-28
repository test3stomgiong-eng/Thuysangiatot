<div class="content-body">
    <div class="page-header-row">
        <h2 class="page-title">Danh sách đơn hàng</h2>
        <button class="btn-action btn-blue"><i class="fa-solid fa-file-export"></i> Xuất Excel</button>
    </div>

    <form action="/admin/order" method="GET">
        <div class="filter-bar">
            <div class="search-wrapper">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="keyword" 
                       value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : ''; ?>" 
                       placeholder="Tìm mã đơn, tên khách, SĐT...">
            </div>
            
            <select class="filter-select" name="status" onchange="this.form.submit()">
                <option value="">-- Tất cả trạng thái --</option>
                <?php 
                    $status_list = [
                        'pending'   => 'Chờ xử lý',
                        'shipping'  => 'Đang giao hàng',
                        'completed' => 'Đã giao (Hoàn thành)',
                        'cancelled' => 'Đã hủy'
                    ];
                    foreach($status_list as $key => $label): 
                ?>
                    <option value="<?php echo $key; ?>" <?php echo (isset($_GET['status']) && $_GET['status'] == $key) ? 'selected' : ''; ?>>
                        <?php echo $label; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            </div>
    </form>

    <div class="data-card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Mã Đơn</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Thanh toán</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $item): ?>
                            <tr>
                                <td data-label="Mã đơn">
                                    <strong style="color: #007bff;">#<?php echo $item->code; ?></strong>
                                </td>
                                
                                <td data-label="Khách hàng">
                                    <strong><?php echo $item->customer_name; ?></strong><br>
                                    <span class="sub-text"><?php echo $item->customer_phone; ?></span>
                                </td>

                                <td data-label="Ngày đặt">
                                    <?php echo date('d/m/Y', strtotime($item->created_at)); ?><br>
                                    <span class="sub-text"><?php echo date('H:i A', strtotime($item->created_at)); ?></span>
                                </td>

                                <td data-label="Tổng tiền" class="text-red" style="color:#d0021b; font-weight:bold;">
                                    <?php echo number_format($item->total_money, 0, ',', '.'); ?>đ
                                </td>

                                <td data-label="Thanh toán">
                                    <?php 
                                        $pay = isset($item->payment_method) ? $item->payment_method : 'COD';
                                        if ($pay == 'COD') echo '<span class="badge-pay cod" style="background:#eee; padding:3px 8px; border-radius:4px; font-size:12px;">COD</span>';
                                        elseif ($pay == 'Banking') echo '<span class="badge-pay bank" style="background:#e3f2fd; color:#0d47a1; padding:3px 8px; border-radius:4px; font-size:12px;">Banking</span>';
                                        else echo '<span class="badge-pay momo" style="background:#fce4ec; color:#c2185b; padding:3px 8px; border-radius:4px; font-size:12px;">'.$pay.'</span>';
                                    ?>
                                </td>

                                <td data-label="Trạng thái">
                                    <?php 
                                        switch ($item->status) {
                                            case 'pending':
                                                echo '<span class="status pending" style="color:orange; font-weight:bold;">Chờ xử lý</span>';
                                                break;
                                            case 'shipping':
                                                echo '<span class="status shipping" style="color:#17a2b8; font-weight:bold;">Đang giao</span>';
                                                break;
                                            case 'completed':
                                                echo '<span class="status completed" style="color:green; font-weight:bold;">Hoàn thành</span>';
                                                break;
                                            case 'cancelled':
                                                echo '<span class="status cancelled" style="color:red; font-weight:bold;">Đã hủy</span>';
                                                break;
                                        }
                                    ?>
                                </td>

                                <td data-label="Hành động">
                                    <a href="/admin/order/detail/<?php echo $item->id; ?>" class="btn-table blue" style="text-decoration:none; color:#007bff; font-weight:600;">
                                        <i class="fa-regular fa-eye"></i> Xem
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 30px; color: #999;">
                                Không tìm thấy đơn hàng nào.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        </div>
</div>