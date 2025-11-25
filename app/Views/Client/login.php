<!-- Giao diện Login của bạn -->
<section class="auth-section section-padding">
    <div class="auth-wrapper">
        <h2 class="auth-title">Đăng nhập</h2>
        <p class="auth-subtitle">Vui lòng đăng nhập để tiếp tục mua sắm</p>

        <!-- KHU VỰC HIỂN THỊ LỖI PHP -->
        <?php if (isset($error)): ?>
            <div class="alert-error" style="background: #ffebee; color: #c62828; padding: 10px; border-radius: 4px; margin-bottom: 20px; font-size: 14px; text-align: center; border: 1px solid #ef9a9a;">
                <i class="fa-solid fa-triangle-exclamation"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- FORM ĐĂNG NHẬP (Đã sửa action và method) -->
        <form action="/auth/login" method="POST">
            <div class="form-group">
                <label>Số điện thoại / Email</label>
                <!-- Thêm name="account" và value để giữ lại giá trị cũ -->
                <input type="text" name="account" placeholder="Nhập thông tin..." required
                    value="<?php echo isset($_POST['account']) ? htmlspecialchars($_POST['account']) : ''; ?>">
            </div>
            <div class="form-group">
                <label>Mật khẩu</label>
                <!-- Thêm name="password" -->
                <input type="password" name="password" placeholder="Nhập mật khẩu..." required>
            </div>

            <div class="form-actions">
                <label><input type="checkbox"> Ghi nhớ tôi</label>
                <a href="#" class="forgot-pass">Quên mật khẩu?</a>
            </div>

            <button type="submit" class="btn-submit">ĐĂNG NHẬP</button>
        </form>

        <div class="social-login">
            <p class="social-label">Hoặc tiếp tục với</p>
            <div class="social-btns">
                <button class="btn-face"><i class="fa-brands fa-facebook"></i> Facebook</button>
                <button class="btn-google"><i class="fa-brands fa-google"></i> Google</button>
            </div>
        </div>

        <div class="auth-switch">
            Bạn chưa có tài khoản? <a href="/auth/register">Đăng ký ngay</a>
        </div>

        <!-- Link về trang chủ cho tiện -->
        <div style="margin-top: 15px; text-align: center;">
            <a href="/" style="color: #666; text-decoration: none; font-size: 13px;">
                <i class="fa-solid fa-arrow-left"></i> Về trang chủ
            </a>
        </div>
    </div>
</section>