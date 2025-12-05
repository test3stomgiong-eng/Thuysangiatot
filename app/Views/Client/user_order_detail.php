<div class="breadcrumb container">
    <a href="/">Trang chủ</a> <i class="fa-solid fa-angle-right"></i>
    <a href="/user">Tài khoản</a> <i class="fa-solid fa-angle-right"></i>
    <span>Đơn hàng #<?php echo $order->code; ?></span>
</div>

<section class="order-detail-page container section-padding" style="margin-bottom: 50px;">
    
    <div class="data-card" style="background: #fff; padding: 30px; border-radius: 8px; border: 1px solid #eee; max-width: 900px; margin: 0 auto;">
        
        <div style="border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 20px; display: flex; justify-content: space-between; flex-wrap: wrap;">
            <div>
                <h2 style="margin: 0; color: #007bff; font-size: 24px;">ĐƠN HÀNG #<?php echo $order->code; ?></h2>
                <p style="color: #666; margin: 5px 0;">Ngày đặt: <?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></p>
                
                <?php 
                    $statusColors = [
                        'pending'   => ['orange', 'Chờ xử lý'],
                        'shipping'  => ['blue', 'Đang giao hàng'],
                        'completed' => ['green', 'Hoàn thành'],
                        'cancelled' => ['red', 'Đã hủy']
                    ];
                    $st = $statusColors[$order->status] ?? ['gray', 'Không rõ'];
                ?>
                <span style="display: inline-block; background: <?php echo $st[0]; ?>; color: white; padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                    <?php echo $st[1]; ?>
                </span>
            </div>
            
            <div style="text-align: right;">
                <a href="/user" style="text-decoration: none; color: #666; font-size: 14px;">
                    <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách
                </a>
            </div>
        </div>

        <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <h4 style="margin-top: 0; margin-bottom: 10px;">Thông tin nhận hàng</h4>
            <p style="margin: 5px 0;"><strong>Người nhận:</strong> <?php echo $order->customer_name; ?></p>
            <p style="margin: 5px 0;"><strong>Điện thoại:</strong> <?php echo $order->customer_phone; ?></p>
            <p style="margin: 5px 0;"><strong>Địa chỉ:</strong> <?php echo $order->shipping_address; ?></p>
            <p style="margin: 5px 0; font-style: italic; color: #666;"><strong>Ghi chú:</strong> <?php echo $order->note ? $order->note : 'Không có'; ?></p>
        </div>

        <div class="table-responsive">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #eee;">
                        <th style="padding: 10px; text-align: left;">Sản phẩm</th>
                        <th style="padding: 10px; text-align: center;">Đơn giá</th>
                        <th style="padding: 10px; text-align: center;">Số lượng</th>
                        <th style="padding: 10px; text-align: right;">Tạm tính</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($details as $item): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 10px; display: flex; align-items: center; gap: 10px;">
                                <?php $img = !empty($item->main_image) ? '/assets/uploads/products/'.$item->main_image : 'https://placehold.co/50x50'; ?>
                                <img src="<?php echo $img; ?>" style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #eee;">
                                <div>
                                    <strong><?php echo $item->product_name; ?></strong>
                                    <?php if(!empty($item->sku)): ?>
                                        <br><span style="font-size: 12px; color: #888;">Mã: <?php echo $item->sku; ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td style="padding: 10px; text-align: center;"><?php echo number_format($item->price); ?>đ</td>
                            <td style="padding: 10px; text-align: center;">x<?php echo $item->quantity; ?></td>
                            <td style="padding: 10px; text-align: right; font-weight: bold;">
                                <?php echo number_format($item->total_price); ?>đ
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="padding: 10px; text-align: right; font-weight: bold;">Tổng tiền hàng:</td>
                        <td style="padding: 10px; text-align: right; font-weight: bold; color: #d0021b; font-size: 18px;">
                            <?php echo number_format($order->total_money); ?>đ
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
    </div>

</section>