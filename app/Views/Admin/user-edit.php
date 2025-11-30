<div class="content-body">
    <div class="page-header-row">
        <h2 class="page-title">Sửa thông tin: <?php echo $user->fullname; ?></h2>
        <div class="actions">
            <a href="/admin/user" class="btn-action btn-gray">Hủy bỏ</a>
            <button class="btn-action btn-green" onclick="document.getElementById('form-user').submit()">Cập nhật</button>
        </div>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" style="background:#f8d7da; color:#721c24; padding:15px; margin-bottom:20px; border-radius:5px;">
            <ul style="margin:0; padding-left:20px;">
                <?php foreach ($errors as $err): ?><li><?php echo $err; ?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form id="form-user" action="/admin/user/update/<?php echo $user->id; ?>" method="POST" class="data-card form-card" style="max-width: 600px; margin: 0 auto;">

        <div class="form-group">
            <label>Họ và tên <span style="color:red">*</span></label>
            <input type="text" name="fullname" required value="<?php echo $user->fullname; ?>">
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label>Email <span style="color:red">*</span></label>
                <input type="email" name="email" required value="<?php echo $user->email; ?>">
            </div>
            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text" name="phone" value="<?php echo $user->phone; ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Đổi mật khẩu (Bỏ trống nếu không đổi)</label>
            <input type="password" name="password" placeholder="Chỉ nhập khi muốn đổi pass...">
        </div>

        <div class="form-group">
            <label>Phân quyền</label>
            <select name="role" class="form-control" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">

                <option value="admin" <?php echo ($user->role == 'admin') ? 'selected' : ''; ?>>
                    Quản trị viên (Admin)
                </option>

                <option value="sale" <?php echo ($user->role == 'sale') ? 'selected' : ''; ?>>
                    Nhân viên bán hàng (Sale)
                </option>

                <option value="warehouse" <?php echo ($user->role == 'warehouse') ? 'selected' : ''; ?>>
                    Thủ kho (Warehouse)
                </option>

                <option value="editor" <?php echo ($user->role == 'editor') ? 'selected' : ''; ?>>
                    Biên tập viên (Editor)
                </option>

            </select>
        </div>

        <div class="form-group">
            <label>Trạng thái</label>
            <select name="status">
                <option value="1" <?php echo ($user->status == 1) ? 'selected' : ''; ?>>Đang làm việc</option>
                <option value="0" <?php echo ($user->status == 0) ? 'selected' : ''; ?>>Đã nghỉ việc</option>
            </select>
        </div>
    </form>
</div>