<div class="content-body">
    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="alert-box success" style="background: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-circle-check" style="font-size: 18px;"></i>
            <span><?php echo $_SESSION['flash_success']; ?></span>

            <?php unset($_SESSION['flash_success']); ?>
        </div>
    <?php endif; ?>
    <div class="page-header-row">
        <div>
            <h2 class="page-title">Đơn hàng <span style="color:#007bff">#<?php echo $order->code; ?></span></h2>
            <span class="sub-text">Đặt ngày: <?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></span>
        </div>
        <div class="actions">
            <a href="/admin/order" class="btn-action btn-gray"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
            <button class="btn-action btn-blue" onclick="window.print()"><i class="fa-solid fa-print"></i> In hóa đơn</button>
        </div>
    </div>

    <div class="form-layout">

        <div class="form-col-main">
            <div class="data-card form-card">
                <h3 class="card-title">Danh sách sản phẩm</h3>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Đơn giá</th>
                                <th>Số lượng</th>
                                <th class="text-right">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($details as $item): ?>
                                <tr>
                                    <td style="display: flex; gap: 10px; align-items: center;">
                                        <?php
                                        $img = !empty($item->main_image) ? '/assets/uploads/products/' . $item->main_image : 'https://placehold.co/50x50';
                                        ?>
                                        <img src="<?php echo $img; ?>" class="table-img" style="width:50px; height:50px; object-fit:cover; border:1px solid #eee;">
                                        <div>
                                            <strong><?php echo $item->product_name; ?></strong><br>
                                            <span class="sub-text">SKU: <?php echo $item->sku ?? '---'; ?></span>
                                        </div>
                                    </td>

                                    <td><?php echo number_format($item->price, 0, ',', '.'); ?>đ</td>
                                    <td>x <?php echo $item->quantity; ?></td>
                                    <td class="text-right" style="color:#d0021b; font-weight:bold;">
                                        <?php echo number_format($item->total_price, 0, ',', '.'); ?>đ
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right"><strong>Tổng tiền:</strong></td>
                                <td class="text-right text-red" style="font-size: 18px; font-weight: bold;">
                                    <?php echo number_format($order->total_money, 0, ',', '.'); ?>đ
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="data-card form-card">
                <h3 class="card-title">Ghi chú của khách hàng</h3>
                <p class="note-text" style="font-style:italic; color:#666;">
                    <?php echo !empty($order->note) ? $order->note : 'Không có ghi chú.'; ?>
                </p>
            </div>
        </div>

        <div class="form-col-side">

            <div class="data-card form-card">
                <h3 class="card-title">Trạng thái đơn hàng</h3>

                <form action="/admin/order/updateStatus/<?php echo $order->id; ?>" method="POST">
                    <div class="form-group">
                        <label>Cập nhật trạng thái:</label>
                        <select name="status" class="status-select" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
                            <option value="pending" <?php echo ($order->status == 'pending') ? 'selected' : ''; ?>>Chờ xử lý</option>
                            <option value="shipping" <?php echo ($order->status == 'shipping') ? 'selected' : ''; ?>>Đang giao hàng</option>
                            <option value="completed" <?php echo ($order->status == 'completed') ? 'selected' : ''; ?>>Đã giao hàng</option>
                            <option value="cancelled" <?php echo ($order->status == 'cancelled') ? 'selected' : ''; ?>>Hủy đơn</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-action btn-green" style="width: 100%; border:none; cursor:pointer;">
                        Cập nhật
                    </button>
                </form>
            </div>

            <div class="data-card form-card">
                <h3 class="card-title">Thông tin khách hàng</h3>
                <div class="info-row" style="margin-bottom:10px; display:flex; gap:10px;">
                    <i class="fa-solid fa-user" style="color:#888; margin-top:3px;"></i>
                    <div>
                        <strong><?php echo $order->customer_name; ?></strong>
                    </div>
                </div>

                <div class="info-row" style="margin-bottom:10px; display:flex; gap:10px;">
                    <i class="fa-solid fa-phone" style="color:#888; margin-top:3px;"></i>
                    <span>
                        <a href="tel:<?php echo $order->customer_phone; ?>" style="text-decoration:none; color:#333;">
                            <?php echo $order->customer_phone; ?>
                        </a>
                    </span>
                </div>
            </div>

            <div class="data-card form-card">
                <h3 class="card-title">Địa chỉ giao hàng</h3>
                <p class="address-text" style="line-height:1.5;">
                    <?php echo $order->shipping_address; ?>
                </p>
            </div>

        </div>
    </div>
</div>