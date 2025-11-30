<div class="content-body">
    <div class="page-header-row">
        <h2 class="page-title">Thêm nhân viên mới</h2>
        <div class="actions">
            <a href="/admin/user" class="btn-action btn-gray">Hủy bỏ</a>
            <button class="btn-action btn-green" onclick="document.getElementById('form-user').submit()">Lưu lại</button>
        </div>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" style="background:#f8d7da; color:#721c24; padding:15px; margin-bottom:20px; border-radius:5px;">
            <ul style="margin:0; padding-left:20px;">
                <?php foreach($errors as $err): ?><li><?php echo $err; ?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form id="form-user" action="/admin/user/store" method="POST" class="data-card form-card" style="max-width: 600px; margin: 0 auto;">
        
        <div class="form-group">
            <label>Họ và tên <span style="color:red">*</span></label>
            <input type="text" name="fullname" required 
                   value="<?php echo isset($old['fullname']) ? $old['fullname'] : ''; ?>" 
                   placeholder="Nguyễn Văn A">
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label>Email đăng nhập <span style="color:red">*</span></label>
                <input type="email" name="email" required 
                       value="<?php echo isset($old['email']) ? $old['email'] : ''; ?>" 
                       placeholder="nhanvien@gmail.com">
            </div>
            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text" name="phone" 
                       value="<?php echo isset($old['phone']) ? $old['phone'] : ''; ?>" 
                       placeholder="09xxxxxxxx">
            </div>
        </div>

        <div class="form-group">
            <label>Mật khẩu <span style="color:red">*</span></label>
            <input type="password" name="password" required placeholder="******">
        </div>

        <div class="form-group">
            <label>Phân quyền (Role)</label>
            <select name="role" required style="width:100%; padding:10px; border:1px solid #ddd;">
                <option value="admin">Admin (Toàn quyền)</option>
                <option value="sale" selected>Sale (Quản lý đơn hàng)</option>
                <option value="warehouse">Warehouse (Quản lý kho)</option>
                <option value="editor">Editor (Viết tin tức)</option>
            </select>
        </div>

        <div class="form-group">
            <label>Trạng thái</label>
            <select name="status">
                <option value="1">Đang làm việc</option>
                <option value="0">Đã nghỉ việc</option>
            </select>
        </div>
    </form>
</div>