<div class="auth-container" style="max-width: 400px; margin: 50px auto; padding: 30px; border: 1px solid #ddd; border-radius: 8px; background: #fff;">
    <h2 style="text-align: center; margin-bottom: 20px; color: #007bff;">QUÊN MẬT KHẨU</h2>

    <?php if (isset($error)): ?>
        <p style="color: red; background: #ffe6e6; padding: 10px; border-radius: 4px; font-size:13px;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <p style="color: green; background: #e6fffa; padding: 10px; border-radius: 4px; font-size:13px;"><?php echo $success; ?></p>
    <?php else: ?>

        <p style="color:#666; font-size:14px; margin-bottom:15px;">
            Nhập email của bạn để nhận mật khẩu mới.
        </p>

        <form action="/auth/sendResetLink" method="POST">
            <div class="form-group" style="margin-bottom: 15px;">
                <input type="email" name="email" required placeholder="Nhập email..."
                    style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <button type="submit" style="width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
                LẤY LẠI MẬT KHẨU
            </button>
        </form>

    <?php endif; ?>

    <div style="text-align: center; margin-top: 20px;">
        <a href="/auth/login" style="text-decoration: none; font-size: 14px; color: #555;">Quay lại Đăng nhập</a>
    </div>
</div>