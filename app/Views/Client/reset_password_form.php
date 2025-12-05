<div class="auth-container" style="max-width: 400px; margin: 50px auto; padding: 30px; border: 1px solid #ddd; border-radius: 8px; background: #fff;">
    <h2 style="text-align: center; margin-bottom: 20px; color: #28a745;">ĐẶT MẬT KHẨU MỚI</h2>

    <form action="/auth/saveNewPass" method="POST">
        <input type="hidden" name="token" value="<?php echo $token; ?>">

        <div class="form-group" style="margin-bottom: 15px;">
            <label>Mật khẩu mới</label>
            <input type="password" name="password" required placeholder="******"
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label>Xác nhận mật khẩu</label>
            <input type="password" name="repassword" required placeholder="******"
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <button type="submit" style="width: 100%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
            LƯU THAY ĐỔI
        </button>
    </form>
</div>