<section class="cart-section container section-padding">
    <div class="cart-layout">

        <div class="cart-list-area">

            <div class="cart-header-row">
                <div class="col-product">Sản phẩm</div>
                <div class="col-price">Đơn giá</div>
                <div class="col-qty">Số lượng</div>
                <div class="col-total">Thành tiền</div>
                <div class="col-action"></div>
            </div>

            <?php if (!empty($cart)): ?>
                <?php foreach ($cart as $id => $item): ?>

                    <?php
                    // Xử lý ảnh
                    $img = !empty($item['image']) ? '/assets/uploads/products/' . $item['image'] : 'https://placehold.co/100x100';

                    // Kiểm tra cờ quà tặng
                    $isGift = isset($item['is_gift']) && $item['is_gift'] == true;

                    // Tính thành tiền (Nếu là quà thì = 0)
                    $lineTotal = $isGift ? 0 : ($item['price'] * $item['qty']);
                    ?>

                    <?php if ($isGift): ?>

                        <div class="cart-item cart-item-gift">
                            <div class="item-img">
                                <img src="<?php echo $img; ?>" alt="<?php echo $item['name']; ?>">
                                <span class="gift-badge"><i class="fa-solid fa-gift"></i> Tặng</span>
                            </div>
                            <div class="item-info">
                                <h3><a href="/product/detail/<?php echo $item['id']; ?>"><?php echo $item['name']; ?></a></h3>
                                <p class="variant">Mã SP: <?php echo $item['id']; ?> - Quà tặng khuyến mãi</p>
                            </div>
                            <div class="item-price">
                                <span class="old-price"><?php echo number_format($item['price']); ?>đ</span>
                                <span class="free-price">0đ</span>
                            </div>
                            <div class="item-qty">
                                <span>x<?php echo $item['qty']; ?></span>
                            </div>
                            <div class="item-total text-green">Miễn phí</div>
                            <div class="item-remove"></div>
                        </div>

                    <?php else: ?>

                        <div class="cart-item">
                            <div class="item-img">
                                <img src="<?php echo $img; ?>" alt="<?php echo $item['name']; ?>">
                            </div>
                            <div class="item-info">
                                <h3><a href="/product/detail/<?php echo $item['id']; ?>"><?php echo $item['name']; ?></a></h3>
                                <p class="variant">Mã SP: <?php echo $item['id']; ?></p>
                            </div>
                            <div class="item-price">
                                <?php echo number_format($item['price']); ?>đ
                            </div>
                            <div class="item-qty">
                                <div class="qty-control-small">
                                    <a href="/cart/update/<?php echo $id; ?>/decrease" style="text-decoration:none;">
                                        <button type="button">-</button>
                                    </a>

                                    <input type="number" value="<?php echo $item['qty']; ?>" min="1" readonly>

                                    <a href="/cart/update/<?php echo $id; ?>/increase" style="text-decoration:none;">
                                        <button type="button">+</button>
                                    </a>
                                </div>
                            </div>
                            <div class="item-total">
                                <?php echo number_format($lineTotal); ?>đ
                            </div>
                            <div class="item-remove">
                                <a href="/cart/remove/<?php echo $id; ?>" onclick="return confirm('Xóa sản phẩm này?');">
                                    <button type="button"><i class="fa-regular fa-trash-can"></i></button>
                                </a>
                            </div>
                        </div>

                    <?php endif; ?>

                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align:center; padding: 40px; color: #999;">
                    <i class="fa-solid fa-cart-arrow-down" style="font-size: 40px; margin-bottom: 10px;"></i>
                    <p>Giỏ hàng của bạn đang trống.</p>
                    <a href="/" style="color: var(--primary-color); font-weight: bold;">Tiếp tục mua sắm</a>
                </div>
            <?php endif; ?>

            <div class="cart-footer-actions">
                <a href="/" class="btn-continue"><i class="fa-solid fa-arrow-left"></i> Tiếp tục xem thuốc</a>
            </div>
        </div>

        <div class="cart-summary-area">
            <div class="summary-box">
                <h3 class="summary-title">Cộng giỏ hàng</h3>
                <div class="summary-row">
                    <span>Tạm tính:</span>
                    <span class="temp-total"><?php echo number_format($total_money ?? 0); ?>đ</span>
                </div>
                <div class="summary-row">
                    <span>Giảm giá:</span>
                    <span>0đ</span>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-row total-row">
                    <span>Tổng tiền:</span>
                    <span class="final-total"><?php echo number_format($total_money ?? 0); ?>đ</span>
                </div>
                <p class="vat-note">(Đã bao gồm VAT nếu có)</p>

                <?php if (!empty($cart)): ?>
                    <a href="/checkout" style="text-decoration: none;">
                        <button class="btn-checkout">TIẾN HÀNH THANH TOÁN</button>
                    </a>
                <?php else: ?>
                    <button class="btn-checkout" disabled style="background:#ccc; cursor:not-allowed;">TIẾN HÀNH THANH TOÁN</button>
                <?php endif; ?>

                <div class="support-note">
                    <i class="fa-solid fa-shield-halved"></i>
                    <p>Cam kết chính hãng 100%<br>Hoàn tiền nếu phát hiện hàng giả</p>
                </div>
            </div>
        </div>

    </div>
</section>

<script>
    function formatMoney(amount) {
        return amount.toLocaleString('vi-VN') + 'đ';
    }

    function updateCart(btn, change) {
        const row = btn.closest('.cart-item');
        const input = row.querySelector('input');
        const priceEl = row.querySelector('.item-price');
        const totalEl = row.querySelector('.item-total');

        let qty = parseInt(input.value) + change;
        if (qty < 1) qty = 1;
        input.value = qty;

        const price = parseInt(priceEl.getAttribute('data-price'));
        const total = price * qty;
        totalEl.textContent = formatMoney(total);
        updateGrandTotal();
    }

    function removeItem(btn) {
        if (confirm('Xóa sản phẩm này?')) {
            btn.closest('.cart-item').remove();
            updateGrandTotal();
        }
    }

    function updateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.cart-item:not(.cart-item-gift)').forEach(item => {
            const input = item.querySelector('input');
            const price = parseInt(item.querySelector('.item-price').getAttribute('data-price'));
            grandTotal += price * parseInt(input.value);
        });
        document.querySelector('.temp-total').textContent = formatMoney(grandTotal);
        document.querySelector('.final-total').textContent = formatMoney(grandTotal);
    }
</script>