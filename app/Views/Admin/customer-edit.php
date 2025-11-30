<div class="content-body">
    <div class="page-header-row">
        <h2 class="page-title">Chỉnh sửa khách hàng</h2>
        <div class="actions">
            <a href="/admin/customer" class="btn-action btn-gray"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
            <button class="btn-action btn-green" onclick="document.getElementById('form-cus').submit()">Cập nhật</button>
        </div>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border-radius: 5px; border: 1px solid #f5c6cb;">
            <ul style="margin: 0; padding-left: 20px;">
                <?php foreach ($errors as $err): ?>
                    <li><?php echo $err; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form id="form-cus" action="/admin/customer/update/<?php echo $customer->id; ?>" method="POST" class="data-card form-card" style="max-width: 700px; margin: 0 auto;">
        
        <div class="form-group">
            <label>Họ và tên <span style="color:red">*</span></label>
            <input type="text" name="fullname" required 
                   value="<?php echo isset($old['fullname']) ? $old['fullname'] : $customer->fullname; ?>">
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label>Số điện thoại <span style="color:red">*</span></label>
                <input type="text" name="phone" required placeholder="09xxxxxxxx"
                       value="<?php echo isset($old['phone']) ? $old['phone'] : $customer->phone; ?>">
                <small style="color: #888;">(Định dạng: 10 số, bắt đầu bằng 0)</small>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" placeholder="email@example.com"
                       value="<?php echo isset($old['email']) ? $old['email'] : $customer->email; ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Đổi mật khẩu (Bỏ trống nếu không đổi)</label>
            <input type="password" name="password" placeholder="******" autocomplete="new-password">
        </div>

        <div class="form-group">
            <label>Trạng thái</label>
            <select name="status">
                <option value="1" <?php echo ($customer->status == 1) ? 'selected' : ''; ?>>Hoạt động</option>
                <option value="0" <?php echo ($customer->status == 0) ? 'selected' : ''; ?>>Bị khóa (Cấm đăng nhập)</option>
            </select>
        </div>

    </form>
</div>