 <!-- Giao diện Đăng ký -->
    <section class="auth-section section-padding">
        <div class="auth-wrapper">
            <h2 class="auth-title">Tạo tài khoản mới</h2>
            <p class="auth-subtitle">Đăng ký thành viên để tích điểm và nhận ưu đãi</p>

            <!-- KHU VỰC HIỂN THỊ LỖI PHP -->
            <?php if(isset($error)): ?>
                <div class="alert-error" style="background: #ffebee; color: #c62828; padding: 10px; border-radius: 4px; margin-bottom: 20px; font-size: 14px; text-align: center; border: 1px solid #ef9a9a;">
                    <i class="fa-solid fa-triangle-exclamation"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- FORM ĐĂNG KÝ -->
            <form action="/auth/registerPost" method="POST">
                
                <!-- Họ tên -->
                <div class="form-group">
                    <label>Họ và tên <span class="req">*</span></label>
                    <input type="text" name="fullname" placeholder="Ví dụ: Nguyễn Văn A" required
                           value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>">
                </div>

                <!-- Số điện thoại -->
                <div class="form-group">
                    <label>Số điện thoại <span class="req">*</span></label>
                    <input type="tel" name="phone" placeholder="Nhập số điện thoại..." required
                           value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                </div>

                <!-- Email (Thêm vào cho đầy đủ logic) -->
                <div class="form-group">
                    <label>Email (Tùy chọn)</label>
                    <input type="email" name="email" placeholder="email@example.com"
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>

                <!-- Mật khẩu -->
                <div class="form-group">
                    <label>Mật khẩu <span class="req">*</span></label>
                    <input type="password" name="password" placeholder="Tự tạo mật khẩu..." required>
                </div>

                <!-- Nhập lại mật khẩu -->
                <div class="form-group">
                    <label>Xác nhận mật khẩu <span class="req">*</span></label>
                    <input type="password" name="repassword" placeholder="Nhập lại mật khẩu..." required>
                </div>

                <button type="submit" class="btn-submit">ĐĂNG KÝ TÀI KHOẢN</button>
            </form>

            <div class="auth-switch">
                Bạn đã có tài khoản? <a href="/auth/login">Đăng nhập ngay</a>
            </div>
            
            <!-- Link về trang chủ -->
            <div style="margin-top: 15px; text-align: center;">
                 <a href="/" style="color: #666; text-decoration: none; font-size: 13px;">
                    <i class="fa-solid fa-arrow-left"></i> Về trang chủ
                </a>
            </div>
        </div>
    </section>