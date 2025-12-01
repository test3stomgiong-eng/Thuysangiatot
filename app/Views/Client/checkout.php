
<section class="checkout-section container section-padding">
    
    <form class="checkout-layout" action="/checkout/process" method="POST">

        <div class="checkout-info">
            <h3 class="block-title">
                <i class="fa-solid fa-location-dot"></i> Thông tin giao hàng
            </h3>

            <div class="form-group-row">
                <div class="form-group">
                    <label>Họ và tên <span class="required">*</span></label>
                    <input type="text" name="fullname" required class="form-control" 
                           placeholder="Nhập họ tên..."
                           value="<?php echo isset($user) ? $user->fullname : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Số điện thoại <span class="required">*</span></label>
                    <input type="tel" name="phone" required class="form-control" 
                           placeholder="09xxxxxxx"
                           value="<?php echo isset($user) ? $user->phone : ''; ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" 
                       placeholder="email@example.com"
                       value="<?php echo isset($user) ? $user->email : ''; ?>">
            </div>

            <div class="form-group">
                <label>Địa chỉ nhận hàng <span class="required">*</span></label>
                <textarea name="address" rows="3" required class="form-control" 
                          placeholder="Số nhà, đường, phường/xã..."><?php echo isset($user) ? $user->address : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label>Ghi chú đơn hàng</label>
                <textarea name="note" rows="2" class="form-control" 
                          placeholder="Giao giờ hành chính..."></textarea>
            </div>

            <h3 class="block-title">
                <i class="fa-solid fa-wallet"></i> Phương thức thanh toán
            </h3>
            
            <div class="payment-methods">
                <label class="payment-item">
                    <input type="radio" name="payment_method" value="COD" checked>
                    <span class="payment-content">
                        <i class="fa-solid fa-money-bill-wave"></i> Thanh toán khi nhận hàng (COD)
                    </span>
                </label>
                
                <label class="payment-item">
                    <input type="radio" name="payment_method" value="BANK">
                    <span class="payment-content">
                        <i class="fa-solid fa-building-columns"></i> Chuyển khoản ngân hàng
                    </span>
                </label>
            </div>
        </div>

        <div class="checkout-summary-col">
            <div class="summary-card">
                <h3 class="summary-header">Đơn hàng (<?php echo count($cart); ?> sản phẩm)</h3>

                <div class="summary-list">
                    <?php foreach ($cart as $item): ?>
                        <?php
                            $img = !empty($item['image']) ? '/assets/uploads/products/' . $item['image'] : 'https://placehold.co/60x60';
                        ?>
                        <div class="summary-item">
                            <div class="img-wrapper">
                                <img src="<?php echo $img; ?>" alt="<?php echo $item['name']; ?>">
                            </div>
                            <div class="info">
                                <h4><?php echo $item['name']; ?></h4>
                                <p>x<?php echo $item['qty']; ?></p>
                            </div>
                            <div class="price">
                                <?php echo number_format($item['price'] * $item['qty'], 0, ',', '.'); ?>đ
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="summary-pricing">
                    <div class="row">
                        <span>Tạm tính</span>
                        <span><?php echo number_format($total_money, 0, ',', '.'); ?>đ</span>
                    </div>
                    <div class="row">
                        <span>Phí vận chuyển</span>
                        <span>0đ</span>
                    </div>
                    <div class="divider"></div>
                    <div class="row total">
                        <span>Tổng cộng</span>
                        <span class="total-price"><?php echo number_format($total_money, 0, ',', '.'); ?>đ</span>
                    </div>
                </div>

                <button type="submit" class="btn-place-order">ĐẶT HÀNG NGAY</button>
                
                <p class="policy-text">
                    Bằng cách đặt hàng, bạn đồng ý với <a href="#">Điều khoản sử dụng</a>.
                </p>
            </div>
        </div>

    </form>
</section>

<script>
    function handleCheckout(event) {
        event.preventDefault();
        alert('Đặt hàng thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất.');
        window.location.href = 'index.html';
    }
</script>