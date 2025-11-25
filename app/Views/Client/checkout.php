<section class="checkout-section container section-padding">
    <!-- SỬA 1: Form trỏ về Controller xử lý, method POST -->
    <form class="checkout-layout" action="/checkout/process" method="POST">

        <div class="checkout-info">
            <h3 class="block-title"><i class="fa-solid fa-location-dot"></i> Thông tin giao hàng</h3>

            <div class="form-group-row">
                <div class="form-group">
                    <label>Họ và tên <span class="required">*</span></label>
                    <!-- SỬA 2: Thêm name="fullname" -->
                    <input type="text" name="fullname" placeholder="Nhập họ tên của bạn" required>
                </div>
                <div class="form-group">
                    <label>Số điện thoại <span class="required">*</span></label>
                    <!-- SỬA 3: Thêm name="phone" -->
                    <input type="tel" name="phone" placeholder="Ví dụ: 0912345678" required>
                </div>
            </div>

            <div class="form-group">
                <label>Email (Để nhận hóa đơn)</label>
                <!-- Thêm name="email" -->
                <input type="email" name="email" placeholder="example@gmail.com">
            </div>

            <div class="form-group">
                <label>Địa chỉ cụ thể <span class="required">*</span></label>
                <!-- SỬA 4: Thêm name="address" -->
                <input type="text" name="address" placeholder="Số nhà, tên đường..." required>
            </div>

            <div class="form-group">
                <label>Địa chỉ nhận hàng đầy đủ <span class="required">*</span></label>
                <!-- Gợi ý khách nhập đầy đủ 4 cấp -->
                <textarea name="address" rows="2" placeholder="Vui lòng nhập rõ: Số nhà, Tên đường, Xã/Phường, Quận/Huyện, Tỉnh/Thành phố..." required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;"></textarea>
            </div>

            <div class="form-group">
                <label>Ghi chú đơn hàng</label>
                <!-- SỬA 5: Thêm name="note" -->
                <textarea name="note" rows="3" placeholder="Ví dụ: Giao hàng giờ hành chính..."></textarea>
            </div>

            <h3 class="block-title" style="margin-top: 30px;"><i class="fa-solid fa-wallet"></i> Phương thức thanh toán</h3>
            <div class="payment-methods">
                <label class="payment-item">
                    <!-- SỬA 6: Thêm name="payment_method" và value -->
                    <input type="radio" name="payment_method" value="COD" checked>
                    <div class="payment-content">
                        <i class="fa-solid fa-money-bill-wave"></i>
                        <span>Thanh toán khi nhận hàng (COD)</span>
                    </div>
                </label>
                <label class="payment-item">
                    <input type="radio" name="payment_method" value="BANK">
                    <div class="payment-content">
                        <i class="fa-solid fa-building-columns"></i>
                        <span>Chuyển khoản ngân hàng</span>
                    </div>
                </label>
                <label class="payment-item">
                    <input type="radio" name="payment_method" value="MOMO">
                    <div class="payment-content">
                        <i class="fa-solid fa-qrcode"></i>
                        <span>Ví MoMo / ZaloPay</span>
                    </div>
                </label>
            </div>
        </div>

        <div class="checkout-summary-col">
            <div class="summary-card">
                <h3 class="summary-header">Đơn hàng (<?php echo count($cart); ?> sản phẩm)</h3>

                <div class="summary-list">
                    <!-- SỬA 7: Vòng lặp hiển thị sản phẩm thật trong giỏ -->
                    <?php foreach ($cart as $item): ?>
                        <?php
                        // Xử lý ảnh (Nếu không có ảnh thật thì dùng ảnh placeholder)
                        $img = !empty($item['image']) ? '/assets/uploads/' . $item['image'] : 'https://placehold.co/60x60?text=No+Img';
                        ?>
                        <div class="summary-item">
                            <div class="img-wrapper"><img src="<?php echo $img; ?>" alt="<?php echo $item['name']; ?>"></div>
                            <div class="info">
                                <h4><?php echo $item['name']; ?></h4>
                                <p>x<?php echo $item['qty']; ?></p>
                            </div>
                            <div class="price"><?php echo number_format($item['price'] * $item['qty'], 0, ',', '.'); ?>đ</div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="summary-pricing">
                    <div class="row">
                        <span>Tạm tính</span>
                        <!-- SỬA 8: Hiển thị tổng tiền thật -->
                        <span><?php echo number_format($total_money, 0, ',', '.'); ?>đ</span>
                    </div>
                    <div class="row">
                        <span>Phí vận chuyển</span>
                        <span>0đ (Miễn phí vận chuyển) </span>
                    </div>
                    <div class="divider"></div>
                    <div class="row total">
                        <span>Tổng cộng</span>
                        <!-- Cộng thêm 30k ship -->
                        <span class="total-price"><?php echo number_format($total_money, 0, ',', '.'); ?>đ</span>
                    </div>
                </div>

                <button type="submit" class="btn-place-order">ĐẶT HÀNG NGAY</button>
                <p class="policy-text">Bằng cách đặt hàng, bạn đồng ý với <a href="#">Điều khoản sử dụng</a> của TS-AQUA Pharma.</p>
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